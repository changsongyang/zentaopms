<?php
declare(strict_types=1);
/**
 * The zen file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
class executionZen extends execution
{
    /**
     * 展示Bug列表的相关变量。
     * Show the bug list related variables.
     *
     * @param  object    $execution
     * @param  object    $project
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $products
     * @param  string    $orderBy
     * @param  string    $type
     * @param  string    $build
     * @param  int       $param
     * @param  array     $bugs
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignBugVars(object $execution, object $project, int $productID, string $branch, array $products, string $orderBy, string $type, int $param, string $build, array $bugs, object $pager)
    {
        $this->loadModel('product');
        $this->loadModel('tree');

        $moduleID = $type != 'bysearch' ? $param : 0;

        /* Get module tree.*/
        $extra = array('projectID' => $execution->id, 'orderBy' => $orderBy, 'type' => $type, 'build' => $build, 'branchID' => $branch);
        if($execution->id and empty($productID) and count($products) > 1)
        {
            $moduleTree = $this->tree->getBugTreeMenu($execution->id, $productID, 0, array('treeModel', 'createBugLink'), $extra);
        }
        elseif(!empty($products))
        {
            $productID  = empty($productID) ? reset($products)->id : $productID;
            $moduleTree = $this->tree->getTreeMenu((int)$productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('branchID' => $branch, 'productID' => $productID), $branch);
        }
        else
        {
            $moduleTree = array();
        }
        $tree       = $moduleID ? $this->tree->getByID($moduleID) : '';
        $showModule = !empty($this->config->execution->bug->showModule) ? $this->config->execution->bug->showModule : '';

        /* Assign. */
        $this->view->title           = $execution->name . $this->lang->colon . $this->lang->execution->bug;
        $this->view->project         = $project;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->pager           = $pager;
        $this->view->bugs            = $bugs;
        $this->view->summary         = $this->loadModel('bug')->summary($bugs);
        $this->view->moduleTree      = $moduleTree;
        $this->view->moduleID        = $moduleID;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
        $this->view->build           = $this->loadModel('build')->getById($build);
        $this->view->buildID         = $this->view->build ? $this->view->build->id : 0;
        $this->view->productID       = $productID;
        $this->view->product         = $this->product->getByID($productID);
        $this->view->branchID        = empty($this->view->build->branch) ? $branch : $this->view->build->branch;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->param           = $param;
        $this->view->defaultProduct  = (empty($productID) and !empty($products)) ? current(array_keys($products)) : $productID;
        $this->view->builds          = $this->loadModel('build')->getBuildPairs($productID);
        $this->view->projectPairs    = $this->loadModel('project')->getPairsByProgram();
    }

    /**
     * 展示看板的相关变量。
     * Show the variables associated with the kanban.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignKanbanVars(int $executionID)
    {
        /* Get user list. */
        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        /* Get execution linked products. */
        $productID    = 0;
        $branchID     = 0;
        $products     = $this->loadModel('product')->getProducts($executionID);
        $productNames = array();
        if($products)
        {
            $productID = key($products);
            $branches  = $this->loadModel('branch')->getPairs($productID, '', $executionID);
            if($branches) $branchID = key($branches);
        }
        foreach($products as $product) $productNames[$product->id] = $product->name;

        /* Get execution linked plans. */
        $plans    = $this->execution->getPlans(array_keys($products), 'skipParent', $executionID);
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $this->view->users        = $users;
        $this->view->userList     = $userList;
        $this->view->productID    = $productID;
        $this->view->branchID     = $branchID;
        $this->view->productNames = $productNames;
        $this->view->productNum   = count($products);
        $this->view->allPlans     = $allPlans;
    }

    /**
     * 展示维护产品相关变量。
     * Show the manage products related variables.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignManageProductsVars(object $execution)
    {
        $branches            = $this->project->getBranchesByProject($execution->id);
        $linkedProductIdList = empty($branches) ? array() : array_keys($branches);
        $allProducts         = $this->loadModel('product')->getProductPairsByProject($execution->project, 'all', implode(',', $linkedProductIdList));
        $linkedProducts      = $this->product->getProducts($execution->id, 'all', '', true, $linkedProductIdList);
        $linkedBranches      = array();
        $executionStories    = $this->project->getStoriesByProject($execution->id);

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        $unmodifiableBranches = array();
        $linkedStoryIDList    = array();
        $linkedBranchIdList   = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $linkedBranches[$productID] = array();
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->name;
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranches[$productID][$branchID] = $branchID;
                $linkedBranchIdList[$branchID] = $branchID;
                if(!empty($executionStories[$productID][$branchID]))
                {
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                    $linkedStoryIDList[$productID][$branchID] = $executionStories[$productID][$branchID]->storyIDList;
                }
            }
        }

        $this->view->title                = $this->lang->execution->manageProducts . $this->lang->colon . $execution->name;
        $this->view->execution            = $execution;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->unmodifiableBranches = $unmodifiableBranches;
        $this->view->linkedBranches       = $linkedBranches;
        $this->view->linkedStoryIDList    = $linkedStoryIDList;
        $this->view->allProducts          = $allProducts;
        $this->view->branchGroups         = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal|noclosed', $linkedBranchIdList);
        $this->view->allBranches          = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal');

        $this->display();
    }

    /**
     * 展示任务看板的相关变量。
     * Show the task Kanban related variables.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignTaskKanbanVars(object $execution)
    {
        /* Get user list. */
        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        /* Get execution linked products. */
        $productID    = 0;
        $productNames = array();
        $products     = $this->loadModel('product')->getProducts($execution->id);
        if($products) $productID = key($products);
        foreach($products as $product) $productNames[$product->id] = $product->name;

        $plans    = $this->execution->getPlans(array_keys($products));
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $project = $this->project->getByID($execution->project);

        $this->view->title        = $this->lang->execution->kanban;
        $this->view->userList     = $userList;
        $this->view->realnames    = $users;
        $this->view->productID    = $productID;
        $this->view->productNames = $productNames;
        $this->view->productNum   = count($products);
        $this->view->allPlans     = $allPlans;
        $this->view->hiddenPlan   = $project->model !== 'scrum';
        $this->view->execution    = $execution;
        $this->view->canBeChanged = common::canModify('execution', $execution);
    }

    /**
     * 展示用例列表的相关变量。
     * Show the case list related variables.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  string    $branchID
     * @param  int       $moduleID
     * @param  string    $orderBy
     * @param  string    $type
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignTestcaseVars(int $executionID, int $productID, string $branchID, int $moduleID, string $orderBy, string $type, object $pager)
    {
        $this->loadModel('tree');

        /* Get cases. */
        $cases = $this->loadModel('testcase')->getExecutionCases($executionID, $productID, $branchID, $moduleID, $orderBy, $pager, $type);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $cases = $this->testcase->appendData($cases, 'case');
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        /* Get module tree.*/
        if($executionID and empty($productID))
        {
            $moduleTree = $this->tree->getCaseTreeMenu($executionID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $executionID, 'productID' => $productID, 'branchID' => $branchID), $branchID);
        }

        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $this->view->cases           = $cases;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->title           = $this->lang->execution->testcase;
        $this->view->executionID     = $executionID;
        $this->view->productID       = $productID;
        $this->view->product         = $this->product->getByID((int) $productID);
        $this->view->orderBy         = $orderBy;
        $this->view->pager           = $pager;
        $this->view->type            = $type;
        $this->view->branchID        = $branchID;
        $this->view->branchTagOption = $this->loadModel('branch')->getPairs($productID, 'withClosed');
        $this->view->recTotal        = $pager->recTotal;
        $this->view->showBranch      = $this->loadModel('branch')->showBranch($productID);
        $this->view->stories         = array( 0 => '') + $this->loadModel('story')->getPairs($productID);
        $this->view->moduleTree      = $moduleTree;
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->showBranch      = $this->loadModel('branch')->showBranch($productID);
    }

    /**
     * 展示测试单的相关变量。
     * Show the testtask related variables.
     *
     * @param  array $tasks
     * @access protected
     * @return void
     */
    protected function assignTesttaskVars(array $tasks)
    {
        /* Compute rowspan. */
        $productGroup = array();
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($tasks as $task)
        {
            $productGroup[$task->product][] = $task;
            if($task->status == 'wait')    $waitCount ++;
            if($task->status == 'doing')   $testingCount ++;
            if($task->status == 'blocked') $blockedCount ++;
            if($task->status == 'done')    $doneCount ++;
            if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
        }

        $lastProduct = '';
        foreach($tasks as $taskID => $task)
        {
            $task->rowspan = 0;
            if($lastProduct !== $task->product)
            {
                $lastProduct = $task->product;
                if(!empty($productGroup[$task->product])) $task->rowspan = count($productGroup[$task->product]);
            }
        }

        $this->view->waitCount    = $waitCount;
        $this->view->testingCount = $testingCount;
        $this->view->blockedCount = $blockedCount;
        $this->view->doneCount    = $doneCount;
        $this->view->tasks        = $tasks;
    }

    /**
     * 展示执行详情的相关变量。
     * Show the view related variables.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignViewVars(int $executionID)
    {
        $this->executeHooks($executionID);

        $userPairs = array();
        $userList  = array();
        $users     = $this->loadModel('user')->getList('all');
        foreach($users as $user)
        {
            $userList[$user->account]  = $user;
            $userPairs[$user->account] = $user->realname;
        }

        /* Get linked branches. */
        $products       = $this->loadModel('product')->getProducts($executionID);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if(isset($product->branches))
            {
                foreach($product->branches as $branchID) $linkedBranches[$branchID] = $branchID;
            }
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $this->view->users        = $userPairs;
        $this->view->userList     = $userList;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->planGroups   = $this->execution->getPlans(array_keys($products));
        $this->view->actions      = $this->loadModel('action')->getList('execution', $executionID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', 'all', $executionID);
        $this->view->teamMembers  = $this->execution->getTeamMembers($executionID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('execution', $executionID);
        $this->view->statData     = $this->execution->statRelatedData($executionID);
    }

    /**
     * 构造任务分组视图数据。
     * Build task group data.
     *
     * @param  string    $groupBy story|status|pri|assignedTo|finishedBy|closedBy|type
     * @param  array     $tasks
     * @param  array     $users
     * @access protected
     * @return array
     */
    protected function buildGroupTasks(string $groupBy = 'story', array $tasks = array(), array $users = array()): array
    {
        $groupTasks  = array();
        $groupByList = array();
        foreach($tasks as $task)
        {
            if($groupBy == 'story')
            {
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'status')
            {
                $groupTasks[$this->lang->task->statusList[$task->status]][] = $task;
            }
            elseif($groupBy == 'assignedTo')
            {
                if(isset($task->team))
                {
                    $groupTasks = $this->buildGroupMultiTask($groupBy, $task, $users, $groupTasks);
                }
                else
                {
                    $groupTasks[$task->assignedToRealName][] = $task;
                }
            }
            elseif($groupBy == 'finishedBy')
            {
                if(isset($task->team))
                {
                    $task->consumed = $task->estimate = $task->left = 0;
                    $groupTasks = $this->buildGroupMultiTask($groupBy, $task, $users, $groupTasks);
                }
                else
                {
                    $groupTasks[$users[$task->finishedBy]][] = $task;
                }
            }
            elseif($groupBy == 'closedBy')
            {
                $groupTasks[$users[$task->closedBy]][] = $task;
            }
            elseif($groupBy == 'type')
            {
                $groupTasks[$this->lang->task->typeList[$task->type]][] = $task;
            }
            else
            {
                $groupTasks[$task->$groupBy][] = $task;
            }
        }

        /* Process closed data when group by assignedTo. */
        if($groupBy == 'assignedTo' && isset($groupTasks['Closed']))
        {
            $closedTasks = $groupTasks['Closed'];
            unset($groupTasks['Closed']);
            $groupTasks['closed'] = $closedTasks;
        }

        return array($groupTasks, $groupByList);
    }

    /**
     * 构建多人任务的分组视图数据。
     * Build group data for multiple task.
     *
     * @param  string    $groupBy
     * @param  object    $task
     * @param  array     $users
     * @param  array     $groupTasks
     * @access protected
     * @return array
     */
    protected function buildGroupMultiTask(string $groupBy, object $task, array $users, array $groupTasks): array
    {
        foreach($task->team as $team)
        {
            if($team->left != 0 && $groupBy == 'finishedBy')
            {
                $task->estimate += $team->estimate;
                $task->consumed += $team->consumed;
                $task->left     += $team->left;
                continue;
            }

            $cloneTask = clone $task;
            $cloneTask->{$groupBy} = $team->account;
            $cloneTask->estimate   = $team->estimate;
            $cloneTask->consumed   = $team->consumed;
            $cloneTask->left       = $team->left;
            if($team->left == 0 || $groupBy == 'finishedBy') $cloneTask->status = 'done';

            $realname = zget($users, $team->account);
            $cloneTask->assignedToRealName = $realname;
            $groupTasks[$realname][] = $cloneTask;
        }

        if($groupBy == 'finishedBy' && !empty($task->left)) $groupTasks[$users[$task->finishedBy]][] = $task;

        return $groupTasks;
    }

    /**
     * 将导入的Bug转为任务。
     * Change imported bugs to the tasks.
     *
     * @param  object    $execution
     * @param  array     $postData
     * @access protected
     * @return array
     */
    protected function buildTasksForImportBug(object $execution, array $postData)
    {
        $this->loadModel('task');

        $tasks          = array();
        $bugs           = $this->loadModel('bug')->getByIdList(array_keys($postData));
        $showAllModule  = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $modules        = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, 0, $showAllModule ? 'allModule' : '');
        $now            = helper::now();
        $requiredFields = str_replace(',story,', ',', ',' . $this->config->task->create->requiredFields . ',');
        $requiredFields = trim($requiredFields, ',');
        foreach($postData as $bugID => $task)
        {
            $bug = zget($bugs, $bugID, '');
            if(empty($bug)) continue;

            unset($task->id);
            $task->bug          = $bug;
            $task->project      = $execution->project;
            $task->execution    = $execution->id;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->storyVersion;
            $task->module       = isset($modules[$bug->module]) ? $bug->module : 0;
            $task->fromBug      = $bugID;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->consumed     = 0;
            $task->status       = 'wait';
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;

            if($task->estimate !== '') $task->left = $task->estimate;
            if(strpos($requiredFields, 'estStarted') !== false && helper::isZeroDate($task->estStarted)) $task->estStarted = '';
            if(strpos($requiredFields, 'deadline') !== false && helper::isZeroDate($task->deadline)) $task->deadline = '';
            if(!empty($task->assignedTo)) $task->assignedDate = $now;

            /* Check task required fields. */
            foreach(explode(',', $requiredFields) as $field)
            {
                if(empty($field))         continue;
                if(!isset($task->$field)) continue;
                if(!empty($task->$field)) continue;

                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }

            if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", (string)$task->estimate) and !empty($task->estimate))
            {
                dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
                return false;
            }

            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

            $tasks[$bugID] = $task;
        }

        return $tasks;
    }

    /**
     * 构建导入Bug的搜索表单数据。
     * Build the search form data to import the Bug.
     *
     * @param  object    $execution
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $executions
     * @access protected
     * @return void
     */
    protected function buildImportBugSearchForm(object $execution, int $queryID, array $products, array $executions)
    {
        $project = $this->loadModel('project')->getByID($execution->project);

        $this->config->bug->search['actionURL'] = $this->createLink('execution', 'importBug', "executionID=$execution->id&browseType=bySearch&param=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        if(!empty($products))
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'') + $products + array('all'=>$this->lang->execution->aboveAllProduct);
        }
        else
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'');
        }
        $this->config->bug->search['params']['execution']['values'] = array(''=>'') + $executions + array('all'=>$this->lang->execution->aboveAllExecution);
        $this->config->bug->search['params']['plan']['values']      = $this->loadModel('productplan')->getPairs(array_keys($products));
        $this->config->bug->search['module'] = 'importBug';
        $this->config->bug->search['params']['confirmed']['values'] = $this->lang->bug->confirmedList;

        $this->loadModel('tree');
        $bugModules = array();
        foreach($products as $productID => $productName)
        {
            $productModules = $this->tree->getOptionMenu($productID, 'bug', 0, 'all');
            foreach($productModules as $moduleID => $moduleName)
            {
                if(empty($moduleID))
                {
                    $bugModules[$moduleID] = $moduleName;
                    continue;
                }
                $bugModules[$moduleID] = $productName . $moduleName;
            }
        }
        $this->config->bug->search['params']['module']['values'] = $bugModules;

        unset($this->config->bug->search['fields']['resolvedBy']);
        unset($this->config->bug->search['fields']['closedBy']);
        unset($this->config->bug->search['fields']['status']);
        unset($this->config->bug->search['fields']['toTask']);
        unset($this->config->bug->search['fields']['toStory']);
        unset($this->config->bug->search['fields']['severity']);
        unset($this->config->bug->search['fields']['resolution']);
        unset($this->config->bug->search['fields']['resolvedBuild']);
        unset($this->config->bug->search['fields']['resolvedDate']);
        unset($this->config->bug->search['fields']['closedDate']);
        unset($this->config->bug->search['fields']['branch']);
        if(empty($execution->multiple) && empty($execution->hasProduct)) unset($this->config->bug->search['fields']['plan']);
        if(empty($project->hasProduct))
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model !== 'scrum') unset($this->config->bug->search['fields']['plan']);
        }
        unset($this->config->bug->search['params']['resolvedBy']);
        unset($this->config->bug->search['params']['closedBy']);
        unset($this->config->bug->search['params']['status']);
        unset($this->config->bug->search['params']['toTask']);
        unset($this->config->bug->search['params']['toStory']);
        unset($this->config->bug->search['params']['severity']);
        unset($this->config->bug->search['params']['resolution']);
        unset($this->config->bug->search['params']['resolvedBuild']);
        unset($this->config->bug->search['params']['resolvedDate']);
        unset($this->config->bug->search['params']['closedDate']);
        unset($this->config->bug->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 检查累积流图的日期。
     * Check Cumulative flow diagram date.
     *
     * @param  string    $begin
     * @param  string    $end
     * @param  string    $minDate
     * @param  string    $maxDate
     * @access protected
     * @return bool
     */
    protected function checkCFDDate(string $begin, string $end, string $minDate, string $maxDate): bool
    {
        $dateError = array();
        if(empty($begin)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->begin);
        if(empty($end)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->end);
        if(empty($dateError))
        {
            if($begin < $minDate) $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->begin, $minDate);
            if($begin > $maxDate) $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->begin, $maxDate);
            if($end < $minDate)   $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->end, $minDate);
            if($end > $maxDate)   $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->end, $maxDate);
        }

        foreach($dateError as $index => $error)
        {
            dao::$errors = str_replace(array('。', '.'), array('', ''), $error);
            return false;
        }

        if($begin >= $end)
        {
            dao::$errors = $this->lang->execution->charts->cfd->errorBegin;
            return false;
        }

        if(date("Y-m-d", strtotime("-3 months", strtotime($end))) > $begin)
        {
            dao::$errors = $this->lang->execution->charts->cfd->errorDateRange;
            return false;
        }
        return true;
    }

    /**
     * 处理版本列表展示数据。
     * Process build list display data.
     *
     * @param  array     $buildList
     * @param  string    $executionID
     * @access protected
     * @return object[]
     */
    protected function processBuildListData(array $buildList, int $executionID = 0): array
    {
        $this->loadModel('build');

        $productIdList = array();
        foreach($buildList as $build) $productIdList[$build->product] = $build->product;

        /* Get branch name. */
        $showBranch   = false;
        $branchGroups = $this->loadModel('branch')->getByProducts($productIdList);
        $builds       = array();
        foreach($buildList as $build)
        {
            $build->branchName = '';
            if(isset($branchGroups[$build->product]))
            {
                $showBranch  = true;
                $branchPairs = $branchGroups[$build->product];
                foreach(explode(',', trim($build->branch, ',')) as $branchID)
                {
                    if(isset($branchPairs[$branchID])) $build->branchName .= "{$branchPairs[$branchID]},";
                }
                $build->branchName = trim($build->branchName, ',');
            }
            $build->actions = $this->build->buildActionList($build, $executionID, 'execution');

            if($build->scmPath && $build->filePath)
            {
                $build->rowspan = 2;

                $buildInfo = clone $build;
                $buildInfo->pathType = 'scmPath';
                $buildInfo->path     = $build->scmPath;
                $builds[]  = $buildInfo;

                $buildInfo = clone $build;
                $buildInfo->pathType = 'filePath';
                $buildInfo->path     = $build->filePath;
                $builds[]  = $buildInfo;
            }
            else
            {
                $build->pathType = empty($build->scmPath) ? 'filePath' : 'scmPath';
                $build->path     = empty($build->scmPath) ? $build->filePath : $build->scmPath;

                $builds[] = $build;
            }
        }

        if(!$showBranch) unset($this->config->build->dtable->fieldList['branch']);
        unset($this->config->build->dtable->fieldList['execution']);

        return $builds;
    }

    /**
     * 构建产品下拉选择数据。
     * Build product drop-down select data.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  object[]  $products
     * @access protected
     * @return array
     */
    protected function buildProductSwitcher(int $executionID, int $productID, array $products)
    {
        $productOption = array();
        $branchOption  = array();
        $programIdList = array();
        if(count($products) > 1) $productOption[0] = $this->lang->product->all;
        foreach($products as $productData) $programIdList[$productData->program] = $productData->program;
        $programPairs = $this->loadModel('program')->getPairsByList($programIdList);
        $linePairs    = $this->loadModel('product')->getLinePairs($programIdList);

        foreach($products as $productData)
        {
            $programName = isset($programPairs[$productData->program]) ? $programPairs[$productData->program] . ' / ' : '';
            $lineName    = isset($linePairs[$productData->line]) ? $linePairs[$productData->line] . ' / ' : '';
            $productOption[$productData->id] = $programName . $lineName . $productData->name;
        }

        $product = $this->product->getById((int)$productID);
        if($product and $product->type != 'normal')
        {
            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, $executionID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }
        return array($productOption, $branchOption);
    }

    /**
     * 构建执行团队成员信息。
     * Build execution team member information.
     *
     * @param  array  $currentMembers
     * @param  array  $members2Import
     * @param  array  $deptUsers
     * @param  int    $days
     * @access public
     * @return array
     */
    public function buildMembers(array $currentMembers, array $members2Import, array $deptUsers, int $days): array
    {
        $teamMembers = array();
        foreach($currentMembers as $account => $member)
        {
            $member->memberType = 'default';
            $teamMembers[$account] = $member;
        }

        foreach($members2Import as $account => $member2Import)
        {
            $member2Import->memberType = 'import';
            $member2Import->days       = $days;
            $member2Import->limited    = 'no';
            $teamMembers[$account] = $member2Import;
        }

        $roles = $this->loadModel('user')->getUserRoles(array_keys($deptUsers));
        foreach($deptUsers as $deptAccount => $userName)
        {
            if(isset($currentMembers[$deptAccount]) || isset($members2Import[$deptAccount])) continue;

            $deptMember = new stdclass();
            $deptMember->memberType = 'dept';
            $deptMember->account    = $deptAccount;
            $deptMember->role       = zget($roles, $deptAccount, '');
            $deptMember->days       = $days;
            $deptMember->hours      = $this->config->execution->defaultWorkhours;
            $deptMember->limited    = 'no';

            $teamMembers[$deptAccount] = $deptMember;
        }

        for($j = 0; $j < 5; $j ++)
        {
            $newMember = new stdclass();
            $newMember->memberType = 'add';
            $newMember->account    = '';
            $newMember->role       = '';
            $newMember->days       = $days;
            $newMember->hours      = $this->config->execution->defaultWorkhours;
            $newMember->limited    = 'no';

            $teamMembers[] = $newMember;
        }

        return $teamMembers;
    }

    /**
     * 构造待更新的团队成员数据。
     * Construct the team member data to be updated.
     *
     * @param  object    $execution
     * @access protected
     * @return array
     */
    protected function buildMembersForManageMembers(object $execution)
    {
        $members = form::batchData()->get();

        foreach($members as $rowIndex => $member)
        {
            $member->root = $execution->id;
            if(!empty($execution->days) and $member->days > $execution->days)
            {
                dao::$errors["days[$rowIndex]"] = sprintf($this->lang->execution->daysGreaterProject, $execution->days);
                return false;
            }
            if($member->hours > 24)
            {
                dao::$errors["hours[$rowIndex]"] = $this->lang->execution->errorHours;
                return false;
            }
        }
        return $members;
    }

    /**
     * 根据过滤规则，筛选任务分组数据。
     * Filter task group data based on the filter rules.
     *
     * @param  array     $groupTasks
     * @param  string    $groupBy
     * @param  string    $filter
     * @param  int       $allCount
     * @param  array     $tasks
     * @access protected
     * @return array
     */
    protected function filterGroupTasks(array $groupTasks, string $groupBy, string $filter, int $allCount, array $tasks): array
    {
        if($filter == 'all') return array($groupTasks, $allCount);

        if($groupBy == 'story' && $filter == 'linked' && isset($groupTasks[0]))
        {
            $allCount -= count($groupTasks[0]);
            unset($groupTasks[0]);
        }
        elseif($groupBy == 'pri' && $filter == 'noset')
        {
            foreach($groupTasks as $pri => $tasks)
            {
                if($pri)
                {
                    $allCount -= count($tasks);
                    unset($groupTasks[$pri]);
                }
            }
        }
        elseif($groupBy == 'assignedTo' && $filter == 'undone')
        {
            $multiTaskCount = array();
            foreach($groupTasks as $assignedTo => $tasks)
            {
                foreach($tasks as $i => $task)
                {
                    if($task->status != 'wait' && $task->status != 'doing')
                    {
                        if($task->mode == 'multi' && !isset($multiTaskCount[$task->id]))
                        {
                            $multiTaskCount[$task->id] = true;
                            $allCount -= 1;
                        }
                        elseif($task->mode != 'multi')
                        {
                            $allCount -= 1;
                        }

                        unset($groupTasks[$assignedTo][$i]);
                    }
                }
            }
        }
        elseif(($groupBy == 'finishedBy' || $groupBy == 'closedBy') && isset($tasks['']))
        {
            $allCount -= count($tasks['']);
            unset($tasks['']);
        }

        return array($groupTasks, $allCount);
    }

    /**
     * 设置最近五次执行。
     * Set the recent five executions.
     *
     * @param  int $executionID
     * @access protected
     * @return void
     */
    protected function setRecentExecutions(int $executionID)
    {
        if($this->session->multiple)
        {
            $recentExecutions = isset($this->config->execution->recentExecutions) ? explode(',', $this->config->execution->recentExecutions) : array();
            array_unshift($recentExecutions, $executionID);
            $recentExecutions = array_slice(array_unique($recentExecutions), 0, 5);
            $recentExecutions = implode(',', $recentExecutions);

            $this->loadModel('setting');
            if(empty($this->config->execution->recentExecutions) || $this->config->execution->recentExecutions != $recentExecutions) $this->setting->updateItem($this->app->user->account . 'common.execution.recentExecutions', $recentExecutions);
            if(empty($this->config->execution->lastExecution)    || $this->config->execution->lastExecution != $executionID)         $this->setting->updateItem($this->app->user->account . 'common.execution.lastExecution', $executionID);
        }
    }

    /**
     * 设置任务页面的Cookie和Session。
     * Set task page storage.
     *
     * @access protected
     * @return void
     */
    protected function setTaskPageStorage(int $executionID, string $orderBy, string $browseType, int $param = 0)
    {
        helper::setcookie('preExecutionID', (string)$executionID);
        helper::setcookie('executionTaskOrder', $orderBy);
        if($this->cookie->preExecutionID != $executionID)
        {
            helper::setcookie('moduleBrowseParam',  '0');
            helper::setcookie('productBrowseParam', '0');
        }
        if($browseType == 'bymodule')
        {
            helper::setcookie('moduleBrowseParam',  (string)$param);
            helper::setcookie('productBrowseParam', '0');
        }
        elseif($browseType == 'byproduct')
        {
            helper::setcookie('moduleBrowseParam',  '0');
            helper::setcookie('productBrowseParam', (string)$param);
        }
        else
        {
            $this->session->set('taskBrowseType', $browseType);
        }

        if($browseType == 'bymodule' && $this->session->taskBrowseType == 'bysearch') $this->session->set('taskBrowseType', 'unclosed');
    }

    /**
     * 构建执行看板的数据。
     * Build the data to execution Kanban.
     *
     * @param  array     $projectIdList
     * @param  array     $executions
     * @access protected
     * @return void
     */
    protected function buildExecutionKanbanData(array $projectIdList, array $executions)
    {
        $projectCount = 0;
        $statusCount  = array();
        $myExecutions = array();
        $kanbanGroup  = array();
        $teams        = $this->execution->getMembersByIdList(explode(',', $this->app->user->view->sprints));
        foreach($projectIdList as $projectID)
        {
            foreach(array_keys($this->lang->execution->statusList) as $status)
            {
                if(!isset($statusCount[$status])) $statusCount[$status] = 0;

                foreach($executions as $execution)
                {
                    if($execution->status == $status)
                    {
                        if(isset($teams[$execution->id][$this->app->user->account])) $myExecutions[$status][$execution->id] = $execution;
                        if($execution->project == $projectID) $kanbanGroup[$projectID][$status][$execution->id] = $execution;
                    }
                }

                $statusCount[$status] += isset($kanbanGroup[$projectID][$status]) ? count($kanbanGroup[$projectID][$status]) : 0;

                /* Max 2 closed executions. */
                if($status == 'closed')
                {
                    list($myExecutions, $kanbanGroup) = $this->processExecutionKanbanData($myExecutions, $kanbanGroup, $projectID, $status);
                }
            }

            if(empty($kanbanGroup[$projectID])) continue;
            $projectCount ++;
        }

        return array($projectCount, $statusCount, $myExecutions, $kanbanGroup);
    }

    /**
     * 获取可以导入到执行中的Bug。
     *
     * @param  int       $executionID
     * @param  array     $productIdList
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getImportBugs(int $executionID, array $productIdList, string $browseType, int $queryID, object $pager): array
    {
        $this->loadModel('bug');

        $bugs = array();
        if($browseType != "bysearch")
        {
            $bugs = $this->bug->getActiveAndPostponedBugs($productIdList, $executionID, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importBugQuery', $query->sql);
                    $this->session->set('importBugForm', $query->form);
                }
                else
                {
                    $this->session->set('importBugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->importBugQuery === false) $this->session->set('importBugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN($productIdList), $this->session->importBugQuery); // Search all execution.
            $bugs     = $this->execution->getSearchBugs($productIdList, $executionID, $bugQuery, 'id_desc', $pager);
        }

        return $bugs;
    }

    /**
     * 获取打印看板的数据。
     * Get printed kanban data.
     *
     * @param  int       $executionID
     * @param  array     $stories
     * @access protected
     * @return array
     */
    protected function getPrintKanbanData(int $executionID, array $stories): array
    {
        $kanbanTasks = $this->execution->getKanbanTasks($executionID, "id");
        $kanbanBugs  = $this->loadModel('bug')->getExecutionBugs($executionID);

        $users       = array();
        $taskAndBugs = array();
        foreach($kanbanTasks as $task)
        {
            $status  = $task->status;
            $users[] = $task->assignedTo;

            $taskAndBugs[$status]["task{$task->id}"] = $task;
        }
        foreach($kanbanBugs as $bug)
        {
            $status  = $bug->status;
            $status  = $status == 'active' ? 'wait' : ($status == 'resolved' ? ($bug->resolution == 'postponed' ? 'cancel' : 'done') : $status);
            $users[] = $bug->assignedTo;

            $taskAndBugs[$status]["bug{$bug->id}"] = $bug;
        }

        $dataList = array();
        $contents = array('story', 'wait', 'doing', 'done', 'cancel');
        foreach($contents as $content)
        {
            if($content != 'story' and !isset($taskAndBugs[$content])) continue;
            $dataList[$content] = $content == 'story' ? $stories : $taskAndBugs[$content];
        }

        return array($dataList, $users);
    }

    /**
     * 处理执行看板数据。
     * Process execution kanban data.
     *
     * @param  array     $myExecutions
     * @param  array     $kanbanGroup
     * @param  int       $projectID
     * @param  string    $status
     * @access protected
     * @return array
     */
    protected function processExecutionKanbanData(array $myExecutions, array $kanbanGroup, int $projectID, string $status): array
    {
        if(isset($myExecutions[$status]) and count($myExecutions[$status]) > 2)
        {
            foreach($myExecutions[$status] as $executionID => $execution)
            {
                unset($myExecutions[$status][$executionID]);
                $myExecutions[$status][$execution->closedDate] = $execution;
            }

            krsort($myExecutions[$status]);
            $myExecutions[$status] = array_slice($myExecutions[$status], 0, 2, true);
        }

        if(isset($kanbanGroup[$projectID][$status]) and count($kanbanGroup[$projectID][$status]) > 2)
        {
            foreach($kanbanGroup[$projectID][$status] as $executionID => $execution)
            {
                unset($kanbanGroup[$projectID][$status][$executionID]);
                $kanbanGroup[$projectID][$status][$execution->closedDate] = $execution;
            }

            krsort($kanbanGroup[$projectID][$status]);
            $kanbanGroup[$projectID][$status] = array_slice($kanbanGroup[$projectID][$status], 0, 2);
        }
        return array($myExecutions, $kanbanGroup);
    }

    /**
     * 处理打印的看板数据。
     * Process printed Kanban data.
     *
     * @param  int       $executionID
     * @param  array     $dataList
     * @access protected
     * @return array
     */
    protected function processPrintKanbanData(int $executionID, array $dataList): array
    {
        $prevKanbans = $this->execution->getPrevKanban($executionID);
        foreach($dataList as $type => $data)
        {
            if(isset($prevKanbans[$type]))
            {
                $prevData = $prevKanbans[$type];
                foreach($prevData as $id)
                {
                    if(isset($data[$id])) unset($dataList[$type][$id]);
                }
            }
        }

        return $dataList;
    }

    /**
     * Check if the product has multiple branch and check if the execution has a product with multiple branch.
     *
     * @param  int $productID
     * @param  int $executionID
     * @return bool
     */
    protected function hasMultipleBranch(int $productID, int $executionID): bool
    {
        /* Check if the product is multiple branch. */
        $multiBranchProduct = false;
        if($productID)
        {
            $product = $this->loadModel('product')->getByID($productID);
            if($product->type != 'normal') $multiBranchProduct = true;
        }
        else
        {
            $executionProductList = $this->loadModel('product')->getProducts($executionID);
            foreach($executionProductList as $executionProduct)
            {
                if($executionProduct->type != 'normal')
                {
                    $multiBranchProduct = true;
                    break;
                }
            }
        }
        return $multiBranchProduct;
    }

    /**
     * 通过模块，方法和类型生成执行的链接。
     * Generate the link of execution by module, method and type.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  mixed   $type
     * @access public
     * @return string
     */
    public function getLink(string $module, string $method, string $type = ''): string
    {
        $executionModules = array('task', 'testcase', 'build', 'bug', 'case', 'testtask', 'testreport');
        if(in_array($module, array('task', 'testcase')) && in_array($method, array('view', 'edit', 'batchedit'))) $method = $module;
        if(in_array($module, $executionModules) && in_array($method, array('view', 'edit')))                      $method = $module;
        if(in_array($module, $executionModules + array('story', 'product')))                                      $module = 'execution';

        if($module == 'story') $method = 'story';
        if($module == 'product' && $method == 'showerrornone') $method = 'task';
        if($module == 'execution' && $method == 'create') return '';

        $link = helper::createLink($module, $method, "executionID=%s");
        if($module == 'execution' && ($method == 'index' || $method == 'all'))
        {
            $link = helper::createLink($module, 'task', "executionID=%s");
        }
        elseif($module == 'bug' && $method == 'create' && $this->app->tab == 'execution')
        {
            $link = helper::createLink($module, $method, "productID=0&branch=0&executionID=%s");
        }
        elseif(in_array($module, array('bug', 'case', 'testtask', 'testreport')) && strpos(',view,edit,', ",$method,") !== false)
        {
            $link = helper::createLink('execution', $module, "executionID=%s");
        }
        elseif($module == 'repo')
        {
            $link = helper::createLink('repo', 'browse', "repoID=0&branchID=&executionID=%s");
        }
        elseif($module == 'doc')
        {
            $link = helper::createLink('doc', $method, "type=execution&objectID=%s&from=execution");
        }
        elseif(in_array($module, array('issue', 'risk', 'opportunity', 'pssp', 'auditplan', 'nc', 'meeting')))
        {
            $link = helper::createLink($module, 'browse', "executionID=%s&from=execution");
        }
        elseif($module == 'testreport' && $method == 'create')
        {
            $link = helper::createLink('execution', 'testtask', "executionID=%s");
        }

        if($type != '') $link .= "&type=$type";
        return $link;
    }
}
