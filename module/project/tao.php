<?php
declare(strict_types=1);
/**
 * The tao file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectTao extends projectModel
{
    /**
     * Update project table when start a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doStart(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->start->requiredFields, 'notempty')
            ->checkIF($project->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when suspend a project.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access protected
     * @return bool
     */
    protected function doSuspend(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when close a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $oldProject
     *
     * @access protected
     * @return bool
     */
    protected function doClosed(int $projectID, object $project, object $oldProject): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->close->requiredFields, 'notempty')
            ->checkIF($project->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($project->realEnd != '', 'realEnd', 'ge', $oldProject->realBegan)
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doActivate(int $projectID ,object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project , 'readjustTime, readjustTask, comment')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Fetch undone tasks.
     *
     * @param  int $projectID
     * @access protected
     * @return array|false
     */
    protected function fetchUndoneTasks(int $projectID): array|false
    {
        return $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
            ->where('deadline')->notZeroDate()
            ->andWhere('status')->in('wait,doing')
            ->andWhere('project')->eq($projectID)
            ->fetchAll();
    }

    /**
     * Update start and end date of tasks.
     *
     * @param  array $tasks
     * @access protected
     * @return bool
     */
    protected function updateTasksStartAndEndDate(array $tasks): bool
    {
        foreach($tasks as $task)
        {
            if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
            {
                $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                $estStarted = date('Y-m-d', $estStartedTimeStamp);
                $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                if($estStarted > $project->end) $estStarted = $project->end;
                if($deadline > $project->end)   $deadline   = $project->end;

                $this->dao->update(TABLE_TASK)
                    ->set('estStarted')->eq($estStarted)
                    ->set('deadline')->eq($deadline)
                    ->where('id')->eq($task->id)
                    ->exec();

                if(dao::isError()) return false;
            }
            else
            {
                $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                if($deadline > $project->end) $deadline = $project->end;
                $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();

                if(dao::isError()) return false;
            }
        }

        return true;
    }

    /**
     * Get project details, including all contents of the TABLE_PROJECT.
     * 获取项目的详情，包含project表的所有内容。
     *
     * @param  int       $projectID
     * @access protected
     * @return object|false
     */
    protected function fetchProjectInfo(int $projectID): object|false
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        /* Filter the date is empty or 1970. */
        if($project and helper::isZeroDate($project->end)) $project->end = '';
        return $project;
    }

    /**
     * 创建项目后，将团队成员插入到TEAM表.
     * Insert into zt_team after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @access protected
     * @return array
     */
    protected function setProjectTeam(int $projectID, object $project, object $postData): array
    {
        /* Set team of project. */
        $members = isset($postData->rawdata->teamMembers) ? $postData->rawdata->teamMembers : array();
        array_push($members, $project->PM, $project->openedBy);
        $members = array_unique($members);
        $roles   = $this->loadModel('user')->getUserRoles(array_values($members));

        $teamMembers = array();
        foreach($members as $account)
        {
            if(empty($account)) continue;

            $member = new stdClass();
            $member->root    = $projectID;
            $member->type    = 'project';
            $member->account = $account;
            $member->role    = zget($roles, $account, '');
            $member->join    = helper::now();
            $member->days    = zget($project, 'days', 0);
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            $teamMembers[$account] = $member;
        }

        return $teamMembers;
    }

    /**
     * 创建项目后，创建默认的项目主库.
     * Create doclib after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @param  object $program
     * @access protected
     * @return bool 
     */
    protected function createDocLib(int $projectID, object $project, object $postData, object $program): bool
    {
        /* Create doc lib. */
        $this->app->loadLang('doc');
        $authorizedUsers = array();

        if($project->parent and $project->acl == 'program')
        {
            $stakeHolders    = $this->loadModel('stakeholder')->getStakeHolderPairs($project->parent);
            $authorizedUsers = array_keys($stakeHolders);

            foreach(explode(',', $project->whitelist) as $user)
            {
                if(empty($user)) continue;
                $authorizedUsers[$user] = $user;
            }

            $authorizedUsers[$project->PM]       = $project->PM;
            $authorizedUsers[$project->openedBy] = $project->openedBy;
            $authorizedUsers[$program->PM]       = $program->PM;
            $authorizedUsers[$program->openedBy] = $program->openedBy;
        }

        $lib = new stdclass();
        $lib->project   = $projectID;
        $lib->name      = $this->lang->doclib->main['project'];
        $lib->type      = 'project';
        $lib->main      = '1';
        $lib->acl       = 'default';
        $lib->users     = ',' . implode(',', array_filter($authorizedUsers)) . ',';
        $lib->vision    = zget($project, 'vision', 'rnd');
        $lib->addedBy   = $this->app->user->account;
        $lib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        return !dao::isError();
    }

    /**
     * 创建项目时，如果直接输入了产品名，则创建产品并与项目关联.
     * Create doclib after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @param  object $program
     * @access protected
     * @return bool 
     */
    protected function createProduct(int $projectID, object $project, object $postData, object $program): bool
    {
        /* If parent not empty, link products or create products. */
        $product = new stdclass();
        $product->name           = $project->hasProduct && $postData->rawdata->productName ? $postData->rawdata->productName : $project->name;
        $product->shadow         = zget($project, 'vision', 'rnd') == 'rnd' ? (int)empty($project->hasProduct) : 1;
        $product->bind           = $postData->rawdata->parent ? 0 : 1;
        $product->program        = $project->parent ? current(array_filter(explode(',', $program->path))) : 0;
        $product->acl            = $project->acl == 'open' ? 'open' : 'private';
        $product->PO             = $project->PM;
        $product->QD             = '';
        $product->RD             = '';
        $product->whitelist      = '';
        $product->createdBy      = $this->app->user->account;
        $product->createdDate    = helper::now();
        $product->status         = 'normal';
        $product->line           = 0;
        $product->desc           = '';
        $product->createdVersion = $this->config->version;
        $product->vision         = zget($project, 'vision', 'rnd');

        $this->dao->insert(TABLE_PRODUCT)->data($product)->exec();
        $productID = $this->dao->lastInsertId();
        if(!$project->hasProduct) $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
        $this->loadModel('action')->create('product', $productID, 'opened');
        $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();
        if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        $projectProduct = new stdclass();
        $projectProduct->project = $projectID;
        $projectProduct->product = $productID;
        $projectProduct->branch  = 0;
        $projectProduct->plan    = 0;

        $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->exec();

        if($project->hasProduct)
        {
            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->product   = $productID;
            $lib->name      = $this->lang->doclib->main['product'];
            $lib->type      = 'product';
            $lib->main      = '1';
            $lib->acl       = 'default';
            $lib->addedBy   = $this->app->user->account;
            $lib->addedDate = helper::now();
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取创建项目时选择的产品数量.
     * Get products count from post.
     *
     * @param  object $project
     * @param  object $rawdata
     * @param  object $program
     * @access protected
     * @return bool 
     */
    protected function getLinkedProductsCount(object $project, object $rawdata): int
    {
        $linkedProductsCount = 0;
        if($project->hasProduct && isset($rawdata->products))
        {
            foreach($rawdata->products as $product)
            {
                if(!empty($product)) $linkedProductsCount++;
            }
        }

        return $linkedProductsCount;
    }

    /**
     * 创建项目后，将项目创建者加到项目管理员分组.
     * Create project admin after create a project.
     *
     * @param  int $projectID
     * @access protected
     * @return bool 
     */
    protected function addProjectAdmin(int $projectID): bool
    {
        $groupPriv = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.`group` = t2.id')
            ->where('t1.account')->eq($this->app->user->account)
            ->andWhere('t2.role')->eq('projectAdmin')
            ->fetch();

        if(!empty($groupPriv))
        {
            $newProject = $groupPriv->project . ",$projectID";
            $this->dao->update(TABLE_USERGROUP)->set('project')->eq($newProject)->where('account')->eq($groupPriv->account)->andWhere('`group`')->eq($groupPriv->group)->exec();
        }
        else
        {
            $projectAdminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->fetch('id');

            $groupPriv = new stdclass();
            $groupPriv->account = $this->app->user->account;
            $groupPriv->group   = $projectAdminID;
            $groupPriv->project = $projectID;
            $this->dao->replace(TABLE_USERGROUP)->data($groupPriv)->exec();
        }

        return !dao::isError();
    }
}
