<?php
declare(strict_types=1);
/**
 * The tao file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

class taskTao extends taskModel
{
    /**
     * 计算任务列表中每个任务的进度，包括子任务。
     * Compute progress of task list, include its' children.
     *
     * @param  object[]  $tasks
     * @access protected
     * @return object[]
     */
    protected function batchComputeProgress(array $tasks): array
    {
        foreach($tasks as $task)
        {
            $task->progress = $this->computeTaskProgress($task);
            if(empty($task->children)) continue;

            $task->children = $this->batchComputeProgress($task->children);
        }

        return $tasks;
    }

    /**
     * 将任务的层级改为父子结构。
     * Change the hierarchy of tasks to a parent-child structure.
     *
     * @param  object[]  $tasks
     * @param  object[]  $parentTasks
     * @access protected
     * @return object[]
     */
    protected function buildTaskTree(array $tasks, array $parentTasks): array
    {
        foreach($tasks as $task)
        {
            if($task->parent <= 0) continue;
            if(isset($tasks[$task->parent]))
            {
                if(!isset($tasks[$task->parent]->children)) $tasks[$task->parent]->children = array();
                $tasks[$task->parent]->children[$task->id] = $task;
                unset($tasks[$task->id]);
            }
            else
            {
                $parent = $parentTasks[$task->parent];
                $task->parentName = $parent->name;
            }
        }
        return $tasks;
    }

    /**
     * 检查一个任务是否有子任务。
     * Check if a task has children.
     *
     * @param  int       $taskID
     * @access protected
     * @return bool
     */
    protected function checkHasChildren(int $taskID): bool
    {
        $childrenCount = $this->dao->select('count(*) as count')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetch('count');
        if(!$childrenCount) return false;
        return true;
    }

    /**
     * 获取任务的进度。
     * Compute progress of a task.
     *
     * @param  object    $task
     * @access protected
     * @return float
     */
    protected function computeTaskProgress(object $task): float
    {
        if($task->left != 0) return round($task->consumed / ($task->consumed + $task->left), 2) * 100;
        if($task->consumed == 0) return 0;
        return 100;
    }

    /**
     *  计算当前任务的状态。
     *  Compute the status of the current task.
     *
     * @param  object    $currentTask
     * @param  object    $oldTask
     * @param  object    $task
     * @param  bool      $autoStatus  true|false
     * @param  bool      $hasEfforts  true|false
     * @param  array     $members
     * @access protected
     * @return object
     */
    protected function computeTaskStatus(object $currentTask, object $oldTask, object $task, bool $autoStatus, bool $hasEfforts, array $members): object
    {
        /* If the status is not automatic, return the current task. */
        if(!$autoStatus) return $currentTask;

        /* If consumed of the current task is empty and current task has no efforts, the current task status should be wait. */
        if($currentTask->consumed == 0 && !$hasEfforts)
        {
            if(!isset($task->status)) $currentTask->status = 'wait';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        /* If neither consumed nor left of the current task is empty, the current task status should be doing. */
        if($currentTask->consumed > 0 && $currentTask->left > 0)
        {
            $currentTask->status       = 'doing';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        /* If consumed of the current task is not empty and left of the current task is empty, the current task status should be done or doing. */
        if($currentTask->consumed > 0 && $currentTask->left == 0)
        {
            $finisedUsers = $this->getFinishedUsers($oldTask->id, $members);
            /* If the number of finisher is less than the number of team members , the current task status should be doing. */
            if(count($finisedUsers) != count($members))
            {
                if(strpos('cancel,pause', $oldTask->status) === false || ($oldTask->status == 'closed' && $oldTask->reason == 'done'))
                {
                    $currentTask->status       = 'doing';
                    $currentTask->finishedBy   = null;
                    $currentTask->finishedDate = null;
                }
            }
            /* If status of old task is wait or doing or pause, the current task status should be done. */
            elseif(strpos('wait,doing,pause', $oldTask->status) !== false)
            {
                $currentTask->status       = 'done';
                $currentTask->assignedTo   = $oldTask->openedBy;
                $currentTask->assignedDate = helper::now();
                $currentTask->finishedBy   = $this->app->user->account;
                $currentTask->finishedDate = $task->finishedDate;
            }
        }

        return $currentTask;
    }

    /**
     * 拼接团队成员信息，包括账号、预计、消耗、剩余，用来创建历史记录。例如：团队成员: admin, 预计: 2, 消耗: 0, 剩余: 3。
     * Concat team info for create history.
     *
     * @param  array     $teamInfoList
     * @param  array     $userPairs
     * @access protected
     * @return string
     */
    protected function concatTeamInfo(array $teamInfoList, array $userPairs): string
    {
        $teamInfo = '';
        foreach($teamInfoList as $info) $teamInfo .= "{$this->lang->task->teamMember}: " . zget($userPairs, $info->account) . ", {$this->lang->task->estimateAB}: " . (float)$info->estimate . ", {$this->lang->task->consumedAB}: " . (float)$info->consumed . ", {$this->lang->task->leftAB}: " . (float)$info->left . "\n";
        return $teamInfo;
    }

    /**
     * 取消父任务更新子任务。
     * Update a child task when cancel its parent task.
     *
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function cancelParentTask(object $task): void
    {
        $taskID = $task->id;
        unset($task->assignedTo);
        unset($task->id);

        $oldChildrenTasks = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetchAll('id');
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('parent')->eq((int)$taskID)->exec();
        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy')->where('parent')->eq((int)$taskID)->exec();

        if(!dao::isError() and count($oldChildrenTasks) > 0)
        {
            $this->loadModel('action');
            foreach($oldChildrenTasks as $oldChildrenTask)
            {
                $actionID = $this->action->create('task', $oldChildrenTask->id, 'Canceled', $this->post->comment);
                $this->action->logHistory($actionID, common::createChanges($oldChildrenTask, $task));
            }
        }
    }

    /**
     * 更新一个任务。
     * Update a task.
     *
     * @param  object    $task
     * @param  object    $oldTask
     * @param  string    $requiredFields
     * @access protected
     * @return bool
     */
    protected function doUpdate(object $task, object $oldTask, string $requiredFields): bool
    {
        /* Task link design. */
        if(!empty($task->design))
        {
            $design = $this->dao->select('version')->from(TABLE_DESIGN)->where('id')->eq($task->design)->fetch();
            $task->designVersion = $design->version;
        }

        $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();
        if($this->isNoStoryExecution($execution)) $task->story = 0;

        /* Update children task. */
        if(isset($task->execution) && $task->execution != $oldTask->execution)
        {
            $newExecution  = $this->loadModel('execution')->getByID($task->execution);
            $task->project = $newExecution->project;
            $this->dao->update(TABLE_TASK)->set('execution')->eq($task->execution)->set('module')->eq($task->module)->set('project')->eq($task->project)->where('parent')->eq($task->id)->exec();
        }

        $this->dao->update(TABLE_TASK)->data($task, 'deleteFiles')
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $requiredFields, 'notempty')
            ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
            ->checkIF($task->estimate !== false, 'estimate', 'float')
            ->checkIF($task->left     !== false, 'left',     'float')
            ->checkIF($task->consumed !== false, 'consumed', 'float')
            ->batchCheckIF($task->status == 'wait' || $task->status == 'doing', 'finishedBy,finishedDate,canceledBy,canceledDate,closedBy,closedDate,closedReason', 'empty')
            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->status == 'done' && $task->closedReason, 'closedReason', 'equal', 'done')
            ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')
            ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
            ->checkFlow()
            ->where('id')->eq((int)$task->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * 根据任务编号查询任务数据。
     * Fetch a task by id.
     *
     * @param  int       $taskID
     * @param  string    $field
     * @access protected
     * @return object|string|false
     */
    protected function fetchByID(int $taskID, string $field = ''): object|string|false
    {
        $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(empty($field)) return $task;

        $value = isset($task->$field) ? $task->$field : false;
        return (string)$value;
    }

    /**
     * 获取执行下的任务。
     * Fetch tasks under execution by executionID(Todo).
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  object       $pager
     * @access protected
     * @return object[]
     */
    protected function fetchExecutionTasks(int $executionID, int $productID = 0, string|array $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', object $pager = null): array
    {
        if(is_string($type)) $type = strtolower($type);
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $fields  = "DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";
        if($this->config->edition == 'max') $fields .= ', t6.name as designName, t6.version as latestDesignVersion';

        $currentAccount = $this->app->user->account;

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($currentAccount)->fetchPairs('objectID', 'objectID');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on('t4.task = t1.id')
            ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
            ->beginIF($this->config->edition == 'max')->leftJoin(TABLE_DESIGN)->alias('t6')->on('t1.design= t6.id')->fi()
            ->where('t1.execution')->eq($executionID)
            ->beginIF($type == 'myinvolved')->andWhere("((t4.`account` = '{$currentAccount}') OR t1.`assignedTo` = '{$currentAccount}' OR t1.`finishedby` = '{$currentAccount}')")->fi()
            ->beginIF($productID)->andWhere('((t5.root=' . $productID . " and t5.type='story') OR t2.product=" . $productID . ')')->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$currentAccount}' or (t1.mode = 'multi' and t4.`account` = '{$currentAccount}' and t1.status != 'closed' and t4.status != 'done') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($currentAccount)
            ->orWhere('t4.status')->eq('done')
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) || strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'review')
            ->andWhere("FIND_IN_SET('{$currentAccount}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', !($productID || in_array($type, array('myinvolved', 'needconfirm', 'assignedtome'))));

        return $tasks;
    }

    /**
     * 通过任务类型查找用户的任务。
     * Fetch user tasks by type.
     *
     * @param  string      $account
     * @param  string      $type      assignedTo|finishedBy|closedBy
     * @param  string      $orderBy
     * @param  int         $projectID
     * @param  int         $limit
     * @param  object|null $pager
     * @access protected
     * @return object[]
     */
    protected function fetchUserTasksByType(string $account, string $type, string $orderBy, int $projectID, int $limit, object|null $pager): array
    {
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $orderBy = str_replace('project_', 't1.project_', $orderBy);

        return $this->dao->select("t1.*, t4.id as project, t2.id as executionID, t2.name as executionName, t4.name as projectName, t2.multiple as executionMultiple, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t2.project = t4.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t5.task = t1.id and t5.account = '{$account}'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF($type != 'closedBy' && $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'finishedBy')
            ->andWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t5.status')->eq('done')
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'assignedTo' && ($this->app->rawModule == 'my' || $this->app->rawModule == 'block'))->andWhere('t2.status', true)->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)->fi()
            ->beginIF($type != 'all' && $type != 'finishedBy' && $type != 'assignedTo')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'assignedTo')->andWhere("(t1.assignedTo = '{$account}' or (t1.mode = 'multi' and t5.`account` = '{$account}' and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->beginIF($type == 'assignedTo' && $this->app->rawModule == 'my' && $this->app->rawMethod == 'work')->andWhere('t1.status')->notin('closed,cancel')->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id');
    }

    /**
     * 根据报表条件查询任务.
     * Get task list by report.
     *
     * @param  string    $field
     * @param  string    $condition
     * @access protected
     * @return object[]
     */
    protected function getListByReportCondition(string $field, string $condition): array
    {
        return $this->dao->select("id,{$field}")->from(TABLE_TASK)
                ->where($condition)
                ->fetchAll('id');
    }

    /**
     * 获取edit方法的必填项。
     * Get required fields for edit method.
     *
     * @param  object    $task
     * @access protected
     * @return string
     */
    protected function getRequiredFields4Edit(object $task): string
    {
        $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();

        $requiredFields = ',' . $this->config->task->edit->requiredFields . ',';
        if($this->isNoStoryExecution($execution)) $requiredFields = str_replace(',story,', ',', $requiredFields);

        if($task->status != 'cancel' && strpos($requiredFields, ',estimate,') !== false)
        {
            if(strlen(trim($task->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->task->estimate);
            $requiredFields = str_replace(',estimate,', ',', $requiredFields);
        }

        if(strpos(',doing,pause,', $task->status) && empty($task->left))
        {
            dao::$errors[] = sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]);
            return false;
        }

        return trim($requiredFields, ',');
    }

    /**
     * 通过任务ID列表查询任务团队信息。
     * Get task team by id list.
     *
     * @param  array     $taskIdList
     * @access protected
     * @return object[]
     */
    protected function getTeamMembersByIdList(array $taskIdList): array
    {
        return $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task');
    }

    /**
     * 获取团队成员以及他的预计、消耗、剩余工时。
     * Get team account,estimate,consumed and left info.
     *
     * @param  array     $teamList
     * @param  array     $teamSourceList
     * @param  array     $teamEstimateList
     * @param  array     $teamConsumedList
     * @param  array     $teamLeftList
     * @access protected
     * @return object[]
     */
    protected function getTeamInfoList(array $teamList, array $teamSourceList, array $teamEstimateList, array $teamConsumedList, array $teamLeftList): array
    {
        $teamInfoList = array();
        foreach($teamList as $index => $account)
        {
            if(empty($account)) continue;

            $teamInfo = new stdclass();
            $teamInfo->account  = $account;
            $teamInfo->source   = $teamSourceList[$index];
            $teamInfo->estimate = $teamEstimateList[$index];
            $teamInfo->consumed = $teamConsumedList[$index];
            $teamInfo->left     = $teamLeftList[$index];

            $teamInfoList[$index] = $teamInfo;
        }

        return $teamInfoList;
    }

    /**
     * 拆分已消耗的任务。
     * Split the consumed task.
     *
     * @param  object    $parentTask
     * @access protected
     * @return false|int
     */
    protected function splitConsumedTask(object $parentTask): false|int
    {
        /* 复制当前任务信息。 */
        /* Copy the current task to child task, and change the parent field value. */
        $childTask = clone $parentTask;
        $childTask->parent = $parentTask->id;
        unset($childTask->id);

        $this->dao->insert(TABLE_TASK)->data($childTask)->autoCheck()->exec();
        if(dao::isError()) return false;

        /* 将父任务的日志记录更新到子任务下。 */
        /* Update the logs of the parent task under the subtask. */
        $childTaskID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_EFFORT)->set('objectID')->eq($childTaskID)
            ->where('objectID')->eq($parentTask->id)
            ->andWhere('objectType')->eq('task')
            ->exec();
        if(dao::isError()) return false;

        return (int)$childTaskID;
    }

    /**
     * 维护团队成员信息。
     * Maintain team member information.
     *
     * @param  object    $member
     * @param  string    $mode   multi|linear
     * @param  bool      $inTeam
     * @access protected
     * @return bool
     */
    protected function setTeamMember(object $member, string $mode, bool $inTeam): bool
    {
        if($mode == 'multi' && $inTeam)
        {
            $this->dao->update(TABLE_TASKTEAM)
                ->beginIF($member->estimate)->set("estimate= estimate + {$member->estimate}")->fi()
                ->beginIF($member->left)->set("`left` = `left` + {$member->left}")->fi()
                ->beginIF($member->consumed)->set("`consumed` = `consumed` + {$member->consumed}")->fi()
                ->where('task')->eq($member->task)
                ->andWhere('account')->eq($member->account)
                ->exec();
        }
        else
        {
            $this->dao->insert(TABLE_TASKTEAM)->data($member)->autoCheck()->exec();
        }
        return !dao::isError();
    }

    /**
     * 记录任务的版本。
     * Record task version.
     *
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function recordTaskVersion(object $task): bool
    {
        $taskSpec = new stdclass();
        $taskSpec->task       = $task->id;
        $taskSpec->version    = $task->version;
        $taskSpec->name       = $task->name;
        $taskSpec->estStarted = $task->estStarted;
        $taskSpec->deadline   = $task->deadline;
        $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * 通过拖动甘特图修改任务的预计开始日期和截止日期。
     * Update task estimate date and deadline through gantt.
     *
     * @param  int       $taskID
     * @param  object    $postData
     * @access protected
     * @return bool
     */
    protected function updateTaskEsDateByGantt(int $taskID, object $postData): bool
    {
        $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $isChildTask = $task->parent > 0;

        if($isChildTask) $parentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();
        $stage  = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->andWhere('project')->eq($task->project)->fetch();

        $start = $isChildTask ? $parentTask->estStarted   : $stage->begin;
        $end   = $isChildTask ? $parentTask->deadline     : $stage->end;
        $arg   = $isChildTask ? $this->lang->task->parent : $this->lang->project->stage;

        if(helper::diffDate($start, $postData->startDate) > 0) dao::$errors = sprintf($this->lang->task->overEsStartDate, $arg, $arg);
        if(helper::diffDate($end, $postData->endDate) < 0)     dao::$errors = sprintf($this->lang->task->overEsEndDate, $arg, $arg);

        /* Update estimate started and deadline of a task. */
        $this->dao->update(TABLE_TASK)
            ->set('estStarted')->eq($postData->startDate)
            ->set('deadline')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($taskID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 通过父任务更新子任务。
     * Update chilren task by parent task.
     *
     * @param  int       $parentID
     * @param  object    $data
     * @param  string    $action
     * @param  string    $comment
     * @access protected
     * @return void
     */
    protected function updateChildrenByParent(int $parentID, object $data, string $action, string $comment): void
    {
        $oldChildrenTasks = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($parentID)->fetchAll('id');
        $this->dao->update(TABLE_TASK)->data($data)->autoCheck()->where('parent')->eq($parentID)->exec();
        $this->computeWorkingHours($parentID);

        if(!dao::isError() and count($oldChildrenTasks) > 0)
        {
            $this->loadModel('action');
            foreach($oldChildrenTasks as $oldChildrenTask)
            {
                $actionID = $this->action->create('task', $oldChildrenTask->id, $action, $comment);
                $this->action->logHistory($actionID, common::createChanges($oldChildrenTask, $data));
            }
        }
    }
}
