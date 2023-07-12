<?php
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: model.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testcaseModel extends model
{
    /**
     * Set menu.
     *
     * @param  array  $products
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $suiteID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0, $moduleID = 0, $suiteID = 0, $orderBy = 'id_desc')
    {
        $this->loadModel('qa')->setMenu($products, $productID, $branch, $moduleID, 'case');
    }

    /**
     * Create a case.
     *
     * @param  object $case
     * @access public
     * @return bool|int
     */
    public function create($case): bool|int
    {
        if(empty($case->product)) $this->config->testcase->create->requiredFields = str_replace('story', '', $this->config->testcase->create->requiredFields);
        /* Value of story may be showmore. */
        $this->dao->insert(TABLE_CASE)->data($case, 'steps,expects,files,labels,stepType,forceNotReview,scriptFile,scriptName')
            ->autoCheck()
            ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $caseID = $this->dao->lastInsertID();

        $this->loadModel('action');
        $this->action->create('case', $caseID, 'Opened');
        if($case->status == 'wait') $this->action->create('case', $caseID, 'submitReview');

        $this->config->dangers = '';
        $this->loadModel('file')->saveUpload('testcase', $caseID, 'autoscript', 'script', 'scriptName');
        $this->loadModel('file')->saveUpload('testcase', $caseID);

        $this->loadModel('score')->create('testcase', 'create', $caseID);

        $parentStepID = 0;
        foreach($case->steps as $stepID => $stepDesc)
        {
            if(empty($stepDesc)) continue;

            $stepType      = $this->post->stepType;
            $step          = new stdClass();
            $step->type    = ($stepType[$stepID] == 'item' and $parentStepID == 0) ? 'step' : $stepType[$stepID];
            $step->parent  = ($step->type == 'item') ? $parentStepID : 0;
            $step->case    = $caseID;
            $step->version = 1;
            $step->desc    = rtrim(htmlSpecialString($stepDesc));
            $step->expect  = $step->type == 'group' ? '' : rtrim(htmlSpecialString($case->expects[$stepID]));

            $this->dao->insert(TABLE_CASESTEP)->data($step)
                ->autoCheck()
                ->exec();

            if($step->type == 'group') $parentStepID = $this->dao->lastInsertID();
            if($step->type == 'step')  $parentStepID = 0;
        }
        if(dao::isError()) return false;

        return $caseID;
    }

    /**
     * Batch create cases.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $storyID
     * @access public
     * @return array
     */
    function batchCreate($productID, $branch, $storyID)
    {
        $branch      = (int)$branch;
        $productID   = (int)$productID;
        $now         = helper::now();
        $cases       = fixer::input('post')->get();

        $result = $this->loadModel('common')->removeDuplicate('case', $cases, "product={$productID}");
        $cases  = $result['data'];
        $module = 0;
        $scene  = 0;
        $story  = 0;
        $type   = '';
        $pri    = 3;
        foreach($cases->title as $i => $title)
        {
        }

        $this->loadModel('story');
        $extendFields   = $this->getFlowExtendFields();
        $storyVersions  = array();
        $forceNotReview = $this->forceNotReview();
        $data           = array();
        foreach($cases->title as $i => $title)
        {
            if(empty($title)) continue;

            $data[$i] = new stdclass();
            $data[$i]->product      = $productID;
            if($this->app->tab == 'project') $data[$i]->project = $this->session->project;
            $data[$i]->branch       = isset($cases->branch[$i]) ? (int)$cases->branch[$i] : '0';
            $data[$i]->module       = $cases->module[$i];
            $data[$i]->scene        = (int)$cases->scene[$i];
            $data[$i]->type         = $cases->type[$i];
            $data[$i]->pri          = $cases->pri[$i];
            $data[$i]->stage        = is_array($cases->stage[$i]) ? implode(',', $cases->stage[$i]) : zget($cases->stage, $i);
            $data[$i]->story        = (int)$cases->story[$i];
            $data[$i]->color        = isset($cases->color) ? $cases->color[$i] : '';
            $data[$i]->title        = $cases->title[$i];
            $data[$i]->precondition = $cases->precondition[$i];
            $data[$i]->keywords     = $cases->keywords[$i];
            $data[$i]->openedBy     = $this->app->user->account;
            $data[$i]->openedDate   = $now;
            $data[$i]->status       = $forceNotReview || $cases->needReview[$i] == 0 ? 'normal' : 'wait';
            $data[$i]->version      = 1;

            $caseStory = $data[$i]->story;
            $data[$i]->storyVersion = isset($storyVersions[$caseStory]) ? $storyVersions[$caseStory] : 0;
            if($caseStory and !isset($storyVersions[$caseStory]))
            {
                $data[$i]->storyVersion = $this->story->getVersion($caseStory);
                $storyVersions[$caseStory] = $data[$i]->storyVersion;
            }

            foreach($extendFields as $extendField)
            {
                $data[$i]->{$extendField->field} = $this->post->{$extendField->field}[$i];
                if(is_array($data[$i]->{$extendField->field})) $data[$i]->{$extendField->field} = join(',', $data[$i]->{$extendField->field});

                $data[$i]->{$extendField->field} = htmlSpecialString($data[$i]->{$extendField->field});
            }

            foreach(explode(',', $this->config->testcase->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field && empty($data[$i]->{$field})) dao::$errors["{$field}[{$i}]"][] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
            }
        }
        if(dao::isError()) return false;

        $caseIDList = array();
        foreach($data as $i => $case)
        {
            $this->dao->insert(TABLE_CASE)->data($case)
                ->autoCheck()
                ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            $caseID       = $this->dao->lastInsertID();
            $caseIDList[] = $caseID;

            $this->executeHooks($caseID);

            $this->loadModel('score')->create('testcase', 'create', $caseID);
            $actionID = $this->loadModel('action')->create('case', $caseID, 'Opened');

            /* If the story is linked project, make the case link the project. */
            $this->syncCase2Project($case, $caseID);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');
        return $caseIDList;
    }

    /**
     * Get cases of a module.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $moduleIdList
     * @param  string      $browseType
     * @param  string      $auto   no|unit
     * @param  string      $caseType
     * @param  string      $orderBy
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getModuleCases($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $stmt = $this->dao->select('t1.*, t2.title as storyTitle, t2.deleted as storyDeleted')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id');

        if($this->app->tab == 'project') $stmt = $stmt->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id=t3.case');

        return $stmt ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t3.project')->eq($this->session->project)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto == 'auto')->andWhere('t1.auto')->eq('auto')->fi()
            ->beginIF($auto != 'unit' && $auto != 'auto')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get project cases of a module.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  int        $moduleIdList
     * @param  string     $browseType
     * @param  string     $auto   no|unit
     * @param  string     $caseType
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getModuleProjectCases($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $executions = $this->loadModel('execution')->getIdList($this->session->project);
        array_push($executions, $this->session->project);

        return $this->dao->select('distinct t1.*, t2.*, t4.title as storyTitle')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t3.story=t2.story')
            ->leftJoin(TABLE_STORY)->alias('t4')->on('t3.story=t4.id')
            ->where('t1.project')->in($executions)
            ->beginIF(!empty($productID))->andWhere('t2.product')->eq((int)$productID)->fi()
            ->beginIF(!empty($productID) and $branch !== 'all')->andWhere('t2.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF($auto == 'unit')->andWhere('t2.auto')->eq('unit')->fi()
            ->beginIF($auto == 'auto')->andWhere('t2.auto')->eq('auto')->fi()
            ->beginIF($auto != 'unit' && $auto != 'auto')->andWhere('t2.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t2.type')->eq($caseType)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager, 't1.case')
            ->fetchAll('id');
    }

    /**
     * Get project cases.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProjectCases($projectID, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF($browseType != 'all')->andWhere('t2.status')->eq($browseType)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get execution cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType   all|wait|needconfirm
     * @access public
     * @return array
     */
    public function getExecutionCases($executionID, $productID = 0, $branchID = 0, $moduleID = 0, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        if($browseType == 'needconfirm')
        {
            return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->leftJoin(TABLE_MODULE)->alias('t4')->on('t2.module=t4.id')
                ->where('t1.project')->eq((int)$executionID)
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF(!empty($moduleID))->andWhere('t4.path')->like("%,$moduleID,%")->fi()
                ->beginIF(!empty($productID) and $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t3.version > t2.storyVersion')
                ->andWhere("t3.status")->eq('active')
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $this->dao->select('distinct t1.*, t2.*')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module=t3.id')
            ->where('t1.project')->eq((int)$executionID)
            ->beginIF($browseType != 'all' and $browseType != 'byModule')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF(!empty($moduleID))->andWhere('t3.path')->like("%,$moduleID,%")->fi()
            ->beginIF(!empty($productID) and $branchID !== 'all')->andWhere('t2.branch')->eq($branchID)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get cases by suite.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $suiteID
     * @param  array       $moduleIdList
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $auto    no|unit
     * @access public
     * @return array
     */
    public function getBySuite($productID, $branch = 0, $suiteID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.*, t2.title as storyTitle, t3.version as version')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_SUITECASE)->alias('t3')->on('t1.id=t3.case')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t3.suite')->eq((int)$suiteID)
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto == 'auto')->andWhere('t1.auto')->eq('auto')->fi()
            ->beginIF($auto != 'unit' && $auto != 'auto')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get cases by type.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $suiteID
     * @param  array       $moduleIdList
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $auto    no|unit
     * @access public
     * @return array
     */
    public function getByType($productID, $branch = 0, $type = '', $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($this->app->tab == 'project')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->beginIF($type)->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get case info by ID.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return object|bool
     */
    public function getById($caseID, $version = 0)
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        if(!$case) return false;

        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and $value && !(int)substr($value, 0, 4)) $case->$key = '';

        /* Get project and execution. */
        if($this->app->tab == 'project')
        {
            $case->project = $this->session->project;
        }
        elseif($this->app->tab == 'execution')
        {
            $case->execution = $this->session->execution;
            $case->project   = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($case->execution)->fetch('project');
        }
        else
        {
            $objects = $this->dao->select('t1.*, t1.project as objectID, t2.type')->from(TABLE_PROJECTCASE)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.case')->eq($caseID)
                ->fetchAll('objectID');

            foreach($objects as $objectID => $object)
            {
                if($object->type == 'project') $case->project = $objectID;
                if(in_array($object->type, array('sprint', 'stage', 'kanban'))) $case->execution = $objectID;
            }
        }

        if($case->story)
        {
            $story = $this->dao->findById($case->story)->from(TABLE_STORY)->fields('title, status, version')->fetch();
            $case->storyTitle         = $story->title;
            $case->storyStatus        = $story->status;
            $case->latestStoryVersion = $story->version;
        }
        if($case->fromBug) $case->fromBugData = $this->dao->findById($case->fromBug)->from(TABLE_BUG)->fields('title, severity, openedDate')->fetch();

        $case->toBugs = array();
        $toBugs       = $this->dao->select('id, title, severity, openedDate')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        foreach($toBugs as $toBug) $case->toBugs[$toBug->id] = $toBug;

        if($case->linkCase or $case->fromCaseID) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->orWhere('id')->eq($case->fromCaseID)->fetchPairs();
        if($version == 0) $version = $case->version;
        $case->files = $this->loadModel('file')->getByObject('testcase', $caseID);
        $case->currentVersion = $version ? $version : $case->version;

        $case->steps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->orderBy('id')->fetchAll('id');
        foreach($case->steps as $key => $step)
        {
            $step->desc   = html_entity_decode($step->desc);
            $step->expect = html_entity_decode($step->expect);
        }

        return $case;
    }

    /**
     * Get case list.
     *
     * @param  int|array|string $caseIDList
     * @access public
     * @return array
     */
    public function getByList($caseIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->beginIF($caseIDList)->andWhere('id')->in($caseIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get test cases.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $browseType
     * @param  int        $queryID
     * @param  int        $moduleID
     * @param  string     $caseType
     * @param  string     $sort
     * @param  object     $pager
     * @param  string     $auto   no|unit
     * @access public
     * @return array
     */
    public function getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $caseType = '', $sort = 'id_desc', $pager = null, $auto = 'no')
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->caseBrowseType and $this->session->caseBrowseType != 'bysearch') ? $this->session->caseBrowseType : $browseType;
        $group      = $this->lang->navGroup->testcase;
        $auto       = $this->cookie->onlyAutoCase ? 'auto' : $auto;

        /* By module or all cases. */
        $cases = array();
        if($browseType == 'bymodule' or $browseType == 'all' or $browseType == 'wait')
        {
            if($this->app->tab == 'project')
            {
                $cases = $this->getModuleProjectCases($productID, $branch, $modules, $browseType, $auto, $caseType, $sort, $pager);
            }
            else
            {
                $cases = $this->getModuleCases($productID, $branch, $modules, $browseType, $auto, $caseType, $sort, $pager);
            }
        }
        /* Cases need confirmed. */
        elseif($browseType == 'needconfirm')
        {
            $cases = $this->dao->select('distinct t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id = t3.case')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($this->app->tab == 'project')->andWhere('t3.project')->eq($this->session->project)->fi()
                ->beginIF($branch !== 'all' and !empty($productID))->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
                ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
                ->beginIF($auto == 'auto')->andWhere('t1.auto')->eq('auto')->fi()
                ->beginIF($auto != 'unit' && $auto != 'auto')->andWhere('t1.auto')->ne('unit')->fi()
                ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
                ->orderBy($sort)
                ->page($pager, 't1.id')
                ->fetchAll();
        }
        elseif($browseType == 'bysuite')
        {
            $cases = $this->getBySuite($productID, $branch, $queryID, $modules, $sort, $pager, $auto);
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            $cases = $this->getBySearch($productID, $queryID, $sort, $pager, $branch, $auto);
        }

        return $cases;
    }

    /**
     * Get cases by search.
     *
     * @param  int         $productID
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  int|string  $branch
     * @param  string      $auto   no|unit
     * @access public
     * @return array
     */
    public function getBySearch($productID, $queryID, $orderBy, $pager = null, $branch = 0, $auto = 'no')
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
            else
            {
                $this->session->set('testcaseQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        }

        $queryProductID = $productID;
        $allProduct     = "`product` = 'all'";
        $caseQuery      = '(' . $this->session->testcaseQuery;
        if(strpos($this->session->testcaseQuery, $allProduct) !== false)
        {
            $products  = $this->app->user->view->products;
            $caseQuery = str_replace($allProduct, '1', $caseQuery);
            $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($products);
            $queryProductID = 'all';
        }

        $allBranch = "`branch` = 'all'";
        if($branch !== 'all' and strpos($caseQuery, '`branch` =') === false) $caseQuery .= " AND `branch` in('$branch')";
        if(strpos($caseQuery, $allBranch) !== false) $caseQuery = str_replace($allBranch, '1', $caseQuery);
        $caseQuery .= ')';
        $caseQuery  = str_replace('`version`', 't1.`version`', $caseQuery);

        if($this->app->tab == 'project') $caseQuery = str_replace('`product`', 't2.`product`', $caseQuery);

        /* Search criteria under compatible project. */
        $sql = $this->dao->select('*')->from(VIEW_SCENECASE)->alias('t1');
        if($this->app->tab == 'project') $sql->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case');
        $cases = $sql
            ->where($caseQuery)
            ->andWhere('t1.isCase')->eq(1)
            ->beginIF($this->app->tab == 'project' and $this->config->systemMode == 'new')->andWhere('t2.project')->eq($this->session->project)->fi()
            ->beginIF($this->app->tab == 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($this->app->tab != 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');

        return $cases;
    }

    /**
     * Get cases by assignedTo.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto  no|unit|skip
     * @access public
     * @return array
     */
    public function getByAssignedTo($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->select('t1.id as run, t1.task,t1.case,t1.version,t1.assignedTo,t1.lastRunner,t1.lastRunDate,t1.lastRunResult,t1.status as lastRunStatus,t2.id as id,t2.project,t2.pri,t2.title,t2.type,t2.openedBy,t2.color,t2.product,t2.branch,t2.module,t2.status,t2.story,t2.storyVersion,t3.name as taskName')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.status')->ne('done')
            ->beginIF(strpos($auto, 'skip') === false and $auto != 'unit')->andWhere('t2.auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('t2.auto')->eq('unit')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll(strpos($auto, 'run') !== false? 'run' : 'id');
    }

    /**
     * Get cases by openedBy
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto   no|unit|skip
     * @access public
     * @return array
     */
    public function getByOpenedBy($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        return $this->dao->findByOpenedBy($account)->from(TABLE_CASE)
            ->beginIF($auto != 'skip')->andWhere('product')->ne(0)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF($auto != 'skip' and $auto != 'unit')->andWhere('auto')->ne('unit')->fi()
            ->beginIF($auto == 'unit')->andWhere('auto')->eq('unit')->fi()
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get cases by type
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type    all|needconfirm
     * @param  string $status  all|normal|blocked|investigate
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto    no|unit|skip
     * @access public
     * @return array
     */
    public function getByStatus($productID = 0, $branch = 0, $type = 'all', $status = 'all', $moduleID = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';

        $cases = $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->beginIF($productID)->where('t1.product')->eq((int) $productID)->fi()
            ->beginIF($productID == 0)->where('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'needconfirm')->andWhere("t2.status = 'active'")->andWhere('t2.version > t1.storyVersion')->fi()
            ->beginIF($status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');
        return $this->appendData($cases);
    }

    /**
     * Get cases by product id.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getByProduct($productID)
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->fetchAll('id');
    }

    /**
     * Get case pairs by product id and branch.
     *
     * @param int        $productID
     * @param int|string $branch
     * @access public
     * @return void
     */
    public function getPairsByProduct($productID, $branch = 0)
    {
        return $this->dao->select("id, concat_ws(':', id, title) as title")->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Get cases of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCases($storyID)
    {
        return $this->dao->select('id, project, title, pri, type, status, lastRunner, lastRunDate, lastRunResult')
            ->from(TABLE_CASE)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get counts of some stories' cases.
     *
     * @param  array  $stories
     * @access public
     * @return int
     */
    public function getStoryCaseCounts($stories)
    {
        if(empty($stories)) return array();
        $caseCounts = $this->dao->select('story, COUNT(*) AS cases')
            ->from(TABLE_CASE)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($caseCounts[$storyID])) $caseCounts[$storyID] = 0;
        return $caseCounts;
    }

    /**
     * Update a case.
     *
     * @param  int    $caseID
     * @param  array  $testtasks
     * @access public
     * @return void
     */
    public function update($caseID, $testtasks = array())
    {
        $steps   = $this->post->steps;
        $expects = $this->post->expects;
        foreach($expects as $key => $value)
        {
            if(!empty($value) and empty($steps[$key]))
            {
                dao::$errors[] = sprintf($this->lang->testcase->stepsEmpty, $key);
                return false;
            }
        }

        $now     = helper::now();
        $oldCase = $this->getById($caseID);

        $result = $this->getStatus('update', $oldCase);
        if(!$result or !is_array($result)) return $result;

        list($stepChanged, $status) = $result;

        $version = $stepChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version;

        if(!empty($_POST['auto']))
        {
            $_POST['auto'] = 'auto';
            if($_POST['script']) $_POST['script'] = htmlentities($_POST['script']);
        }
        else
        {
            $_POST['auto']   = 'no';
            $_POST['script'] = '';
        }

        $case = fixer::input('post')
            ->add('id', $caseID)
            ->add('version', $version)
            ->setIF($this->post->story != false and $this->post->story != $oldCase->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF(!$this->post->linkCase, 'linkCase', '')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('story,branch', 0)
            ->setDefault('stage', '')
            ->setDefault('deleteFiles', array())
            ->join('stage', ',')
            ->join('linkCase', ',')
            ->setForce('status', $status)
            ->cleanInt('story,product,branch,module')
            ->stripTags($this->config->testcase->editor->edit['id'], $this->config->allowedTags)
            ->remove('comment,steps,expects,files,labels,linkBug,stepType,scriptFile,scriptName')
            ->get();

        $requiredFields = $this->config->testcase->edit->requiredFields;
        if($oldCase->lib != 0)
        {
            /* Remove the require field named story when the case is a lib case.*/
            $requiredFields = str_replace(',story,', ',', ",$requiredFields,");
        }
        $case = $this->loadModel('file')->processImgURL($case, $this->config->testcase->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_CASE)->data($case, 'deleteFiles')->autoCheck()->batchCheck($requiredFields, 'notempty')->checkFlow()->where('id')->eq((int)$caseID)->exec();
        if(!$this->dao->isError())
        {
            $this->updateCase2Project($oldCase, $case, $caseID);

            if($stepChanged)
            {
                $parentStepID = 0;
                $isLibCase    = ($oldCase->lib and empty($oldCase->product));
                if($isLibCase)
                {
                    $fromcaseVersion = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($caseID)->fetch('fromCaseVersion');
                    $fromcaseVersion = (int)$fromcaseVersion + 1;
                    $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($caseID)->exec();
                }

                /* Ignore steps when post has no steps. */
                if($this->post->steps)
                {
                    $data = fixer::input('post')->get();

                    foreach($data->steps as $stepID => $stepDesc)
                    {
                        if(empty($stepDesc)) continue;
                        $stepType = $this->post->stepType;
                        $step = new stdclass();
                        $step->type    = ($stepType[$stepID] == 'item' and $parentStepID == 0) ? 'step' : $stepType[$stepID];
                        $step->parent  = ($step->type == 'item') ? $parentStepID : 0;
                        $step->case    = $caseID;
                        $step->version = $version;
                        $step->desc    = rtrim(htmlSpecialString($stepDesc));
                        $step->expect  = $step->type == 'group' ? '' : rtrim(htmlSpecialString($data->expects[$stepID]));
                        $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
                        if($step->type == 'group') $parentStepID = $this->dao->lastInsertID();
                        if($step->type == 'step')  $parentStepID = 0;
                    }
                }
                else
                {
                    foreach($oldCase->steps as $step)
                    {
                        unset($step->id);
                        $step->version = $version;
                        $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
                    }
                }
            }

            /* Link bugs to case. */
            $this->post->linkBug = $this->post->linkBug ? $this->post->linkBug : array();
            $linkedBugs = array_keys($oldCase->toBugs);
            $linkBugs   = $this->post->linkBug;
            $newBugs    = array_diff($linkBugs, $linkedBugs);
            $removeBugs = array_diff($linkedBugs, $linkBugs);

            if($newBugs)
            {
                foreach($newBugs as $bugID)
                {
                    $this->dao->update(TABLE_BUG)
                        ->set('`case`')->eq($caseID)
                        ->set('caseVersion')->eq($case->version)
                        ->set('`story`')->eq($case->story)
                        ->set('storyVersion')->eq($case->storyVersion)
                        ->where('id')->eq($bugID)->exec();
                }
            }

            if($removeBugs)
            {
                foreach($removeBugs as $bugID)
                {
                    $this->dao->update(TABLE_BUG)
                        ->set('`case`')->eq(0)
                        ->set('caseVersion')->eq(0)
                        ->set('`story`')->eq(0)
                        ->set('storyVersion')->eq(0)
                        ->where('id')->eq($bugID)->exec();
                }
            }

            /* Join the steps to diff. */
            if($stepChanged and $this->post->steps)
            {
                $oldCase->steps = $this->joinStep($oldCase->steps);
                $case->steps    = $this->joinStep($this->getById($caseID, $version)->steps);
            }
            else
            {
                unset($oldCase->steps);
            }

            if($case->branch and !empty($testtasks))
            {
                $this->loadModel('action');
                foreach($testtasks as $taskID => $testtask)
                {
                    if($testtask->branch != $case->branch and $taskID)
                    {
                        $this->dao->delete()->from(TABLE_TESTRUN)
                            ->where('task')->eq($taskID)
                            ->andWhere('`case`')->eq($caseID)
                            ->exec();
                        $this->action->create('case' ,$caseID, 'unlinkedfromtesttask', '', $taskID);
                    }
                }
            }

            $this->file->processFile4Object('testcase', $oldCase, $case);
            return common::createChanges($oldCase, $case);
        }
    }

    /**
     * Review case
     *
     * @param  int    $caseID
     * @access public
     * @return bool | array
     */
    public function review($caseID)
    {
        $oldCase = $this->getById($caseID);

        $now    = helper::now();
        $status = $this->getStatus('review', $oldCase);
        $case   = fixer::input('post')
            ->add('id', $caseID)
            ->remove('result,comment')
            ->setDefault('reviewedDate', substr($now, 0, 10))
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->stripTags($this->config->testcase->editor->review['id'], $this->config->allowedTags)
            ->setForce('status', $status)
            ->join('reviewedBy', ',')
            ->get();

        $case = $this->loadModel('file')->processImgURL($case, $this->config->testcase->editor->review['id'], $this->post->uid);
        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->checkFlow()->where('id')->eq($caseID)->exec();

        if(dao::isError()) return false;

        return common::createChanges($oldCase, $case);
    }

    /**
     * Batch review cases.
     *
     * @param  array   $caseIDList
     * @access public
     * @return array
     */
    public function batchReview($caseIdList, $result)
    {
        $now     = helper::now();
        $actions = array();
        $this->loadModel('action');

        $oldCases = $this->getByList($caseIdList);
        foreach($caseIdList as $caseID)
        {
            $oldCase = $oldCases[$caseID];
            if($oldCase->status != 'wait') continue;

            $case = new stdClass();
            $case->reviewedBy     = $this->app->user->account;
            $case->reviewedDate   = substr($now, 0, 10);
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            if($result == 'pass') $case->status = 'normal';
            $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->eq($caseID)->exec();
            $actions[$caseID] = $this->action->create('case', $caseID, 'Reviewed', '', ucfirst($result));
        }

        return $actions;
    }

    /**
     * Get cases to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getCases2Link($caseID, $browseType = 'bySearch', $queryID = 0)
    {
        if($browseType == 'bySearch')
        {
            $case       = $this->getById($caseID);
            $cases2Link = $this->getBySearch($case->product, $queryID, 'id', null, $case->branch);
            foreach($cases2Link as $key => $case2Link)
            {
                if($case2Link->id == $caseID) unset($cases2Link[$key]);
                if(in_array($case2Link->id, explode(',', $case->linkCase))) unset($cases2Link[$key]);
            }
            return $cases2Link;
        }
        else
        {
            return array();
        }
    }

    /**
     * Get bugs to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getBugs2Link($caseID, $browseType = 'bySearch', $queryID = 0)
    {
        $this->loadModel('bug');
        if($browseType == 'bySearch')
        {
            $case      = $this->getById($caseID);
            $bugs2Link = $this->bug->getBySearch($case->product, $case->branch, $queryID, 'id');
            foreach($bugs2Link as $key => $bug2Link)
            {
                if($bug2Link->case != 0) unset($bugs2Link[$key]);
            }
            return $bugs2Link;
        }
        else
        {
            return array();
        }
    }

    /**
     * Batch update testcases.
     *
     * @param  array $testtasks
     * @access public
     * @return array
     */
    public function batchUpdate($testtasks = array())
    {
        $cases      = array();
        $allChanges = array();
        $now        = helper::now();
        $data       = fixer::input('post')->get();
        $caseIdList = array_keys($this->post->caseIdList);

        /* Process data if the value is 'ditto'. */
        foreach($caseIdList as $caseID)
        {
            if($data->pri[$caseID]     == 'ditto') $data->pri[$caseID]     = isset($prev['pri'])    ? $prev['pri']    : 3;
            if($data->module[$caseID] == 'ditto')  $data->module[$caseID] = isset($prev['module']) ? $prev['module'] : 0;
            if($data->scene[$caseID]   == 'ditto') $data->scene[$caseID]   = isset($prev['scene'])  ? $prev['scene']  : 0;
            if($data->type[$caseID]   == 'ditto') $data->type[$caseID]   = isset($prev['type'])   ? $prev['type']   : '';
            if($data->story[$caseID]   == '')      $data->story[$caseID]   = 0;
            if($data->story[$caseID]   == 'ditto') $data->story[$caseID]   = isset($prev['story']) ? $prev['story'] : 0;
            if(isset($data->branch[$caseID]) and $data->branch[$caseID] == 'ditto') $data->branch[$caseID] = isset($prev['branch']) ? $prev['branch'] : 0;

            $prev['pri']    = $data->pri[$caseID];
            $prev['type']   = $data->type[$caseID];
            $prev['story']  = $data->story[$caseID];
            $prev['module'] = $data->module[$caseID];
            $prev['scene']  = $data->scene[$caseID];
            if(isset($data->branch)) $prev['branch'] = $data->branch[$caseID];
        }

        /* Initialize cases from the post data.*/
        $extendFields = $this->getFlowExtendFields();
        foreach($caseIdList as $caseID)
        {
            $case = new stdclass();
            $case->id             = $caseID;
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->pri            = $data->pri[$caseID];
            $case->module         = $data->module[$caseID];
            $case->scene          = $data->scene[$caseID];
            $case->status         = $data->status[$caseID];
            $case->story          = $data->story[$caseID];
            //$case->color          = $data->color[$caseID];
            $case->title          = $data->title[$caseID];
            $case->precondition   = $data->precondition[$caseID];
            $case->keywords       = $data->keywords[$caseID];
            $case->type           = $data->type[$caseID];
            $case->stage          = empty($data->stage[$caseID]) ? '' : implode(',', $data->stage[$caseID]);
            if(isset($data->branch[$caseID])) $case->branch = $data->branch[$caseID];

            foreach($extendFields as $extendField)
            {
                $case->{$extendField->field} = $this->post->{$extendField->field}[$caseID];
                if(is_array($case->{$extendField->field})) $case->{$extendField->field} = join(',', $case->{$extendField->field});

                $case->{$extendField->field} = htmlSpecialString($case->{$extendField->field});
            }

            $cases[$caseID] = $case;
            unset($case);
        }

        if(empty($case->product)) $this->config->testcase->edit->requiredFields = str_replace('story', '', $this->config->testcase->edit->requiredFields);

        /* Update cases. */
        $this->loadModel('action');
        foreach($cases as $caseID => $case)
        {
            $oldCase = $this->getByID($caseID);

            $caseChanged = false;
            if($oldCase->title != $case->title)               $caseChanged = true;
            if($oldCase->precondition != $case->precondition) $caseChanged = true;

            $this->dao->update(TABLE_CASE)->data($case)
                ->autoCheck()
                ->batchCheck($this->config->testcase->edit->requiredFields, 'notempty')
                ->checkFlow()
                ->where('id')->eq($caseID)
                ->exec();

            if(!dao::isError())
            {
                $case->product = $oldCase->product;
                $this->updateCase2Project($oldCase, $case, $caseID);

                $this->executeHooks($caseID);

                unset($oldCase->steps);
                $allChanges[$caseID] = common::createChanges($oldCase, $case);

                if(!empty($case->branch) and isset($testtasks[$caseID]))
                {
                    foreach($testtasks[$caseID] as $taskID => $testtask)
                    {
                        if($testtask->branch != $case->branch and $taskID)
                        {
                            $this->dao->delete()->from(TABLE_TESTRUN)
                                ->where('task')->eq($taskID)
                                ->andWhere('`case`')->eq($caseID)
                                ->exec();
                            $this->action->create('case' ,$caseID, 'unlinkedfromtesttask', '', $taskID);
                        }
                    }
                }

                $isLibCase = ($oldCase->lib and empty($oldCase->product));
                if($isLibCase and $caseChanged)
                {
                    $fromcaseVersion = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($caseID)->fetch('fromCaseVersion');
                    $fromcaseVersion = (int)$fromcaseVersion + 1;
                    $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($caseID)->exec();
                }
            }
            else
            {
                return helper::end(js::error('case#' . $caseID . dao::getError(true)));
            }
        }

        return $allChanges;
    }

    /**
     * Batch change branch.
     *
     * @param  array  $caseIDList
     * @param  int    $branchID
     * @access public
     * @return array
     */
    public function batchChangeBranch($caseIDList, $branchID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldCases   = $this->getByList($caseIDList);
        foreach($caseIDList as $caseID)
        {
            $oldCase = $oldCases[$caseID];
            if($branchID == $oldCase->branch) continue;

            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->branch         = $branchID;

            $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->eq((int)$caseID)->exec();
            if(!dao::isError()) $allChanges[$caseID] = common::createChanges($oldCase, $case);
        }

        return $allChanges;
    }

    /**
     * Batch change the module of case.
     *
     * @param  array  $caseIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($caseIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldCases   = $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF($caseIDList)->andWhere('id')->in($caseIDList)->fi()
            ->fetchAll('id');

        /* Split selected nodes into 2 arrays. */
        /* Top level nodes. */
        $finalOldCases1 = array();
        /* Non-top level nodes. */
        $finalOldCases2 = array();
        if(!empty($oldCases))
        {
            foreach($oldCases as $k => $v)
            {
                $flag = 0;
                foreach($oldCases as $k2 => $v2)
                {
                    $resFlag = strpos($v->path,$v2->path);

                    /* v2 is ancestor. */
                    if (($resFlag || $resFlag === 0) && $v->grade > $v2->grade ) $flag = 1;
                }
                if($flag == 0)
                {
                    /* None selected node is its ancestor, assign to this array. */
                    $finalOldCases1[$k] = $v;
                }
                else
                {
                    /* As one selected node is its ancestor, assign to this array. */
                    $finalOldCases2[$k] = $v;
                }
            }
        }

        /* Process all top level nodes of selected nodes. */
        foreach($caseIDList as $caseID)
        {
            if(!isset($finalOldCases1[$caseID])) continue;

            $oldCase = $finalOldCases1[$caseID];

            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->module         = $moduleID;

            if($oldCase->isCase == 2)
            {
                $case->parent = 0;
                $case->path   = ",$caseID,";
                $case->grade  = 1;

                $this->dao->update(TABLE_SCENE)->data($case)
                    ->autoCheck()
                    ->where('id')->eq((int)$caseID - CHANGEVALUE)
                    ->exec();
                if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);
            }
            else
            {
                $case->scene = 0;
                $this->dao->update(TABLE_CASE)->data($case)
                    ->autoCheck()
                    ->where('id')->eq((int)$caseID)
                    ->exec();
                if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
            }
        }

        /* Process non-top level nodes of selected nodes. */
        foreach($caseIDList as $caseID)
        {
            if(!isset($finalOldCases2[$caseID])) continue;

            $oldCase = $finalOldCases2[$caseID];

            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->module         = $moduleID;

            if($oldCase->isCase == 2)
            {
                $resultScene = $this->dao->findById((int)$oldCase->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
                $case->path  = $resultScene->path . "$caseID,";
                $case->grade = $resultScene->grade + 1;

                $this->dao->update(TABLE_SCENE)->data($case)
                    ->autoCheck()
                    ->where('id')->eq((int)$caseID - CHANGEVALUE)
                    ->exec();
                if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);
            }
            else
            {
                $this->dao->update(TABLE_CASE)->data($case)
                    ->autoCheck()
                    ->where('id')->eq((int)$caseID)
                    ->exec();
                if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
            }
        }

        return $allChanges;
    }

    /**
     * Batch case type change.
     *
     * @param  array   $caseIDList
     * @param  string  $result
     * @access public
     * @return array
     */
    public function batchCaseTypeChange($caseIdList, $result)
    {
        $now     = helper::now();
        $actions = array();
        $this->loadModel('action');

        $oldCases = $this->getByList($caseIdList);
        foreach($caseIdList as $caseID)
        {
            $case = new stdClass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->type           = $result;

            $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->eq($caseID)->exec();
            $actionID = $this->action->create('case', $caseID, 'Edited', '', ucfirst($result));
            $changes  = common::createChanges($oldCases[$caseID], $case);
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * Join steps to a string, thus can diff them.
     *
     * @param  array   $steps
     * @access public
     * @return string
     */
    public function joinStep($steps)
    {
        $return = '';
        if(empty($steps)) return $return;
        foreach($steps as $step) $return .= $step->desc . ' EXPECT:' . $step->expect . "\n";
        return $return;
    }

    /**
     * Create case steps from a bug's step.
     *
     * @param  string    $steps
     * @access public
     * @return array
     */
    function createStepsFromBug($steps)
    {
        $steps        = strip_tags($steps);
        $caseSteps    = array((object)array('desc' => $steps, 'expect' => ''));   // the default steps before parse.
        $lblStep      = strip_tags($this->lang->bug->tplStep);
        $lblResult    = strip_tags($this->lang->bug->tplResult);
        $lblExpect    = strip_tags($this->lang->bug->tplExpect);
        $lblStepPos   = strpos($steps, $lblStep);
        $lblResultPos = strpos($steps, $lblResult);
        $lblExpectPos = strpos($steps, $lblExpect);

        if($lblStepPos === false or $lblResultPos === false or $lblExpectPos === false) return $caseSteps;

        $caseSteps  = substr($steps, $lblStepPos + strlen($lblStep), $lblResultPos - strlen($lblStep) - $lblStepPos);
        $caseExpect = substr($steps, $lblExpectPos + strlen($lblExpect));
        $caseSteps  = trim($caseSteps);
        $caseExpect = trim($caseExpect);

        $caseSteps = explode("\n", trim($caseSteps));
        $stepCount = count($caseSteps);
        foreach($caseSteps as $key => $caseStep)
        {
            $expect = $key + 1 == $stepCount ? $caseExpect : '';
            $caseSteps[$key] = (object)array('desc' => trim($caseStep), 'expect' => $expect, 'type' => 'item');
        }
        return $caseSteps;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object $case
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $case, string $action): bool
    {
        $canBeChanged = common::canBeChanged('case', $case);
        if(!$canBeChanged) return false;

        global $config;

        $action = strtolower($action);

        if($action == 'confirmchange')      return $case->caseStatus != 'wait' && $case->version < $case->caseVersion;
        if($action == 'confirmstorychange') return $case->needconfirm || $case->browseType == 'needconfirm';
        if($action == 'createbug')          return !empty($case->caseFails) && $case->caseFails > 0;
        if($action == 'review')             return ($config->testcase->needReview || !empty($config->testcase->forceReview)) && (isset($case->caseStatus) ? $case->caseStatus == 'wait' : $case->status == 'wait');
        if($action == 'showscript')         return $case->auto == 'auto';

        return true;
    }

    /**
     * Create from import
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function createFromImport($productID, $branch = 0)
    {
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('file');
        $now    = helper::now();
        $branch = (int)$branch;
        $data   = fixer::input('post')->get();

        $steps = $data->desc;
        foreach($data->expect as $key => $expects)
        {
            foreach($expects as $exportID => $value)
            {
                if(!empty($value) and (!isset($steps[$key][$exportID]) or empty($steps[$key][$exportID])))
                {
                    dao::$errors = sprintf($this->lang->testcase->whichLine, $key) . sprintf($this->lang->testcase->stepsEmpty, $exportID);
                    return false;
                }
            }
        }

        if(!empty($_POST['id']))
        {
            $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.id')->in(($_POST['id']))
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.version=t2.version')
                ->orderBy('t2.id')
                ->fetchGroup('case');
            $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
        }

        $cases             = array();
        $line              = 1;
        $fieldNames        = array();
        $storyVersionPairs = $this->story->getVersions($data->story);

        if($this->config->edition != 'open')
        {
            $extendFields = $this->getFlowExtendFields();
            $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

            foreach($extendFields as $extendField)
            {
                if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
                {
                    $this->config->testcase->create->requiredFields .= ',' . $extendField->field;
                }
            }
        }

        foreach($data->product as $key => $product)
        {
            $caseData = new stdclass();

            $caseData->product      = $product;
            $caseData->branch       = isset($data->branch[$key]) ? $data->branch[$key] : $branch;
            $caseData->module       = $data->module[$key];
            $caseData->story        = (int)$data->story[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->keywords     = $data->keywords[$key];
            $caseData->frequency    = 1;
            $caseData->precondition = $data->precondition[$key];

            if($this->config->edition != 'open')
            {
                foreach($extendFields as $extendField)
                {
                    $dataArray = $_POST[$extendField->field];
                    $caseData->{$extendField->field} = $dataArray[$key];
                    if(is_array($caseData->{$extendField->field})) $caseData->{$extendField->field} = join(',', $caseData->{$extendField->field});

                    $caseData->{$extendField->field} = htmlSpecialString($caseData->{$extendField->field});
                }
            }

            if(isset($this->config->testcase->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(!isset($caseData->$requiredField)) continue;
                    if(empty($caseData->$requiredField) and !isset($fieldNames[$requiredField])) $fieldNames[$requiredField] = $this->lang->testcase->$requiredField;
                }
            }

            $cases[$key] = $caseData;
            $line++;
        }
        if(!empty($fieldNames))
        {
            $tipContent = '';
            foreach($requiredFields as $field)
            {
                if(isset($fieldNames[$field])) $tipContent .= ",{$fieldNames[$field]}";
            }
            dao::$errors = sprintf($this->lang->testcase->noRequireTip, trim($tipContent, ','));
        }

        if(dao::isError()) return false;

        $forceNotReview = $this->forceNotReview();
        foreach($cases as $key => $caseData)
        {
            $caseID = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $caseID = $data->id[$key];
                if(!isset($oldCases[$caseID])) $caseID = 0;
            }

            if($caseID)
            {
                $stepChanged = false;
                $oldStep     = isset($oldSteps[$caseID]) ? $oldSteps[$caseID] : array();
                $oldCase     = $oldCases[$caseID];

                /* Ignore updating cases for different products. */
                if($oldCase->product != $caseData->product) continue;

                /* Remove the empty setps in post. */
                $steps = array();
                if(isset($_POST['desc'][$key]))
                {
                    foreach($this->post->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $step = new stdclass();
                        $step->type   = $data->stepType[$key][$id];
                        $step->desc   = htmlSpecialString($desc);
                        $step->expect = htmlSpecialString(trim($this->post->expect[$key][$id]));

                        $steps[] = $step;
                    }
                }

                /* If step count changed, case changed. */
                if((!$oldStep != !$steps) or (count($oldStep) != count($steps)))
                {
                    $stepChanged = true;
                }
                else
                {
                    /* Compare every step. */
                    foreach($oldStep as $id => $step)
                    {
                        if(trim($step->desc) != trim($steps[$id]->desc) or trim($step->expect) != $steps[$id]->expect)
                        {
                            $stepChanged = true;
                            break;
                        }
                    }
                }

                $version           = $stepChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version;
                $caseData->version = $version;
                $changes           = common::createChanges($oldCase, $caseData);
                if($caseData->story != $oldCase->story) $caseData->storyVersion = zget($storyVersionPairs, $caseData->story, 1);
                if(!$changes and !$stepChanged) continue;

                if($changes or $stepChanged)
                {
                    $caseData->lastEditedBy   = $this->app->user->account;
                    $caseData->lastEditedDate = $now;
                    if($stepChanged and !$forceNotReview) $caseData->status = 'wait';
                    $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->checkFlow()->exec();

                    if(!dao::isError())
                    {
                        if($stepChanged)
                        {
                            $parentStepID = 0;
                            foreach($steps as $id => $step)
                            {
                                $step = (array)$step;
                                if(empty($step['desc'])) continue;
                                $stepData = new stdclass();
                                $stepData->type    = ($step['type'] == 'item' and $parentStepID == 0) ? 'step' : $step['type'];
                                $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                                $stepData->case    = $caseID;
                                $stepData->version = $version;
                                $stepData->desc    = $step['desc'];
                                $stepData->expect  = $step['expect'];
                                $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                                if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                                if($stepData->type == 'step')  $parentStepID = 0;
                            }
                        }
                        $oldCase->steps  = $this->joinStep($oldStep);
                        $caseData->steps = $this->joinStep($steps);
                        $changes  = common::createChanges($oldCase, $caseData);

                        $this->updateCase2Project($oldCase, $caseData, $caseID);

                        $actionID = $this->action->create('case', $caseID, 'Edited');
                        $this->action->logHistory($actionID, $changes);
                    }
                }
            }
            else
            {
                if($this->app->tab == 'project') $caseData->project = $this->session->project;
                $caseData->version    = 1;
                $caseData->openedBy   = $this->app->user->account;
                $caseData->openedDate = $now;
                $caseData->branch     = isset($data->branch[$key]) ? $data->branch[$key] : $branch;
                if($caseData->story) $caseData->storyVersion = zget($storyVersionPairs, $caseData->story, 1);
                $caseData->status = !$forceNotReview ? 'wait' : 'normal';
                $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $caseID       = $this->dao->lastInsertID();
                    $parentStepID = 0;
                    if($data->desc)
                    {
                        foreach($data->desc[$key] as $id => $desc)
                        {
                            $desc = trim($desc);
                            if(empty($desc)) continue;
                            $stepData = new stdclass();
                            $stepData->type    = ($data->stepType[$key][$id] == 'item' and $parentStepID == 0) ? 'step' : $data->stepType[$key][$id];
                            $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                            $stepData->case    = $caseID;
                            $stepData->version = 1;
                            $stepData->desc    = htmlSpecialString($desc);
                            $stepData->expect  = htmlSpecialString($data->expect[$key][$id]);
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                            if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                            if($stepData->type == 'step')  $parentStepID = 0;
                        }
                    }

                    $this->action->create('case', $caseID, 'Opened');

                    $this->syncCase2Project($caseData, $caseID);
                }
            }
        }

        if($this->post->isEndPage)
        {
            unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }
    }

    /**
     * Get fields for import.
     *
     * @access public
     * @return array
     */
    public function getImportFields($productID = 0)
    {
        $product    = $this->loadModel('product')->getById($productID);
        if($product->type != 'normal') $this->lang->testcase->branch = $this->lang->product->branchName[$product->type];

        $caseLang   = $this->lang->testcase;
        $caseConfig = $this->config->testcase;
        $fields     = explode(',', $caseConfig->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($caseLang->$fieldName) ? $caseLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * Import case from Lib.
     *
     * @param  int    $productID
     * @param  int    $libID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function importFromLib($productID, $libID, $branch)
    {
        $data = fixer::input('post')->get();

        $prevModule = 0;
        $prevBranch = 0;
        foreach($data->module as $i => $module)
        {
            if($module != 'ditto') $prevModule = $module;
            if($module == 'ditto') $data->module[$i] = $prevModule;
        }

        $caseModules = array();
        $this->loadModel('testsuite');
        if(isset($data->branch))
        {
            foreach($data->branch as $i => $branch)
            {
                if($branch != 'ditto') $prevBranch = $branch;
                if($branch == 'ditto') $data->branch[$i] = $prevBranch;
                if(!isset($caseModules[$data->branch[$i]])) $caseModules[$data->branch[$i]] = $this->testsuite->getCanImportModules($productID, $libID,  $data->branch[$i]);
            }
        }
        else
        {
            $caseModules[$branch] = $this->loadModel('testsuite')->getCanImportModules($productID, $libID,  $branch);
        }

        $libCases = $this->dao->select('*')->from(TABLE_CASE)->where('deleted')->eq(0)->andWhere('id')->in($data->caseIdList)->fetchAll('id');
        $libSteps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->in($data->caseIdList)->orderBy('id')->fetchGroup('case');
        $libFiles = $this->dao->select('*')->from(TABLE_FILE)->where('objectID')->in($data->caseIdList)->andWhere('objectType')->eq('testcase')->fetchGroup('objectID', 'id');
        $imported = '';
        foreach($libCases as $libCaseID => $case)
        {
            $case->fromCaseID      = $case->id;
            $case->fromCaseVersion = (int)$case->version;
            $case->product         = $productID;
            if(isset($data->module[$case->id])) $case->module = $data->module[$case->id];
            if(isset($data->branch[$case->id])) $case->branch = $data->branch[$case->id];
            unset($case->id);

            $branch = isset($case->branch) ? $case->branch : 0;
            if(empty($caseModules[$branch][$case->fromCaseID][$case->module]))
            {
                $imported .= "$case->fromCaseID,";
                continue;
            }

            $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->exec();

            if(!dao::isError())
            {
                $caseID = $this->dao->lastInsertID();
                if(isset($libSteps[$libCaseID]))
                {
                    foreach($libSteps[$libCaseID] as $step)
                    {
                        $step->case = $caseID;
                        unset($step->id);
                        $this->dao->insert(TABLE_CASESTEP)->data($step)->exec();
                    }
                }

                /* If under the project module, the cases is imported need linking to the project. */
                if($this->app->tab == 'project')
                {
                    $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($this->session->project)->orderBy('order_desc')->limit(1)->fetch('order');

                    $this->dao->insert(TABLE_PROJECTCASE)
                        ->set('project')->eq($this->session->project)
                        ->set('product')->eq($case->product)
                        ->set('case')->eq($caseID)
                        ->set('version')->eq($case->version)
                        ->set('order')->eq(++ $lastOrder)
                        ->exec();
                }

                /* Fix bug #1518. */
                $oldFiles = zget($libFiles, $libCaseID, array());
                foreach($oldFiles as $fileID => $file)
                {
                    $file->objectID  = $caseID;
                    $file->addedBy   = $this->app->user->account;
                    $file->addedDate = helper::now();
                    $file->downloads = 0;
                    unset($file->id);
                    $this->dao->insert(TABLE_FILE)->data($file)->exec();
                }
                $this->loadModel('action')->create('case', $caseID, 'fromlib', '', $case->lib);
            }
        }

        if(!empty($imported)) return $imported;

        return !dao::isError();
    }

    /**
     * Import cases to lib.
     *
     * @param  int    $caseIdList
     * @access public
     * @return void
     */
    public function importToLib($caseIdList = 0)
    {
        if(empty($caseIdList)) $caseIdList = $this->post->caseIdList;
        $caseIdList = explode(',' , $caseIdList);
        $libID      = $this->post->lib;

        if(empty($libID)) return dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->testcase->caselib);

        $this->loadModel('action');
        $cases          = $this->dao->select('*')->from(TABLE_CASE)->where('deleted')->eq(0)->andWhere('id')->in($caseIdList)->fetchAll('id');
        $caseSteps      = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->in($caseIdList)->orderBy('id')->fetchGroup('case');
        $caseFiles      = $this->dao->select('*')->from(TABLE_FILE)->where('objectID')->in($caseIdList)->andWhere('objectType')->eq('testcase')->fetchGroup('objectID', 'id');
        $libCases       = $this->loadModel('caselib')->getLibCases($libID, 'all');
        $libFiles       = $this->dao->select('*')->from(TABLE_FILE)->where('objectID')->in(array_keys($libCases))->andWhere('objectType')->eq('testcase')->fetchGroup('objectID', 'id');
        $libCases       = $this->dao->select('*')->from(TABLE_CASE)->where('lib')->eq($libID)->andWhere('product')->eq(0)->andWhere('deleted')->eq('0')->fetchGroup('fromCaseID', 'id');
        $maxOrder       = $this->dao->select('max(`order`) as maxOrder')->from(TABLE_CASE)->where('deleted')->eq(0)->fetch('maxOrder');
        $maxModuleOrder = $this->dao->select('max(`order`) as maxOrder')->from(TABLE_MODULE)->where('deleted')->eq(0)->fetch('maxOrder');
        foreach($cases as $caseID => $case)
        {
            $libCase = new stdclass();
            $libCase->lib             = $libID;
            $libCase->title           = $case->title;
            $libCase->precondition    = $case->precondition;
            $libCase->keywords        = $case->keywords;
            $libCase->pri             = $case->pri;
            $libCase->type            = $case->type;
            $libCase->stage           = $case->stage;
            $libCase->status          = $case->status;
            $libCase->fromCaseID      = $case->id;
            $libCase->fromCaseVersion = $case->version;
            $libCase->order           = ++ $maxOrder;
            $libCase->module          = empty($case->module) ? 0 : $this->importCaseRelatedModules($libID, $case->module, $maxModuleOrder);

            if(empty($libCases[$caseID]))
            {
                $libCase->openedBy   = $this->app->user->account;
                $libCase->openedDate = helper::now();
                $this->dao->insert(TABLE_CASE)->data($libCase)->autoCheck()->exec();
                if(!dao::isError()) $libCaseID = $this->dao->lastInsertID();
                $this->action->create('case', $libCaseID, 'tolib', '', $caseID);
            }
            else
            {
                $libCaseList = array_keys($libCases[$caseID]);
                $libCaseID   = $libCaseList[0];

                $libCase->lastEditedBy   = $this->app->user->account;
                $libCase->lastEditedDate = helper::now();
                $libCase->version        = (int)$libCases[$caseID][$libCaseID]->version + 1;
                $this->dao->update(TABLE_CASE)->data($libCase)->autoCheck()->where('id')->eq((int)$libCaseID)->exec();

                $this->action->create('case', $libCaseID, 'updatetolib', '', $caseID);

                $this->dao->delete()->from(TABLE_CASESTEP)->where('`case`')->eq($libCaseID)->exec();

                $removeFiles = zget($libFiles, $libCaseID, array());
                $this->dao->delete()->from(TABLE_FILE)->where('`objectID`')->eq($libCaseID)->andWhere('objectType')->eq('testcase')->exec();
                foreach($removeFiles as $fileID => $file)
                {
                    $filePath = pathinfo($file->pathname, PATHINFO_BASENAME);
                    $datePath = substr($file->pathname, 0, 6);
                    $filePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $filePath;
                    unlink($filePath);
                }
            }

            if(!dao::isError())
            {
                if(isset($caseSteps[$caseID]))
                {
                    foreach($caseSteps[$caseID] as $index => $step)
                    {
                        if($step->version != $case->version) continue;
                        $oldStepID     = $step->id;
                        $step->case    = $libCaseID;
                        $step->version = zget($libCase, 'version', '0');
                        unset($step->id);

                        $this->dao->insert(TABLE_CASESTEP)->data($step)->exec();
                    }
                }

                $oldFiles = zget($caseFiles, $caseID, array());
                foreach($oldFiles as $fileID => $file)
                {
                    $originName = pathinfo($file->pathname, PATHINFO_FILENAME);
                    $datePath   = substr($file->pathname, 0, 6);
                    $originFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $originName;

                    $copyName = $originName . 'copy' . $libCaseID;
                    $copyFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" .  $copyName;
                    copy($originFile, $copyFile);

                    $newFileName    = $file->pathname;
                    $newFileName    = str_replace('.', "copy$libCaseID.", $newFileName);
                    $file->pathname = $newFileName;

                    $file->objectID  = $libCaseID;
                    $file->addedBy   = $this->app->user->account;
                    $file->addedDate = helper::now();
                    $file->downloads = 0;
                    unset($file->id);
                    $this->dao->insert(TABLE_FILE)->data($file)->exec();
                }
            }
        }
    }

    /**
     * Import case related modules.
     *
     * @param  int    $libID
     * @param  int    $oldModuleID
     * @param  int    $maxOrder
     * @access public
     * @return void
     */
    public function importCaseRelatedModules($libID, $oldModuleID = 0, $maxOrder = 0)
    {
        $moduleID = $this->checkModuleImported($libID, $oldModuleID);
        if($moduleID) return $moduleID;

        $oldModule = $this->dao->select('name, parent, grade, `order`, short')->from(TABLE_MODULE)->where('id')->eq($oldModuleID)->fetch();

        $oldModule->root   = $libID;
        $oldModule->from   = $oldModuleID;
        $oldModule->type   = 'caselib';
        if(!empty($maxOrder)) $oldModule->order = $maxOrder + $oldModule->order;
        $this->dao->insert(TABLE_MODULE)->data($oldModule)->autoCheck()->exec();

        if(!dao::isError())
        {
            $newModuleID = $this->dao->lastInsertID();

            if($oldModule->parent)
            {
                $parentModuleID = $this->importCaseRelatedModules($libID, $oldModule->parent, !empty($maxOrder) ? $maxOrder : 0);
                $parentModule   = $this->dao->select('id, path')->from(TABLE_MODULE)->where('id')->eq($parentModuleID)->fetch();
                $parent         = $parentModule->id;
                $path           = $parentModule->path . "$newModuleID,";
            }
            else
            {
                $path   = ",$newModuleID,";
                $parent = 0;
            }

            $this->dao->update(TABLE_MODULE)->set('parent')->eq($parent)->set('path')->eq($path)->where('id')->eq($newModuleID)->exec();

            return $newModuleID;
        }
    }

    /**
     * Adjust module is can import.
     *
     * @param  int    $libID
     * @param  int    $oldModule
     * @access public
     * @return int
     */
    public function checkModuleImported($libID, $oldModule = 0)
    {
        $module = $this->dao->select('id')->from(TABLE_MODULE)
            ->where('root')->eq($libID)
            ->andWhere('`from`')->eq($oldModule)
            ->andWhere('type')->eq('caselib')
            ->andWhere('deleted')->eq(0)
            ->fetch();

        if(!$module) return '';

        return $module->id;
    }

    /**
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($productID, $products, $queryID, $actionURL, $projectID = 0, $moduleID = 0, $branch = 0)
    {
        $productList = array();
        if($this->app->tab == 'project' and empty($productID))
        {
            $productList = $products;
        }
        else
        {
            $productList = array('all' => $this->lang->all);
            if(isset($products[$productID])) $productList = array($productID => $products[$productID]) + $productList;
        }
        $this->config->testcase->search['params']['product']['values'] = array('') + $productList;

        $module = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $branch);
        $scene  = $this->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, $branch, 0, true);
        if(!$productID)
        {
            $module = array();
            foreach($products as $id => $name) $module += $this->loadModel('tree')->getOptionMenu($id, 'case', 0);
        }
        $this->config->testcase->search['params']['module']['values'] = $module;
        $this->config->testcase->search['params']['scene']['values']  = $scene;

        $this->config->testcase->search['params']['lib']['values'] = $this->loadModel('caselib')->getLibraries();

        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->app->loadLang('branch');
            $product = $this->loadModel('product')->getByID($productID);
            $this->config->testcase->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->testcase->search['params']['branch']['values'] = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, '', $projectID) + array('all' => $this->lang->branch->all);
        }
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;
        $this->config->testcase->search['module']    = $this->app->rawModule;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Print cell data
     *
     * @param  object $col
     * @param  object $case
     * @param  array  $users
     * @param  array  $branches
     * @access public
     * @return void
     */
    public function printCell($col, $case, $users, $branches, $modulePairs = array(), $browseType = '', $mode = 'datatable', $isCase = 1)
    {
        /* Check the product is closed. */
        $canBeChanged = common::canBeChanged('case', $case);

        $canBatchRun                = common::hasPriv('testtask', 'batchRun');
        $canBatchEdit               = common::hasPriv('testcase', 'batchEdit');
        $canBatchDelete             = common::hasPriv('testcase', 'batchDelete');
        $canBatchCaseTypeChange     = common::hasPriv('testcase', 'batchCaseTypeChange');
        $canBatchConfirmStoryChange = common::hasPriv('testcase', 'batchConfirmStoryChange');
        $canBatchChangeModule       = common::hasPriv('testcase', 'batchChangeModule');

        $canBatchAction             = ($canBatchRun or $canBatchEdit or $canBatchDelete or $canBatchCaseTypeChange or $canBatchConfirmStoryChange or $canBatchChangeModule);

        $canView    = common::hasPriv('testcase', 'view');
        $caseLink   = helper::createLink('testcase', 'view', "caseID=$case->id&version=$case->version");
        $account    = $this->app->user->account;
        $fromCaseID = $case->fromCaseID;
        $id = $col->id;
        if($col->show)
        {
            $class = $id == 'title' ? 'c-name' : 'c-' . $id;
            $title = '';
            if($id == 'title')
            {
                $class .= ' text-left';
                $title  = "title='{$case->title}'";
            }
            if($id == 'status')
            {
                $class .= $case->status;
                $title  = "title='" . $this->processStatus('testcase', $case) . "'";
            }
            if(strpos(',bugs,results,stepNumber,', ",$id,") !== false) $title = "title='{$case->$id}'";
            if($id == 'actions') $class .= ' c-actions';
            if($id == 'lastRunResult') $class .= " {$case->lastRunResult}";
            if(strpos(',stage,precondition,keywords,story,', ",{$id},") !== false) $class .= ' text-ellipsis';

            if($id == 'title')
            {
                if($isCase == 2)
                {
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon '></span>";
                }
                else
                {
                    echo "<td class='c-name table-nest-title text-left sort-handler has-prefix has-suffix' {$title}><span class='table-nest-icon icon icon-test'></span>";
                }
            }
            else
            {
                echo "<td class='{$class}' {$title}>";
            }
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('testcase', $case, $id);
            switch($id)
            {
            case 'id':
                $showid = "";
                if($isCase == 2)
                {
                    $showid = substr($case->id,1);
                    $showid = preg_replace('/^0+/', '', $showid);
                }
                else
                {
                    $showid = $case->id;
                }
                if($canBatchAction)
                {
                    $disabled = $canBeChanged ? '' : 'disabled';
                    if($isCase == 1){
                        echo html::checkbox('caseIDList', array($case->id => ''), '', $disabled) . html::a(helper::createLink('testcase', 'view', "caseID=$case->id"), sprintf('%03d', $showid), '', "data-app='{$this->app->tab}'");
                    }
                    else
                    {
                        echo html::checkbox('caseIDList', array($case->id => ''), '', $disabled) .  sprintf('%03d', $showid);
                    }
                }
                else
                {
                    printf('%03d', $showid);
                }
                break;
            case 'pri':
                if($isCase != 2)
                {
                    echo "<span class='label-pri label-pri-" . $case->pri . "' title='" . zget($this->lang->testcase->priList, $case->pri, $case->pri) . "'>";
                    echo zget($this->lang->testcase->priList, $case->pri, $case->pri);
                    echo "</span>";
                }
                break;
            case 'title':
                if($isCase == 1)
                {
                    $autoIcon = $case->auto == 'auto' ? " <i class='icon icon-draft-edit'></i>" : '';
                    if($modulePairs and $case->module and isset($modulePairs[$case->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$case->module]}</span> ";
                    echo $canView ? html::a($caseLink, $case->title, null, "style='color: $case->color' data-app='{$this->app->tab}'")
                        : "<span style='color: $case->color'>$case->title</span>";

                    $fromLink = ($fromCaseID and $canView) ? helper::createLink('testcase', 'view', "caseID=$fromCaseID") : '#';
                    $title    = $fromCaseID ? "[<i class='icon icon-share' title='{$this->lang->testcase->fromCaselib}'></i>#$fromCaseID]$autoIcon" : $autoIcon;
                    if($case->auto == 'auto') echo html::a($fromLink, $title, '', "data-app='{$this->app->tab}'");
                }
                else
                {
                    echo $case->title;
                }
                break;
            case 'branch':
                echo $branches[$case->branch];
                break;
            case 'type':
                echo $this->lang->testcase->typeList[$case->type];
                break;
            case 'stage':
                $stages = '';
                foreach(explode(',', trim($case->stage, ',')) as $stage) $stages .= $this->lang->testcase->stageList[$stage] . ',';
                $stages = trim($stages, ',');
                echo "<span title='$stages'>$stages</span>";
                break;
            case 'status':
                if($case->needconfirm)
                {
                    print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>");
                }
                elseif(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
                {
                    print("<span class='status-story status-changed' title='{$this->lang->testcase->changed}'>{$this->lang->testcase->changed}</span>");
                }
                else
                {
                    print("<span class='status-testcase status-{$case->status}'>" . $this->processStatus('testcase', $case) . "</span>");
                }
                break;
            case 'story':
                static $stories = array();
                if(empty($stories)) $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('product')->eq($case->product)->fetchPairs('id', 'title');
                if($case->story and isset($stories[$case->story])) echo html::a(helper::createLink('story', 'view', "storyID=$case->story"), $stories[$case->story]);
                break;
            case 'precondition':
                echo $case->precondition;
                break;
            case 'keywords':
                echo $case->keywords;
                break;
            case 'version':
                if($isCase == 1) echo $case->version;
                break;
            case 'openedBy':
                echo zget($users, $case->openedBy);
                break;
            case 'openedDate':
                echo substr($case->openedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo zget($users, $case->reviewedBy);
                break;
            case 'reviewedDate':
                 echo helper::isZeroDate($case->reviewedDate) ? '' : substr($case->reviewedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $case->lastEditedBy);
                break;
            case 'lastEditedDate':
                 echo helper::isZeroDate($case->lastEditedDate) ? '' : substr($case->lastEditedDate, 5, 11);
                break;
            case 'lastRunner':
                echo zget($users, $case->lastRunner);
                break;
            case 'lastRunDate':
                if(!helper::isZeroDate($case->lastRunDate)) echo substr($case->lastRunDate, 5, 11);
                break;
            case 'lastRunResult':
                if ($isCase == 1) {
                    $class = 'result-' . $case->lastRunResult;
                    $lastRunResultText = $case->lastRunResult ? zget($this->lang->testcase->resultList, $case->lastRunResult, $case->lastRunResult) : $this->lang->testcase->unexecuted;
                    echo "<span class='$class'>" . $lastRunResultText . "</span>";
                }
                break;
            case 'bugs':
                if ($isCase == 1) echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a(helper::createLink('testcase', 'bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;
                break;
            case 'results':
                if ($isCase == 1) echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a(helper::createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;
                break;
            case 'stepNumber':
                if ($isCase == 1) echo $case->stepNumber;
                break;
            case 'actions':
                if ($isCase == 1)
                {
                    $case->browseType = $browseType;
                    echo $this->buildOperateMenu($case, 'browse');
                    break;
                }
                else
                {
                    echo $this->buildOperateBrowseSceneMenu($case);
                }
            }
            echo '</td>';
        }
    }

    /**
     * Append bugs and results.
     *
     * @param  int    $cases
     * @param  string $type
     * @param  array  $caseIdlist
     * @access public
     * @return void
     */
    public function appendData($cases, $type = 'case', $caseIdlist = array())
    {
        if(empty($caseIdlist)) $caseIdList = array_keys($cases);
        if($type == 'case')
        {
            $caseBugs   = $this->dao->select('count(*) as count, `case`')->from(TABLE_BUG)->where('`case`')->in($caseIdList)->andWhere('deleted')->eq(0)->groupBy('`case`')->fetchPairs('case', 'count');
            $results    = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)->where('`case`')->in($caseIdList)->groupBy('`case`')->fetchPairs('case', 'count');

            $caseFails = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)
                ->where('caseResult')->eq('fail')
                ->andwhere('`case`')->in($caseIdList)
                ->groupBy('`case`')
                ->fetchPairs('case','count');

            $steps = $this->dao->select('count(distinct t1.id) as count, t1.`case`')->from(TABLE_CASESTEP)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.`case`=t2.`id`')
                ->where('t1.`case`')->in($caseIdList)
                ->andWhere('t1.type')->ne('group')
                ->andWhere('t1.version=t2.version')
                ->groupBy('t1.`case`')
                ->fetchPairs('case', 'count');
        }
        else
        {
            $caseBugs = $this->dao->select('count(*) as count, `case`')->from(TABLE_BUG)->where('`result`')->in($caseIdList)->andWhere('deleted')->eq(0)->groupBy('`case`')->fetchPairs('case', 'count');
            $results  = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)->where('`run`')->in($caseIdList)->groupBy('`case`')->fetchPairs('case', 'count');

            $caseFails = $this->dao->select('count(*) as count, `case`')->from(TABLE_TESTRESULT)
                ->where('caseResult')->eq('fail')
                ->andwhere('`run`')->in($caseIdList)
                ->groupBy('`case`')
                ->fetchPairs('case','count');

            $steps = $this->dao->select('count(distinct t1.id) as count, t1.`case`')->from(TABLE_CASESTEP)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.`case`=t2.`case`')
                ->where('t2.`id`')->in($caseIdList)
                ->andWhere('t1.type')->ne('group')
                ->andWhere('t1.version=t2.version')
                ->groupBy('t1.`case`')
                ->fetchPairs('case', 'count');
        }

        foreach($cases as $key => $case)
        {
            $caseID = $type == 'case' ? $case->id : $case->case;
            $case->bugs       = isset($caseBugs[$caseID])  ? $caseBugs[$caseID]   : 0;
            $case->results    = isset($results[$caseID])   ? $results[$caseID]    : 0;
            $case->caseFails  = isset($caseFails[$caseID]) ? $caseFails[$caseID]  : 0;
            $case->stepNumber = isset($steps[$caseID])     ? $steps[$caseID]      : 0;
        }

        return $cases;
    }

    /**
     * Check whether force not review.
     *
     * @access public
     * @return bool
     */
    public function forceNotReview()
    {
        if(empty($this->config->testcase->needReview))
        {
            if(!isset($this->config->testcase->forceReview)) return true;
            if(strpos(",{$this->config->testcase->forceReview},", ",{$this->app->user->account},") === false) return true;
        }
        if($this->config->testcase->needReview && isset($this->config->testcase->forceNotReview) && strpos(",{$this->config->testcase->forceNotReview},", ",{$this->app->user->account},") !== false) return true;

        return false;
    }

    /**
     * Summary cases
     *
     * @param  array    $cases
     * @access public
     * @return string
     */
    public function summary($cases)
    {
        $executed = 0;
        foreach($cases as $case)
        {
            if($case->lastRunResult != '') $executed ++;
        }

        return sprintf($this->lang->testcase->summary, count($cases), $executed);
    }

    /**
     * Sync case to project.
     *
     * @param  object $case
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function syncCase2Project($case, $caseID)
    {
        $projects = array();
        if(!empty($case->story))
        {
            $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($case->story)->fetchPairs();
        }
        elseif($this->app->tab == 'project' and empty($case->story))
        {
            $projects = array($this->session->project);
        }
        elseif($this->app->tab == 'execution' and empty($case->story))
        {
            $projects = array($this->session->execution);
        }
        if(empty($projects)) return;

        $this->loadModel('action');
        $objectInfo = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');

        foreach($projects as $projectID)
        {
            $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $case->product;
            $data->case    = $caseID;
            $data->version = 1;
            $data->order   = ++ $lastOrder;
            $this->dao->insert(TABLE_PROJECTCASE)->data($data)->exec();

            $object     = $objectInfo[$projectID];
            $objectType = $object->type;
            if($objectType == 'project') $this->action->create('case', $caseID, 'linked2project', '', $projectID);
            if(in_array($objectType, array('sprint', 'stage')) and $object->multiple) $this->action->create('case', $caseID, 'linked2execution', '', $projectID);
        }
    }

    /**
     * Deal with the relationship between the case and project when edit the case.
     *
     * @param  object  $oldCase
     * @param  object  $case
     * @param  int     $caseID
     * @access public
     * @return void
     */
    public function updateCase2Project($oldCase, $case, $caseID)
    {
        $productChanged = ($oldCase->product != $case->product);
        $storyChanged   = ($oldCase->story   != $case->story);

        if($productChanged)
        {
            $this->dao->update(TABLE_PROJECTCASE)
                ->set('product')->eq($case->product)
                ->set('version')->eq($case->version)
                ->where('`case`')->eq($oldCase->id)
                ->exec();
        }

        /* The related story is changed. */
        if($storyChanged)
        {
            /* If the new related story isn't linked the project, unlink the case. */
            $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($oldCase->story)->fetchAll('project');

            $projectIdList = array_keys($projects);
            $this->dao->delete()->from(TABLE_PROJECTCASE)
                ->where('project')->in()
                ->andWhere('`case`')->eq($oldCase->id)
                ->exec();

            /* If the new related story is not null, make the case link the project which link the new related story. */
            if(!empty($case->story))
            {
                $projects = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('story')->eq($case->story)->fetchAll('project');
                if($projects)
                {
                    $projects = array_keys($projects);
                    foreach($projects as $projectID)
                    {
                        $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
                        $data = new stdclass();
                        $data->project = $projectID;
                        $data->product = $case->product;
                        $data->case    = $caseID;
                        $data->version = $oldCase->version;
                        $data->order   = ++ $lastOrder;
                        $this->dao->replace(TABLE_PROJECTCASE)->data($data)->exec();
                    }
                }
            }
        }
    }

    /**
     * Get status for different method.
     *
     * @param  string $methodName
     * @param  object $case
     * @access public
     * @return mixed    string | bool | array
     */
    public function getStatus($methodName, $case = null)
    {
        if($methodName == 'create')
        {
            if($this->forceNotReview() || $this->post->forceNotReview) return 'normal';
            return 'wait';
        }

        if($methodName == 'review')
        {
            $status = zget($case, 'status', '');

            if($this->post->result == 'pass') return 'normal';

            return $status;
        }

        if($methodName == 'update')
        {
            if(!empty($_POST['lastEditedDate']) and $case->lastEditedDate != $this->post->lastEditedDate)
            {
                dao::$errors[] = $this->lang->error->editedByOther;
                return false;
            }

            $status      = $this->post->status ? $this->post->status : $case->status;
            $stepChanged = false;
            $steps       = array();

            /* ---------------- Judge steps changed or not.-------------------- */

            /* Remove the empty setps in post. */
            if($this->post->steps)
            {
                $data = fixer::input('post')->get();
                foreach($data->steps as $key => $desc)
                {
                    $desc     = trim($desc);
                    $stepType = isset($data->stepType[$key]) ? $data->stepType[$key] : 'step';
                    if(!empty($desc)) $steps[] = array('desc' => $desc, 'type' => $stepType, 'expect' => trim($data->expects[$key]));
                }

                /* If step count changed, case changed. */
                if(count($case->steps) != count($steps))
                {
                    $stepChanged = true;
                }
                else
                {
                    /* Compare every step. */
                    $i = 0;
                    foreach($case->steps as $key => $oldStep)
                    {
                        if(trim($oldStep->desc) != trim($steps[$i]['desc']) or trim($oldStep->expect) != $steps[$i]['expect'] or trim($oldStep->type) != $steps[$i]['type'])
                        {
                            $stepChanged = true;
                            break;
                        }
                        $i++;
                    }
                }
            }

            if(!$this->forceNotReview() and $stepChanged) $status = 'wait';

            if(!empty($_POST['title']) and $case->title != $this->post->title)                      $stepChanged = true;
            if(!empty($_POST['precondition']) and $case->precondition != $this->post->precondition) $stepChanged = true;

            return array($stepChanged, $status);
        }

        return '';
    }

    /**
     * 构造详情页或列表页需要的操作菜单。
     * Build action menu.
     *
     * @param  object $case
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $case = null, string $type = 'view'): array
    {
        $caseID         = $case ? $case->id             : '{id}';
        $runID          = $case ? $case->runID          : '0';
        $version        = $case ? $case->version        : '';
        $currentVersion = $case ? $case->currentVersion : '{version}';
        $product        = $case ? $case->product        : '{product}';
        $branch         = $case ? $case->branch         : '{branch}';
        $module         = $case ? $case->module         : '{module}';

        $params     = "caseID={$caseID}";
        $editParams = $params;
        if($this->app->tab == 'project')   $editParams = "{$params}&comment=false&projectID={$this->session->project}";
        if($this->app->tab == 'execution') $editParams = "{$params}&comment=false&executionID={$this->session->execution}";
        $createBugParams = "product={$product}&branch={$branch}&extra=caseID={$caseID},version={$version},runID=";
        $copyParams      = "productID={$product}&branch={$branch}&moduleID={$module}&from=testcase&param={$caseID}";

        $actions = array();
        $actions['results']    = array('icon' => 'list-alt',  'text' => '结果',           'url' => helper::createLink('testcase', 'results',    "{$params}&version={$version}"),        'data-toggle' => 'modal');
        $actions['runcase']    = array('icon' => 'play',      'text' => '执行',           'url' => helper::createLink('testcase', 'runCase',    "{$params}&version={$currentVersion}"), 'data-toggle' => 'modal');
        $actions['edit']       = array('icon' => 'edit',      'text' => '编辑',           'url' => helper::createLink('testcase', 'edit',       $editParams));
        $actions['review']     = array('icon' => 'review',    'text' => '审批',           'url' => helper::createLink('testcase', 'review',     $params), 'data-toggle' => 'modal');
        $actions['createBug']  = array('icon' => 'bug',       'text' => '转Bug',          'url' => helper::createLink('testcase', 'createBug',  $createBugParams), 'data-toggle' => 'modal');
        $actions['create']     = array('icon' => 'copy',      'text' => '复制',           'url' => helper::createLink('testcase', 'create',     $copyParams));
        $actions['showscript'] = array('icon' => 'file-code', 'text' => '查看自动化脚本', 'url' => helper::createLink('testcase', 'showScript', $params), 'data-toggle' => 'modal');

        foreach($actions as $action => $actionData)
        {
            $actionsConfig = $this->config->testcase->actions->{$type};
            if(strpos(",{$actionsConfig},", ",{$action},") === false)
            {
                unset($actions[$action]);
                continue;
            }
            $actions[$action]['hint'] = $actions[$action]['text'];
            if($type == 'browse') unset($actions[$action]['text']);
        }

        if($type == 'browse') $this->config->testcase->dtable->fieldList['actions']['actionsMap'] = $actions;
        return $actions;
    }

    /**
     * processDatas
     *
     * @param  array  $datas
     * @access public
     * @return void
     */
    public function processDatas($datas)
    {
        if(isset($datas->datas)) $datas = $datas->datas;
        $columnKey  = array();
        $caseData   = array();
        $stepData   = array();
        $stepVars   = 0;

        foreach($datas as $row => $cellValue)
        {
            foreach($cellValue as $field => $value)
            {
                if($field != 'stepDesc' and $field != 'stepExpect') continue;
                if($field == 'stepDesc' or $field == 'stepExpect')
                {
                    $steps = $value;
                    if(strpos($value, "\n"))
                    {
                        $steps = explode("\n", $value);
                    }
                    elseif(strpos($value, "\r"))
                    {
                        $steps = explode("\r", $value);
                    }
                    if(is_string($steps)) $steps = explode("\n", $steps);

                    $stepKey  = str_replace('step', '', strtolower($field));

                    $caseStep = array();
                    foreach($steps as $step)
                    {
                        $trimedStep = trim($step);
                        if(empty($trimedStep)) continue;
                        if(preg_match('/^(([0-9]+)\.[0-9]+)([.、]{1})/U', $step, $out) and ($field == 'stepDesc' or ($field == 'stepExpect' and isset($stepData[$row]['desc'][$out[1]]))))
                        {
                            $num     = $out[1];
                            $parent  = $out[2];
                            $sign    = $out[3];
                            $signbit = $sign == '.' ? 1 : 3;
                            $step    = trim(substr($step, strlen($num) + $signbit));
                            if(!empty($step)) $caseStep[$num]['content'] = $step;
                            $caseStep[$num]['type']    = 'item';
                            $caseStep[$parent]['type'] = 'group';
                        }
                        elseif(preg_match('/^([0-9]+)([.、]{1})/U', $step, $out) and ($field == 'stepDesc' or ($field == 'stepExpect' and isset($stepData[$row]['desc'][$out[1]]))))
                        {
                            $num     = $out[1];
                            $sign    = $out[2];
                            $signbit = $sign == '.' ? 1 : 3;
                            $step    = trim(substr($step, strpos($step, $sign) + $signbit));
                            if(!empty($step)) $caseStep[$num]['content'] = $step;
                            $caseStep[$num]['type'] = 'step';
                        }
                        elseif(isset($num))
                        {
                            if(!isset($caseStep[$num]['content'])) $caseStep[$num]['content'] = '';
                            if(!isset($caseStep[$num]['type']))    $caseStep[$num]['type']    = 'step';
                            $caseStep[$num]['content'] .= "\n" . $step;
                        }
                        else
                        {
                            if($field == 'stepDesc')
                            {
                                $num = 1;
                                $caseStep[$num]['content'] = $step;
                                $caseStep[$num]['type']    = 'step';
                            }
                            if($field == 'stepExpect' and isset($stepData[$row]['desc']))
                            {
                                end($stepData[$row]['desc']);
                                $num = key($stepData[$row]['desc']); $caseStep[$num]['content'] = $step;
                            }
                        }
                    }

                    unset($num);
                    unset($sign);
                    $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);
                    $stepData[$row][$stepKey] = $caseStep;
                }

            }
        }
        return $stepData;
    }

    /**
     * Get modules for datatable.
     *
     * @param int $productID
     * @access public
     * @return void
     */
    public function getDatatableModules($productID)
    {
        $branches = $this->loadModel('branch')->getPairs($productID);
        $modules  = $this->loadModel('tree')->getOptionMenu($productID, 'case', '');
        if(count($branches) <= 1) return $modules;

        foreach($branches as $branchID => $branchName) $modules += $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
        return $modules;
    }

    /**
     * Batch change scene.
     *
     * @param  array $caseIDList
     * @param  int   $sceneId
     * @access public
     * @return array
     */
    public function batchChangeScene($caseIDList, $sceneId)
    {
        $now        = helper::now();
        $allChanges = array();

        $ioldCases   = $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF($caseIDList)->andWhere('id')->in($caseIDList)->fi()
            ->fetchAll('id');

        /* If the target node is root. */
        if(!$sceneId)
        {
            $oldCases = $ioldCases;

            /* Split selected nodes into 2 arrays. */
            $finalOldCases1 = array(); /* Parent Scenes. */
            $finalOldCases2 = array(); /* Cases and leaf Scenes. */
            if(!empty($oldCases))
            {
                foreach($oldCases as $k => $v)
                {
                    $flag = 0;
                    foreach($oldCases as $k2 => $v2)
                    {
                        $resFlag = strpos($v->path,$v2->path);

                        /* $v2 is the ancestor of $v. */
                        if(($resFlag || $resFlag === 0) && $v->grade > $v2->grade ) $flag = 1;
                    }

                    if($flag == 0)
                    {
                        /* None selected node is its ancestor, then assign to this array. */
                        $finalOldCases1[$k] = $v;
                    }
                    else
                    {
                        /* As one selected node is ancestor, then assign to this array. */
                        $finalOldCases2[$k] = $v;
                    }
                }
            }

            /* Process all top level nodes (all parent scenes) of selected nodes. */
            foreach($caseIDList as $caseID)
            {
                if(!isset($finalOldCases1[$caseID])) continue;

                $oldCase = $finalOldCases1[$caseID];
                if($sceneId == $oldCase->parent) continue;
                if($sceneId == $oldCase->id) continue;

                $case = new stdclass();
                $case->lastEditedBy   = $this->app->user->account;
                $case->lastEditedDate = $now;

                if($oldCase->isCase == 2)
                {
                    $case->parent = 0;
                    $case->path   = ",$caseID,";
                    $case->grade  = 1;

                    $this->dao->update(TABLE_SCENE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID - CHANGEVALUE)
                        ->exec();
                    if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);
                }
                else
                {
                    $case->scene = 0;

                    $this->dao->update(TABLE_CASE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID)
                        ->exec();
                    if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
                }
            }

            /* Process all non-top level nodes of selected nodes. */
            foreach($caseIDList as $caseID)
            {
                if (!isset($finalOldCases2[$caseID])) continue;

                $oldCase = $finalOldCases2[$caseID];
                if($sceneId == $oldCase->parent) continue;
                if($sceneId == $oldCase->id) continue;

                $case = new stdclass();
                $case->lastEditedBy   = $this->app->user->account;
                $case->lastEditedDate = $now;

                if($oldCase->isCase == 2)
                {
                    $case->parent = $sceneId;
                    $resultScene  = $this->dao->findById((int)$oldCase->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
                    $case->path   = $resultScene->path . "$caseID,";
                    $case->grade  = $resultScene->grade + 1;

                    $this->dao->update(TABLE_SCENE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID - CHANGEVALUE)
                        ->exec();
                    if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);
                }
                else
                {
                    $case->scene = $sceneId;
                    $this->dao->update(TABLE_CASE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID)
                        ->exec();
                    if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
                }
            }
        }
        else
        {
            $sceneRow = $this->dao->findById((int)$sceneId)->from(VIEW_SCENECASE)->fetch();

            /* Remove ancestors of target node. Won't change ancestors' scene to be target scene. */
            $oldCases = array();
            if(!empty($ioldCases))
            {
                foreach($ioldCases as $k => $v)
                {
                    $resFlag = strpos($sceneRow->path,$v->path);
                    if(($resFlag || $resFlag === 0) && $sceneRow->grade > $v->grade )
                    {
                        /* The ancestors of the target node. */
                    }
                    else
                    {
                        $oldCases[$k] = $v;
                    }
                }
            }

            /* These selected nodes removed ancestors are split into 2 arrays. */
            $finalOldCases1 = array();
            $finalOldCases2 = array();
            if (!empty($oldCases))
            {
                foreach($oldCases as $k => $v)
                {
                    $flag = 0;
                    foreach($oldCases as $k2 => $v2)
                    {
                        $resFlag = strpos($v->path,$v2->path);

                        /* v2 is ancestor. */
                        if (($resFlag || $resFlag === 0) && $v->grade > $v2->grade ) $flag = 1;
                    }

                    if($flag == 0)
                    {
                        /* None selected node is its ancestor, then assign it to this array. */
                        $finalOldCases1[$k] = $v;
                    }
                    else
                    {
                        /* As one selected node is its ancestor, then assign it to this array. */
                        $finalOldCases2[$k] = $v;
                    }
                }
            }

            /* Process all top level of selected nodes. */
            foreach($caseIDList as $caseID)
            {
                if(isset($finalOldCases1[$caseID]))
                {
                    $oldCase = $finalOldCases1[$caseID];
                    if($sceneId == $oldCase->parent) continue;
                    if($sceneId == $oldCase->id) continue;

                    $resFlag = strpos($sceneRow->path,$oldCase->path);
                    /* Target node is child. */
                    if(($resFlag || $resFlag === 0) && $sceneRow->grade > $oldCase->grade ) continue;

                    $case = new stdclass();
                    $case->lastEditedBy   = $this->app->user->account;
                    $case->lastEditedDate = $now;
                    $case->product        = $sceneRow->product;
                    $case->module         = $sceneRow->module;

                    if($oldCase->isCase == 2)
                    {
                        $case->parent = $sceneId;
                        $case->path   = $sceneRow->path . "$caseID,";
                        $case->grade  = $sceneRow->grade + 1;

                        $this->dao->update(TABLE_SCENE)->data($case)->autoCheck()->where('id')->eq((int)$caseID-CHANGEVALUE)->exec();
                        if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);

                    }
                    else
                    {
                        $case->scene = $sceneId;

                        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->eq((int)$caseID)->exec();
                        if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
                    }
                }
            }

            /* Process all non-top level nodes of selected nodes. */
            foreach($caseIDList as $caseID)
            {
                if(!isset($finalOldCases2[$caseID])) continue;

                $oldCase = $finalOldCases2[$caseID];
                if($sceneId == $oldCase->parent) continue;
                if($sceneId == $oldCase->id) continue;

                $resFlag = strpos($sceneRow->path,$oldCase->path);
                /* Target node is child. */
                if(($resFlag || $resFlag === 0) && $sceneRow->grade > $oldCase->grade) continue;

                $case = new stdclass();
                $case->lastEditedBy   = $this->app->user->account;
                $case->lastEditedDate = $now;
                $case->product        = $sceneRow->product;
                $case->module         = $sceneRow->module;

                if($oldCase->isCase == 2)
                {
                    $resultScene = $this->dao->findById((int)$oldCase->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
                    $case->path  = $resultScene->path . "$caseID,";
                    $case->grade = $resultScene->grade + 1;

                    $this->dao->update(TABLE_SCENE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID - CHANGEVALUE)
                        ->exec();
                    if(!dao::isError()) $allChanges[0][$caseID] = common::createChanges($oldCase, $case);
                }
                else
                {
                    $this->dao->update(TABLE_CASE)->data($case)
                        ->autoCheck()
                        ->where('id')->eq((int)$caseID)
                        ->exec();
                    if(!dao::isError()) $allChanges[1][$caseID] = common::createChanges($oldCase, $case);
                }
            }
        }

        return $allChanges;
    }

    /**
     * Build menu query.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  int    $startScene
     * @param  string $branch
     * @access public
     * @return object
     */
    public function buildMenuQuery($rootID, $moduleID, $type, $startScene = 0, $branch = 'all')
    {
        /* Set the start module. */
        $startScenePath = '';
        if($startScene > 0)
        {
            $startScene = $this->dao->findById((int)$startScene)->from(VIEW_SCENECASE)->fetch();
            if($startScene) $startScenePath = $startScene->path . '%';
        }

        return $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF($rootID)->andWhere('product')->eq((int)$rootID)->fi()
            ->beginIF(intval($moduleID) > 0)->andWhere('module')->eq((int)$moduleID)->fi()
            ->beginIF($startScenePath)->andWhere('path')->like($startScenePath)->fi()
            ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false)->andWhere('branch')->eq((int)$branch)->fi()
            ->andWhere('isCase')->eq(2)
            ->orderBy('grade desc, sort')
            ->get();
    }

    /**
     * Build operate browse scene menu.
     *
     * @param  object $scene
     * @access public
     * @return string
     */
    public function buildOperateBrowseSceneMenu($scene)
    {
        $canBeChanged = common::canBeChanged('case', $scene);
        if(!$canBeChanged) return '';

        $params = "sceneID=$scene->id";

        /* Generate params for editing scene. */
        $editParams = $params;
        if($this->app->tab == 'project')   $editParams .= "&projectID={$this->session->project}";
        if($this->app->tab == 'execution') $editParams .= "&executionID={$this->session->execution}";

        $menu  = $this->buildMenu('testcase', 'editScene',   $editParams, $scene, 'browse', 'edit',  '',          '', '', '', $this->lang->testcase->editScene);
        $menu .= $this->buildMenu('testcase', 'deleteScene', $params,     $scene, 'browse', 'trash', 'hiddenwin', '', '', '', $this->lang->testcase->deleteScene);

        return $menu;
    }

    /**
     * Search form add scene.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $projectID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function buildSearchFormAddScene($productID, $products, $queryID, $actionURL, $projectID = 0,$moduleID = 0)
    {
        $product = ($this->app->tab == 'project' and empty($productID)) ? $products : array($productID => $products[$productID]) + array('all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['product']['values'] = $product;

        $module = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0);
        $scene  = $this->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0,  0);

        if(!$productID)
        {
            $module = array();
            foreach($products as $id => $product) $module += $this->loadModel('tree')->getOptionMenu($id, 'case', 0);
        }

        $this->config->testcase->search['params']['module']['values'] = $module;
        $this->config->testcase->search['params']['parent']['values'] = $scene;
        $this->config->testcase->search['params']['lib']['values']    = $this->loadModel('caselib')->getLibraries();

        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $productInfo = $this->loadModel('product')->getByID($productID);

            $this->config->testcase->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productInfo->type]);
            $this->config->testcase->search['params']['branch']['values'] = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, '', $projectID) + array('all' => $this->lang->branch->all);
        }

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Build tree array.
     *
     * @param  array  $treeMenu
     * @param  array  $scenes
     * @param  object $scene
     * @param  string $sceneName
     * @access public
     * @return void
     */
    public function buildTreeArray(& $treeMenu, $scenes, $scene, $sceneName = '/')
    {
        $parentScenes = explode(',', $scene->path);
        foreach($parentScenes as $parentSceneID)
        {
            if(empty($parentSceneID)) continue;
            if(empty($scenes[$parentSceneID])) continue;

            $sceneName .= $scenes[$parentSceneID]->title . '/';
        }

        $sceneName  = rtrim($sceneName, '/');
        $sceneName .= "|$scene->id\n";

        if(isset($treeMenu[$scene->id]) and !empty($treeMenu[$scene->id]))
        {
            if(isset($treeMenu[$scene->parent]))
            {
                $treeMenu[$scene->parent] .= $sceneName;
            }
            else
            {
                $treeMenu[$scene->parent] = $sceneName;
            }
            $treeMenu[$scene->parent] .= $treeMenu[$scene->id];
        }
        else
        {
            if(isset($treeMenu[$scene->parent]) and !empty($treeMenu[$scene->parent]))
            {
                $treeMenu[$scene->parent] .= $sceneName;
            }
            else
            {
                $treeMenu[$scene->parent] = $sceneName;
            }
        }
    }

    /**
     * Create scene.
     *
     * @access public
     * @return array
     */
    public function createScene()
    {
        $now   = helper::now();
        $scene = fixer::input('post')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setDefault('parent', $this->post->scene === false ? 0 : $this->post->scene)
            ->cleanInt('product,module,branch')
            ->remove('scene')
            ->get();

        $this->dao->insert(TABLE_SCENE)->data($scene)
            ->autoCheck()
            ->batchCheck($this->config->testcase->createscene->requiredFields, 'notempty')
            ->check('title', 'unique')
            ->checkFlow()
            ->exec();

        if(!$this->dao->isError())
        {
            $sceneID     = $this->dao->lastInsertID();
            $childPath   = "";
            $grade       = "";
            $viewSceneID = intval($sceneID) + CHANGEVALUE;

            if($scene->parent)
            {
                $resultScene = $this->dao->findById((int)$scene->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
                $childPath   = $resultScene->path . $viewSceneID . ',';
                $grade       = $resultScene->grade + 1;
                $this->dao->update(TABLE_SCENE)
                    ->set('path')->eq($childPath)
                    ->set('grade')->eq($grade)
                    ->set('product')->eq($resultScene->product)
                    ->set('module')->eq($resultScene->module)
                    ->set('sort')->eq(intval($sceneID) + CHANGEVALUE)
                    ->where('id')->eq($sceneID)->limit(1)
                    ->exec();
            }
            else
            {
                $childPath = ",$viewSceneID,";
                $grade     = 1;
                $this->dao->update(TABLE_SCENE)
                    ->set('path')->eq($childPath)
                    ->set('grade')->eq($grade)
                    ->set('sort')->eq(intval($sceneID) + CHANGEVALUE)
                    ->where('id')->eq($sceneID)->limit(1)
                    ->exec();
            }

            return array('status' => 'created', 'id' => $sceneID);
        }
    }

    /**
     * Get all children id.
     *
     * @param  int $sceneID
     * @access public
     * @return object
     */
    public function getAllChildId($sceneID)
    {
        if($sceneID == 0) return array();

        $scene = $this->dao->findById((int)$sceneID)->from(VIEW_SCENECASE)->fetch();
        if(empty($scene)) return array();

        return $this->dao->select('id')->from(VIEW_SCENECASE)
            ->where('path')->like($scene->path . '%')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Search cases has scene.
     *
     * @param  int    $productID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $branch
     * @param  string $auto
     * @access public
     * @return object
     */
    public function getBySearchHasScene($productID, $queryID, $orderBy, $pager = null, $branch = 0, $auto = 'no')
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
            else
            {
                $this->session->set('testcaseQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        }

        $queryProductID = $productID;
        $allProduct     = "`product` = 'all'";
        $caseQuery      = '(' . $this->session->testcaseQuery;
        if(strpos($this->session->testcaseQuery, $allProduct) !== false)
        {
            $products  = $this->app->user->view->products;
            $caseQuery = str_replace($allProduct, '1', $caseQuery);
            $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($products);
            $queryProductID = 'all';
        }

        $allBranch = "`branch` = 'all'";
        if($branch !== 'all' and strpos($caseQuery, '`branch` =') === false) $caseQuery .= " AND `branch` in('$branch')";
        if(strpos($caseQuery, $allBranch) !== false) $caseQuery = str_replace($allBranch, '1', $caseQuery);
        $caseQuery .= ')';
        $caseQuery  = str_replace('`version`', 't1.`version`', $caseQuery);

        if($this->app->tab == 'project') $caseQuery = str_replace('`product`', 't2.`product`', $caseQuery);

        /* Search criteria under compatible project. */
        $sql = $this->dao->select('*')->from(VIEW_SCENECASE)->alias('t1');
        if($this->app->tab == 'project') $sql->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case');
        $cases = $sql
            ->where($caseQuery)
            ->andWhere('isCase')->eq(1)
            ->beginIF($this->app->tab == 'project')->andWhere('t2.project')->eq($this->session->project)->fi()
            ->beginIF($this->app->tab == 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($this->app->tab != 'project' and !empty($productID) and $queryProductID != 'all')->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
            ->beginIF($auto == 'auto')->andWhere('t1.auto')->eq('auto')->fi()
            ->beginIF($auto != 'unit' && $auto != 'auto')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');

        return $cases;
    }

    /**
     * Get scenes and cases list.
     *
     * @param  mixed  $productID  int|array
     * @param  string $branch     number|all
     * @param  int    $moduleID
     * @param  array  $caseIdList
     * @param  object $pager      object|NULL
     * @param  string $type
     * @param  array  $topIdList
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getList($productID,$branch, $moduleID, $caseIdList, $pager = NULL, $type = '', $topIdList = array(), $browseType = '', &$executionSql = NULL)
    {
        if(!$caseIdList && $browseType != 'onlyscene') return array();

        /* Get list of module and its children module. */
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';

        /* Get scenes and all cases in $caseIdList. */
        $cases = $this->dao->select('id,path')->from(VIEW_SCENECASE)
            ->where('id')->in($caseIdList)
            ->beginIF(is_array($productID))->andWhere('product')->in($productID)->fi()
            ->beginIF(!is_array($productID) and intval($productID) > 0)->andWhere('product')->eq($productID)->fi()
            ->beginIF($branch != 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF(intval($moduleID) > 0)->andWhere('module')->in($modules)->fi()
            ->beginIF($browseType != 'all')->andWhere('isCase')->eq(1)->fi()
            ->beginIF($browseType == 'all')->orWhere('isCase')->eq(2)->fi()
            ->fetchPairs('id','path');

        $sceneIdArr = array();
        foreach ($cases as $path) {
            $tmpArr     = explode(',', trim($path, ','));
            $sceneIdArr = array_merge($sceneIdArr,$tmpArr);
        }
        $sceneIdArr = array_unique($sceneIdArr);

        /* Get path list. */
        $pathList = $this->dao->select('id,path')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF(is_array($productID))->andWhere('product')->in($productID)->fi()
            ->beginIF(!is_array($productID) and intval($productID) > 0)->andWhere('product')->eq($productID)->fi()
            ->andWhere('id')->in($sceneIdArr)
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($browseType == 'onlyscene')->andWhere('isCase')->eq(2)->fi()
            ->orderBy('id_asc')
            ->fetchPairs('id');

        /* Get all IDs list. */
        $objectIdList = array();
        foreach($pathList as $path)
        {
            if($type == 'child' and !empty($topIdList))
            {
                $paths = explode(',', trim($path, ','));
                $topID = $paths[0];

                if(!in_array($topID, $topIdList)) continue;
            }

            foreach(explode(',', trim($path, ',')) as $pathID) $objectIdList[$pathID] = $pathID;
        }

        /* Sort by product ID for project list. */
        $orderBy = 'product_desc,sort_asc';

        /* Get sql for batch execution. */
        if($executionSql !== null) $executionSql = $this->buildQuery($modules, $type, $objectIdList, $branch, $browseType)->andWhere('isCase')->eq(1)->orderBy($orderBy)->get();

        return $this->buildQuery($modules, $type, $objectIdList, $branch, $browseType)->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get paginated data with all IDs list.
     *
     * @param  string $modules
     * @param  string $type
     * @param  string $objectIdList
     * @param  string $branch
     * @param  string $browseType
     * @access public
     * @return object
     */
    private function buildQuery($modules, $type, $objectIdList, $branch, $browseType)
    {
        $this->dao->reset();
        $rawMethod = $this->app->rawMethod;
        $rawModule = $this->app->rawModule;
        return $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->beginIF($this->cookie->onlyAutoCase)->andWhere('isCase')->eq(1)->fi()
            ->beginIF($browseType == 'onlyscene')->andWhere('isCase')->eq(2)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($rawMethod == 'browse' and $type === 'top')->andWhere('parent')->eq(0)->andWhere('id')->in($objectIdList)->fi()
            ->beginIF($rawMethod == 'browse' and $type === 'child')->andWhere('id')->in($objectIdList)->fi()
            ->beginIF($rawModule == 'project' and $rawMethod == 'testcase' and intval($branch) > 0)->andWhere('branch')->eq($branch)->fi()
            ->beginIF($rawModule == 'project' and $rawMethod == 'testcase' and $type === 'top')->andWhere('parent')->eq(0)->andWhere('id')->in($objectIdList)->fi()
            ->beginIF($rawModule == 'project' and $rawMethod == 'testcase' and $type === 'child')->andWhere('id')->in($objectIdList)->fi();
    }

    /**
     * Search cases has scene.
     *
     * @param  int $viewID
     * @access public
     * @return string
     */
    public function getParents($viewID)
    {
        if($viewID == 0) return '/';

        $path = $this->dao->select('path')->from(VIEW_SCENECASE)->where('id')->eq((int)$viewID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return '/';

        $pathArr   = explode(',', $path);
        $scenePath = "";
        foreach ($pathArr as $vid)
        {
            $title      = $this->dao->select('title')->from(VIEW_SCENECASE)->where('id')->eq((int)$vid)->fetch('title');
            $scenePath .= '&nbsp;<i class="icon-angle-right"></i>&nbsp;'.$title;
        }

        return substr($scenePath,34);
    }

    /**
     * Get scene menu.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  int    $startScene
     * @param  int    $branch
     * @param  int    $currentScene
     * @param  bool   $emptyMenu
     * @access public
     * @return array
     */
    public function getSceneMenu($rootID, $moduleID, $type = '', $startScene = 0, $branch = 0, $currentScene = 0, $emptyMenu = false)
    {
        if(empty($branch)) $branch = 0;

        /* If type of $branch is array, get scenes of these branches. */
        if(is_array($branch))
        {
            $scenes = array();
            foreach($branch as $branchID) $scenes[$branchID] = $this->getOptionMenu($rootID,$moduleID, $type, $startScene, $branchID,$currentScene);

            return $scenes;
        }

        if($type == 'line') $rootID = 0;

        $branches = array($branch => '');
        if($branch != 'all' and strpos('story|bug|case', $type) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            if($product and $product->type != 'normal')
            {
                $branchPairs = $this->loadModel('branch')->getPairs($rootID, 'all');
                $branches    = array($branch => $branchPairs[$branch]);
            }
            elseif($product and $product->type == 'normal')
            {
                $branches = array(0 => '');
            }
        }

        $treeMenu = array();
        foreach($branches as $branchID => $branch)
        {
            $scenes = array();
            $stmt   = $this->dbh->query($this->buildMenuQuery($rootID, $moduleID, $type, $startScene, $branchID));
            while($scene = $stmt->fetch())
            {
                if ($scene->id != $currentScene) $scenes[$scene->id] = $scene;
            }

            foreach($scenes as $scene)
            {
                $branchName = (!empty($product) and $product->type != 'normal' and $scene->branch === BRANCH_MAIN) ? $this->lang->branch->main : $branch;

                $this->buildTreeArray($treeMenu, $scenes, $scene, (empty($branchName)) ? '/' : "/$branchName/");
            }
        }

        ksort($treeMenu);
        $topMenu = @array_shift($treeMenu);
        $topMenu = explode("\n", trim((string)$topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $sceneID) = explode('|', $menu);
            $lastMenu[$sceneID]    = $label;
        }

        /* Attach empty option. */
        if($emptyMenu) $lastMenu['null'] = $this->lang->null;

        return $lastMenu;
    }

    /**
     * Get scene name.
     *
     * @param  array $moduleIdList
     * @param  bool  $allPath
     * @param  bool  $branchPath
     * @access public
     * @return array
     */
    public function getScenesName($moduleIdList, $allPath = true, $branchPath = false)
    {
        if(!$allPath) return $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchPairs('id', 'title');

        $modules    = $this->dao->select('id, title, path, branch')->from(VIEW_SCENECASE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
        $allModules = $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'title');

        $branchIDList = array();
        $modulePairs  = array();
        foreach($modules as $module)
        {
            $paths = explode(',', trim($module->path, ','));
            $moduleName = '';
            foreach($paths as $path) $moduleName .= '/' . $allModules[$path];
            $modulePairs[$module->id] = $moduleName;

            if($module->branch) $branchIDList[$module->branch] = $module->branch;
        }

        if(!$branchPath) return $modulePairs;

        $branchs  = $this->dao->select('id, title')->from(VIEW_SCENECASE)->where('id')->in($branchIDList)->andWhere('deleted')->eq(0)->fetchALL('id');
        foreach($modules as $module)
        {
            if(isset($modulePairs[$module->id]))
            {
                $branchName = isset($branchs[$module->branch]) ? '/' . $branchs[$module->branch]->name : '';
                $modulePairs[$module->id] = $branchName . $modulePairs[$module->id];
            }
        }

        return $modulePairs;
    }

    /**
     * Get cases which has scene.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getTestCaseHasScene($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager, $auto = 'no')
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->caseBrowseType and $this->session->caseBrowseType != 'bysearch') ? $this->session->caseBrowseType : $browseType;
        $group      = $this->lang->navGroup->testcase;

        /* By module or all cases. */
        $cases = array();
        if($browseType == 'bymodule' or $browseType == 'all' or $browseType == 'wait')
        {
            if($this->app->tab == 'project')
            {
                $cases = $this->getModuleProjectCases($productID, $branch, $modules, $sort, $pager, $browseType, $auto);
            }
            else
            {
                $cases = $this->getModuleCases($productID, $branch, $modules, $sort, $pager, $browseType, $auto);
            }
        }
        /* Cases need confirmed. */
        elseif($browseType == 'needconfirm')
        {
            $cases = $this->dao->select('distinct t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id = t3.case')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($this->app->tab == 'project')->andWhere('t3.project')->eq($this->session->project)->fi()
                ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
                ->beginIF($auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
                ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq('unit')->fi()
                ->orderBy($sort)
                ->page($pager, 't1.id')
                ->fetchAll();
        }
        elseif($browseType == 'bysuite')
        {
            $cases = $this->getBySuite($productID, $branch, $queryID, $modules, $sort, $pager, $auto);
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            $cases = $this->getBySearchHasScene($productID, $queryID, $sort, $pager, $branch, $auto);
        }

        return $cases;
    }

    /**
     * Substr string.
     *
     * @param  string $text
     * @param  int    $length
     * @access public
     * @return string
     */
    public function istrcut($text, $length)
    {
        return (mb_strlen($text, 'utf8') > $length) ? mb_substr($text, 0, $length, 'utf8').'...' : $text;
    }

    /**
     * Print table head.
     *
     * @param  object $col
     * @param  string $orderBy
     * @param  string $vars
     * @param  bool   $checkBox
     * @access public
     * @return string
     */
    public function printHead($col, $orderBy, $vars, $checkBox = true)
    {
        $id = $col->id;
        if($col->show)
        {
            $fixed = $col->fixed == 'no' ? 'true' : 'false';
            $width = is_numeric($col->width) ? "{$col->width}px" : $col->width;
            $title = isset($col->title) ? "title='$col->title'" : '';
            $title = (isset($col->name) and $col->name) ? "title='$col->name'" : $title;
            if($id == 'id' and (int)$width < 90) $width = '120px';

            $align = $id == 'actions' ? 'text-center' : '';
            $align = in_array($id, array('budget', 'teamCount', 'estimate', 'consume', 'consumed', 'left')) ? 'text-right' : $align;

            $style  = '';
            $data   = '';
            $data  .= "data-width='$width'";
            $style .= "width:$width;";

            if(isset($col->minWidth))
            {
                $data  .= "data-minWidth='{$col->minWidth}px'";
                $style .= "min-width:{$col->minWidth}px;";
            }

            if(isset($col->maxWidth))
            {
                $data  .= "data-maxWidth='{$col->maxWidth}px'";
                $style .= "max-width:{$col->maxWidth}px;";
            }

            if(isset($col->pri)) $data .= "data-pri='{$col->pri}'";
            if($col->title == $this->lang->testcase->title)
            {
                echo "<th data-flex='$fixed' $data style='$style' class='c-$id $align' title='".$this->lang->testcase->generalTitle."'>";
            }
            else
            {
                echo "<th data-flex='$fixed' $data style='$style' class='c-$id $align' $title>";
            }

            if($id == 'actions')
            {
                echo $this->lang->actions;
            }
            else
            {
                if($id == 'id' && $checkBox) echo "<div class='checkbox-primary check-all' title='".$this->lang->selectAll."'><label></label></div>";
                if($col->title == $this->lang->testcase->title)
                {
                    echo $this->lang->testcase->generalTitle;
                }
                else
                {
                    echo $col->title;
                }
            }

            echo '</th>';
        }
    }

    /**
     * Update scene.
     *
     * @param  int $sceneID
     * @access public
     * @return string
     */
    public function updateScene($sceneID)
    {
        /* Get original data. */
        $scene = $this->dao->findById((int)$sceneID)->from(VIEW_SCENECASE)->fetch();
        $now   = helper::now();

        /* Collect changed data. */
        $scenePost = fixer::input('post')
            ->add('id', $sceneID - CHANGEVALUE)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->cleanInt('product,module')
            ->get();

        /* Update scene with changed data. */
        $this->dao->update(TABLE_SCENE)->data($scenePost)
            ->batchCheck($this->config->testcase->createscene->requiredFields, 'notempty')
            ->where('id')->eq((int)$sceneID - CHANGEVALUE)
            ->checkFlow()
            ->exec();

        /* Verify database error. */
        if($this->dao->isError()) return;

        $sceneNew    = $this->dao->findById((int)$sceneID - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
        $childPath   = "";
        $grade       = "";
        $viewSceneID = $sceneID;

        /* Not change key fields. */
        if($scene->parent == $sceneNew->parent and $scene->product == $sceneNew->product and $scene->branch == $sceneNew->branch and $scene->module == $sceneNew->module) return array('status' => 'updated', 'id' => $sceneID);;

        if($sceneNew->parent)
        {
            /* Update product, module, branch, path and grade field with parent scene. */
            $resultScene = $this->dao->findById((int)$sceneNew->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
            $childPath   = $resultScene->path . $viewSceneID . ',';
            $grade       = $resultScene->grade + 1;
            $this->dao->update(TABLE_SCENE)
                ->set('path')->eq($childPath)
                ->set('grade')->eq($grade)
                ->set('product')->eq($resultScene->product)
                ->set('module')->eq($resultScene->module)
                ->set('branch')->eq($resultScene->branch)
                ->where('id')->eq($sceneID - CHANGEVALUE)
                ->exec();
        }
        else
        {
            /* Update path and grade field without parent scene. */
            $childPath = ",$viewSceneID,";
            $grade     = 1;
            $this->dao->update(TABLE_SCENE)
                ->set('path')->eq($childPath)
                ->set('grade')->eq($grade)
                ->where('id')->eq($sceneID - CHANGEVALUE)
                ->exec();
        }

        /* Get children scenes and cases. */
        $children = $this->dao->select('*')->from(VIEW_SCENECASE)
            ->where('deleted')->eq(0)
            ->andWhere('(path')->like($scene->path.'%')
            ->orWhere('path')->like(",$sceneID,%")
            ->markRight(1)
            ->orderBy('grade asc')
            ->fetchAll('id');

        foreach($children as $id => $childScene)
        {
            if(!$id or $id == $sceneID or !$childScene->parent) continue;

            /* Get grade of child with parent scene. */
            $parentScene = $this->dao->findById($childScene->parent - CHANGEVALUE)->from(TABLE_SCENE)->fetch();

            /* Update sub-scene. */
            if($childScene->isCase == 2)
            {
                /* The grade of child node must be greater than parent node. */
                if($childScene->grade <= $scene->grade) continue;

                $viewID    = $id;
                $childPath = $parentScene->path . $viewID . ',';
                $grade     = $parentScene->grade + 1;

                $this->dao->update(TABLE_SCENE)
                    ->set('path')->eq($childPath)
                    ->set('grade')->eq($grade)
                    ->set('product')->eq($parentScene->product)
                    ->set('module')->eq($parentScene->module)
                    ->set('branch')->eq($parentScene->branch)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->set('lastEditedDate')->eq($now)
                    ->where('id')->eq($id - CHANGEVALUE)
                    ->exec();

                continue;
            }

            /* Update case. */
            $this->dao->update(TABLE_CASE)
                ->set('product')->eq($parentScene->product)
                ->set('module')->eq($parentScene->module)
                ->set('branch')->eq($parentScene->branch)
                ->set('lastEditedBy')->eq($this->app->user->account)
                ->set('lastEditedDate')->eq($now)
                ->where('id')->eq($id)
                ->exec();
        }

        return array('status' => 'updated', 'id' => $sceneID);
    }

    /**
     * Get xmind file content.
     *
     * @param  string $fileName
     * @access public
     * @return string
     */
    public function getXmindImport($fileName)
    {
        $xmlNode  = simplexml_load_file($fileName);
        $testData = $this->xmlToArray($xmlNode);

        return json_encode($testData);
    }

    /**
     * Save xmind file content to database.
     *
     * @access public
     * @return array
     */
    public function saveXmindImport()
    {
        $this->dao->begin();

        $sceneIds  = array();
        $sceneList = $this->post->sceneList;
        foreach($sceneList as $scene)
        {
            $tmpId  = $scene["tmpId"];
            $tmpPId = $scene["tmpPId"];

            $result = $this->saveScene($scene,$sceneIds);
            /* Rollback. */
            if($result["result"] == "fail")
            {
                $this->dao->rollBack();
                return $result;
            }

            $sceneIds[$tmpId] = array("id"=>$result['sceneID'], "tmpPId"=>$tmpPId);
        }

        $testcaseList = $this->post->testcaseList;
        foreach($testcaseList as $testcase)
        {
            $tmpId  = $testcase["tmpId"];
            $tmpPId = $testcase["tmpPId"];

            $result = $this->saveTestcase($testcase,$sceneIds);
            if($result["result"] == "fail")
            {
                $this->dao->rollBack();
                return $result;
            }

            $sceneIds[$tmpId] = array("id"=>$result['testcaseID'], "tmpPId"=>$tmpPId);
        }

        $this->dao->commit();

        return array("result"=>"success","message"=>1);
    }

    /**
     * Save test case.
     *
     * @param  array $testcaseData
     * @param  array $sceneIds
     * @access public
     * @return array
     */
    public function saveTestcase($testcaseData, $sceneIds)
    {
        $tmpPId = $testcaseData["tmpPId"];
        $scene  = 0;

        if(isset($sceneIds[$tmpPId]))
        {
            $pScene = $sceneIds[$tmpPId];
            $scene  = $pScene["id"] + CHANGEVALUE;
        }

        $id         = isset($testcaseData["id"]) ? $testcaseData["id"] : -1;
        $module     = $testcaseData["module"];
        $product    = $testcaseData["product"];
        $branch     = $testcaseData["branch"];
        $title      = $testcaseData["name"];
        $pri        = $testcaseData["pri"];
        $now        = helper::now();
        $testcaseID = -1;
        $version    = 1;

        if(!isset($testcaseData["id"]))
        {
            $testcase             = new stdclass();
            $testcase->scene      = $scene;
            $testcase->module     = $module;
            $testcase->product    = $product;
            $testcase->branch     = $branch;
            $testcase->title      = $title;
            $testcase->pri        = $pri;
            $testcase->type       = "feature";
            $testcase->status     = "normal";
            $testcase->version    = $version;
            $testcase->openedBy   = $this->app->user->account;
            $testcase->openedDate = $now;

            $this->dao->insert(TABLE_CASE)->data($testcase)->autoCheck()->exec();
            $testcaseID = $this->dao->lastInsertID();

            $order = new stdclass();
            $order->sort = $testcaseID;
            $this->dao->update(TABLE_CASE)->data($order)->where('id')->eq((int)$testcaseID)->exec();
        }
        else
        {
            $oldCase = $this->dao->select('version,id')->from(TABLE_CASE)->where('id')->eq((int)$id)->fetch();

            if(isset($oldCase->id))
            {
                if(!isset($oldCase->version)) return array('result' => 'fail', 'message' => 'not exist testcase');

                $version  = $oldCase->version + 1;

                $testcase                 = new stdclass();
                $testcase->id             = $id;
                $testcase->scene          = $scene;
                $testcase->module         = $module;
                $testcase->product        = $product;
                $testcase->branch         = $branch;
                $testcase->title          = $title;
                $testcase->pri            = $pri;
                $testcase->version        = $version;
                $testcase->lastEditedBy   = $this->app->user->account;
                $testcase->lastEditedDate = $now;

                $testcaseID = $id;
                $this->dao->update(TABLE_CASE)->data($testcase)->where('id')->eq((int)$id)->exec();
            }
            else
            {
                $testcase             = new stdclass();
                $testcase->scene      = $scene;
                $testcase->module     = $module;
                $testcase->product    = $product;
                $testcase->branch     = $branch;
                $testcase->title      = $title;
                $testcase->pri        = $pri;
                $testcase->type       = "feature";
                $testcase->status     = "normal";
                $testcase->version    = $version;
                $testcase->openedBy   = $this->app->user->account;
                $testcase->openedDate = $now;

                $this->dao->insert(TABLE_CASE)->data($testcase)->autoCheck()->exec();
                $testcaseID = $this->dao->lastInsertID();

                $order       = new stdclass();
                $order->sort = $testcaseID;
                $this->dao->update(TABLE_CASE)->data($order)->where('id')->eq((int)$testcaseID)->exec();
            }
        }

        if($this->dao->isError())
        {
            return array('result' => 'fail', 'message' => $this->dao->getError(true));
        }

        $stepList = isset($testcaseData["stepList"]) ? $testcaseData["stepList"] : array();
        if(isset($stepList))
        {
            foreach($stepList as $step)
            {
                $tmpPId = $step["tmpPId"];
                $pObj   = isset($sceneIds[$tmpPId]) ? $sceneIds[$tmpPId] : array();

                $parent = 0;
                if(isset($sceneIds[$tmpPId])) $parent = $pObj["id"];

                $case   = $testcaseID;
                $type   = $step["type"];
                $desc   = $step["desc"];
                $expect = isset($step["expect"]) ? $step["expect"] : '';

                $casestep            = new stdclass();
                $casestep->case      = $case;
                $casestep->version   = $version;
                $casestep->type      = $type;
                $casestep->parent    = $parent;
                $casestep->desc      = $desc;
                $casestep->expect    = $expect;

                $this->dao->insert(TABLE_CASESTEP)->data($casestep)->autoCheck()->exec();
                $casestepID = $this->dao->lastInsertID();

                if($this->dao->isError()) return array('result' => 'fail', 'message' => $this->dao->getError(true));

                $sceneIds[$step["tmpId"]] = array("id"=>$casestepID, "tmpPId"=>$tmpPId);
            }
        }

        return array('result' => 'success', 'message' => 1,'testcaseID'=>$testcaseID);
    }

    /**
     * Save scene.
     *
     * @param  array $sceneData
     * @param  array $sceneIds
     * @access public
     * @return array
     */
    public function saveScene($sceneData, $sceneIds)
    {
        $id      = isset($sceneData["id"]) ? $sceneData["id"] : -1;
        $name    = $sceneData["name"];
        $module  = isset($sceneData["module"]) ? $sceneData["module"] : 0;
        $product = $sceneData["product"];
        $branch  = $sceneData["branch"];
        $now     = helper::now();
        $sceneID = -1;

        if(!isset($sceneData["id"]))
        {
            $scene             = new stdclass();
            $scene->title      = $name;
            $scene->module     = $module;
            $scene->product    = $product;
            $scene->branch     = $branch;
            $scene->openedBy   = $this->app->user->account;
            $scene->openedDate = $now;

            $this->dao->insert(TABLE_SCENE)->data($scene)->autoCheck()->exec();
            $sceneID = $this->dao->lastInsertID();

            $order       = new stdclass();
            $order->sort = ($sceneID + CHANGEVALUE);

            $this->dao->update(TABLE_SCENE)->data($order)->where('id')->eq((int)$sceneID)->exec();
        }
        else
        {
            $scene                 = new stdclass();
            $scene->title          = $name;
            $scene->module         = $module;
            $scene->product        = $product;
            $scene->branch         = $branch;
            $scene->lastEditedBy   = $this->app->user->account;
            $scene->lastEditedDate = $now;

            $sceneID      = $id;
            $affectedRows = $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->eq((int)$id)->exec();
            if(empty($affectedRows)) return array('result' => 'fail', 'message' => sprintf($this->lang->testcase->errorSceneNotExist, $id));
        }

        if($this->dao->isError()) return array('result' => 'fail', 'message' => $this->dao->getError(true));

        $tmpPId = $sceneData["tmpPId"];
        $pScene = isset($sceneIds[$tmpPId]) ? $sceneIds[$tmpPId] : array();
        $parent = 0;
        $grade  = 1;
        $path   = ",".($sceneID + CHANGEVALUE).",";

        if(isset($sceneIds[$tmpPId]))
        {
            $parent      = $pScene["id"];
            $parentScene = $this->dao->findById((int)$parent)->from(TABLE_SCENE)->fetch();
            $path        = $parentScene->path . ($sceneID + CHANGEVALUE).",";
            $grade       = $parentScene->grade + 1;
        }

        if($parent != 0) $parent = $parent + CHANGEVALUE;

        $this->dao->update(TABLE_SCENE)
            ->set('parent')->eq($parent)
            ->set('path')->eq($path)
            ->set('grade')->eq($grade)
            ->where('id')->eq($sceneID)
            ->limit(1)
            ->exec();

        if($this->dao->isError()) return array('result' => 'fail', 'message' => $this->dao->getError(true));

        return array('result' => 'success', 'message' => 1,"sceneID"=>$sceneID);
    }

    /**
     * Get export data.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $branch
     * @access public
     * @return array
     */
    public function getXmindExport($productID, $moduleID, $branch)
    {
        $caseList   = $this->getCaseByProductAndModule($productID, $moduleID);
        $stepList   = $this->getStepByProductAndModule($productID, $moduleID);
        $sceneInfo  = $this->getSceneByProductAndModule($productID, $moduleID);
        $moduleList = $this->getModuleByProductAndModel($productID, $moduleID, $branch);

        $config = $this->getXmindConfig();

        return array(
                'caseList'  =>$caseList,
                'stepList'  =>$stepList,
                'sceneMaps' =>$sceneInfo['sceneMaps'],
                'topScenes' =>$sceneInfo['topScenes'],
                'moduleList'=>$moduleList,
                'config'    =>$config
            );
    }

    /**
     * Get module by product.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $branch
     * @access public
     * @return array
     */
    function getModuleByProductAndModel($productID, $moduleID, $branch)
    {
        $moduleList = array();

        if($moduleID > 0)
        {
            $module = $this->loadModel('tree')->getByID($moduleID);

            $moduleList[$module->id] = $module->name;
        }
        else
        {
            $moduleList = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

            unset($moduleList['0']);
        }

        return $moduleList;
    }

    /**
     * Get case by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getCaseByProductAndModule($productID, $moduleID)
    {
        $fields = "t2.id as productID,"
            . "t2.`name` as productName,"
            . "t3.id as moduleID,"
            . "t3.`name` as moduleName,"
            . "t4.id as sceneID,"
            . "t4.title as sceneName,"
            . "t1.id as testcaseID,"
            . "t1.title as `name`,"
            . "t1.pri";

        $caseList = $this->dao->select($fields)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t1.module = t3.id')
            ->leftJoin(TABLE_SCENE)->alias('t4')->on('t1.scene = t4.id+' . CHANGEVALUE)
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($moduleID > 0)->andWhere('t1.module')->eq($moduleID)->fi()
            ->fetchAll();

        return $caseList;
    }

    /**
     * Get step by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getStepByProductAndModule($productID, $moduleID)
    {
        $fields = "t1.id as testcaseID,"
            . "t2.id as stepID,"
            . "t2.type,"
            . "t2.parent as parentID,"
            . "t2.`desc`,"
            . "t2.expect";

        $stepList = $this->dao->select($fields)->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.`case` and t1.version = t2.version')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.id')->gt('0')
            ->beginIF($moduleID > 0)->andWhere('t1.module')->eq($moduleID)->fi()
            ->fetchAll();

        return $stepList;
    }

    /**
     * Get scene by product and module.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @access public
     * @return array
     */
    function getSceneByProductAndModule($productID, $moduleID)
    {
        $sceneList = $this->dao->select('id as sceneID, title as sceneName, path, parent as parentID, product as productID, module as moduleID')
            ->from(TABLE_SCENE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($moduleID > 0)->andWhere('module')->eq($moduleID)->fi()
            ->fetchAll();

        $sceneMaps = array();
        $topScenes = array();
        foreach($sceneList as $one)
        {
            if($one->parentID == 0) $topScenes[] = $one;

            $sceneMaps[$one->sceneID] = $one;
        }

        return array('sceneMaps'=>$sceneMaps,'topScenes'=>$topScenes);
    }

    /**
     * Check config.
     *
     * @param  string $str
     * @access public
     * @return bool
     */
    function checkConfigValue($str)
    {
        return preg_match("/^[a-zA-Z]{1,10}$/",$str);
    }

    /**
     * Save xmind config.
     *
     * @access public
     * @return array
     */
    function saveXmindConfig()
    {
        $configList = array();

        $module = $this->post->module;
        if(isset($module) && !empty($module))
        {
            if(!$this->checkConfigValue($module)) return array('result' => 'fail', 'message' => '模块特征字符串只能是1-10个字母');
            $configList[] = array('key'=>'module','value'=>$module);
        }

        $scene = $this->post->scene;
        if(isset($scene) && !empty($scene))
        {
            if(!$this->checkConfigValue($scene)) return array('result' => 'fail', 'message' => '场景特征字符串只能是1-10个字母');
            $configList[] = array('key'=>'scene','value'=>$scene);
        }

        $case = $this->post->case;
        if(isset($case) && !empty($case))
        {
            if(!$this->checkConfigValue($case)) return array('result' => 'fail', 'message' => '测试用例特征字符串只能是1-10个字母');
            $configList[] = array('key'=>'case','value'=>$case);
        }

        $pri = $this->post->pri;
        if(isset($pri) && !empty($pri))
        {
            if(!$this->checkConfigValue($pri)) return array('result' => 'fail', 'message' => '优先级特征字符串只能是1-10个字母');
            $configList[] = array('key'=>'pri','value'=>$pri);
        }

        $group = $this->post->group;
        if(isset($group) && !empty($group))
        {
            if(!$this->checkConfigValue($group)) return array('result' => 'fail', 'message' => '步骤分组特征字符串只能是1-10个字母');
            $configList[] = array('key'=>'group','value'=>$group);
        }

        $map = array();
        $map[strtolower($module)] = true;
        $map[strtolower($scene)]  = true;
        $map[strtolower($case)]   = true;
        $map[strtolower($pri)]    = true;
        $map[strtolower($group)]  = true;

        if(count($map) < 5) return array('result' => 'fail', 'message' => '特征字符串不能重复');

        $this->dao->begin();

        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq('xmind')
            ->exec();

        foreach($configList as $one)
        {
            $config = new stdclass();

            $config->module  = 'testcase';
            $config->section = 'xmind';
            $config->key     = $one['key'];
            $config->value   = $one['value'];
            $config->owner   = $this->app->user->account;

            $this->dao->insert(TABLE_CONFIG)->data($config)->autoCheck()->exec();

            if($this->dao->isError())
            {
                $this->dao->rollBack();
                return array('result' => 'fail', 'message' => $this->dao->getError(true));
            }
        }

        $this->dao->commit();

        return array("result" => "success", "message" => 1);
    }

    /**
     * Get xmind config.
     *
     * @access public
     * @return array
     */
    function getXmindConfig()
    {
        $configItems = $this->dao->select("`key`,value")->from(TABLE_CONFIG)
            ->where('owner')->eq($this->app->user->account)
            ->andWhere('module')->eq('testcase')
            ->andWhere('section')->eq('xmind')
            ->fetchAll();

        $config = array();
        foreach($configItems as $item) $config[$item -> key] = $item -> value;

        if(!isset($config['module'])) $config['module'] = 'M';
        if(!isset($config['scene']))  $config['scene']  = 'S';
        if(!isset($config['case']))   $config['case']   = 'C';
        if(!isset($config['pri']))    $config['pri']    = 'P';
        if(!isset($config['group']))  $config['group']  = 'G';

        return $config;
    }

    /**
     * Convert xml to array.
     *
     * @param  object $xml
     * @param  array  $options
     * @access public
     * @return array
     */
    function xmlToArray($xml, $options = array())
    {
        $defaults = array(
            'namespaceRecursive' => false, // Get XML doc namespaces recursively
            'removeNamespace'    => true, // Remove namespace from resulting keys
            'namespaceSeparator' => ':', // Change separator to something other than a colon
            'attributePrefix'    => '', // Distinguish between attributes and nodes with the same name
            'alwaysArray'        => array(), // Array of XML tag names which should always become arrays
            'autoArray'          => true, // Create arrays for tags which appear more than once
            'textContent'        => 'text', // Key used for the text content of elements
            'autoText'           => true, // Skip textContent key if node has no attributes or child nodes
            'keySearch'          => false, // (Optional) search and replace on tag and attribute names
            'keyReplace'         => false, // (Optional) replace values for above search values
        );

        $options        = array_merge($defaults, $options);
        $namespaces     = $xml->getDocNamespaces($options['namespaceRecursive']);
        $namespaces[''] = null; // Add empty base namespace

        /* Get attributes from all namespaces. */
        $attributesArray = array();
        foreach($namespaces as $prefix => $namespace)
        {
            if($options['removeNamespace']) $prefix = '';

            foreach($xml->attributes($namespace) as $attributeName => $attribute)
            {
                // (Optional) replace characters in attribute name
                if($options['keySearch']) $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);

                $attributeKey = $options['attributePrefix'] . ($prefix ? $prefix . $options['namespaceSeparator'] : '') . $attributeName;
                $attributesArray[$attributeKey] = (string) $attribute;
            }
        }

        // Get child nodes from all namespaces
        $tagsArray = array();
        foreach($namespaces as $prefix => $namespace)
        {
            if($options['removeNamespace']) $prefix = '';

            foreach($xml->children($namespace) as $childXml)
            {
                // Recurse into child nodes
                $childArray      = $this->xmlToArray($childXml, $options);
                $childTagName    = key($childArray);
                $childProperties = current($childArray);

                // Replace characters in tag name
                if($options['keySearch']) $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);

                // Add namespace prefix, if any
                if($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

                if(!isset($tagsArray[$childTagName]))
                {
                    // Only entry with this key
                    // Test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray'], true) || !$options['autoArray'] ? array($childProperties) : $childProperties;
                }
                elseif(is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1))
                {
                    // Key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                }
                else
                {
                    // Key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }

        // Get text content of node
        $textContentArray = array();
        $plainText = trim((string) $xml);
        if($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

        // Stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || $plainText === '' ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

        // Return node as array
        return array($xml->getName() => $propertiesArray);
    }

    /**
     * Append case fails.
     *
     * @param  object $case
     * @param  string $from
     * @param  int    $taskID
     * @access public
     * @return object
     */
    public function appendCaseFails(object $case, string $from, int $taskID): object
    {
        $caseFails = $this->dao->select('COUNT(*) AS count')->from(TABLE_TESTRESULT)
            ->where('caseResult')->eq('fail')
            ->andwhere('`case`')->eq($case->id)
            ->beginIF($from == 'testtask')->andwhere('`run`')->eq($taskID)->fi()
            ->fetch('count');
        $case->caseFails = $caseFails;
        return $case;
    }
}
