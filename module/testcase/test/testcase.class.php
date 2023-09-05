<?php
class testcaseTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testcase');
    }

    /**
     * 测试创建一个用例。
     * Test create a case.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function createTest($param)
    {
        $case = new stdclass();
        $case->product      = 1;
        $case->module       = 1821;
        $case->type         = 'feature';
        $case->stage        = ',unittest';
        $case->story        = 4;
        $case->color        = '';
        $case->pri          = 3;
        $case->precondition = '前置条件';
        $case->steps        = array('1' => '1','1.1' => '1.1', '1.2' => '1.2', '2' => '2', '3' => '3', '4' => '');
        $case->stepType     = array('1' => 'group','1.1' => 'item', '1.2' => 'item', '2' => 'step', '3' => 'item', '4' => 'step');
        $case->expects      = array('1' => '','1.1' => '', '1.2' => '', '2' => '', '3' => '', '4' => '');
        $case->keywords     = '关键词1,关键词2';
        $case->status       = 'normal';

        foreach($param as $field => $value) $case->{$field} = $value;

        $objects = $this->objectModel->create($case);

        unset($_POST);

        if(dao::isError()) return isset($param['type']) ? dao::getError()['type'][0] : dao::getError()['title'][0];

        return $objects;
    }

    /**
     * Create a scene.
     *
     * @param  array  $scene
     * @access public
     * @return bool|array
     */
    public function createSceneTest(array $scene): bool|array
    {
        $result = $this->objectModel->createScene((object)$scene);
        if(!$result) return $result;

        $scene  = $this->objectModel->dao->select('*')->from(TABLE_SCENE)->where('deleted')->eq('0')->orderBy('id_desc')->limit(1)->fetch();
        $action = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();

        return array('scene' => $scene, 'action' => $action);
    }

    /**
     * 测试获取模块的用例。
     * Test get cases of modules.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int|array   $moduleIdList
     * @param  string      $browseType
     * @param  string      $auto   no|unit
     * @param  string      $caseType
     * @access public
     * @return string
     */
    public function getModuleCasesTest(int $productID, int|string $branch = 0, int|array $moduleIdList = 0, string $browseType = '', string $auto = 'no', string $caseType = ''): string
    {
        $objects = $this->objectModel->getModuleCases($productID, $branch, $moduleIdList, $browseType, $auto, $caseType);

        if(dao::isError()) return dao::getError();

        $ids = is_array($objects) ? implode(',', array_keys($objects)) : '0';
        return $ids;
    }

    /**
     * Test get project cases of a module.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleIdList
     * @param  string $browseType
     * @param  string $auto
     * @param  string $caseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getModuleProjectCasesTest($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getModuleProjectCases($productID, $branch, $moduleIdList, $browseType, $auto, $caseType, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get execution cases.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @access public
     * @return string
     */
    public function getExecutionCasesTest($executionID, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        $objects = $this->objectModel->getExecutionCases($executionID, $orderBy, $pager, $browseType);

        if(dao::isError()) return dao::getError();

        return $browseType == 'all' ? $objects : count($objects);
    }

    /**
     * Test get cases by suite.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $suiteID
     * @param  int    $moduleIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getBySuiteTest($productID, $branch = 0, $suiteID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getBySuite($productID, $branch, $suiteID, $moduleIdList, $orderBy, $pager, $auto);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get case info by ID.
     *
     * @param  int $caseID
     * @param  int $version
     * @access public
     * @return object
     */
    public function getByIdTest($caseID, $version = 0)
    {
        $object = $this->objectModel->getById($caseID, $version = 0);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get case list.
     *
     * @param  array  $caseIdList
     * @param  string $query
     * @access public
     * @return array
     */
    public function getByListTest($caseIdList, $query = '')
    {
        return $this->objectModel->getByList($caseIdList, $query);
    }

    /**
     * Test get test cases.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getTestCasesTest($productID, $browseType, $queryID, $auto = 'no')
    {
        $objects = $this->objectModel->getTestCases($productID, 0, $browseType, $queryID, $moduleID, '', 'id_desc', null, $auto);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get cases by assignedTo.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return string
     */
    public function getByAssignedToTest($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByAssignedTo($account, $orderBy = 'id_desc', $pager = null, $auto = 'no');

        if(dao::isError()) return dao::getError();

        $ids = implode(array_keys($objects), ',');
        return $ids;
    }

    /**
     * Test get cases by openedBy
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getByOpenedByTest($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByOpenedBy($account, $orderBy = 'id_desc', $pager = null, $auto = 'no');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get cases by type.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  string $status
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return int
     */
    public function getByStatusTest($productID = 0, $branch = 0, $type = 'all', $status = 'all', $moduleID = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByStatus($productID, $branch, $type, $status, $moduleID, $orderBy, $pager, $auto);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get stories' cases.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCasesTest($storyID)
    {
        $objects = $this->objectModel->getStoryCases($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get case pairs by product id and branch.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getPairsByProductTest($productID = 0, $branch = 0)
    {
        $objects = $this->objectModel->getPairsByProduct($productID, $branch);

        if(dao::isError()) return dao::getError();

        if(empty($objects)) return 'empty';
        return $objects;
    }

    /**
     * Test get counts of some stories' cases.
     *
     * @param  array  $stories
     * @access public
     * @return void
     */
    public function getStoryCaseCountsTest($stories)
    {
        $counts = $this->objectModel->getStoryCaseCounts($stories);

        if(dao::isError()) return dao::getError();

        return $counts;
    }

    /**
     * Test get scenes by id list and query string.
     *
     * @param  array  $sceneIdList
     * @param  string $query
     * @access public
     * @return array
     */
    public function getScenesByListTest($sceneIdList, $query = '')
    {
        return $this->objectModel->getScenesByList($sceneIdList, $query);
    }

    /**
     * 测试获取相关用例。
     * Test get cases to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getCases2LinkTest(int $caseID, string $browseType): array
    {
        return $this->objectModel->getCases2Link($caseID, $browseType);
    }

    /**
     * 测试获取相关 bug。
     * Test get bugs to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getBugs2LinkTest(int $caseID, string $browseType): array
    {
        return $this->objectModel->getBugs2Link($caseID, $browseType);
    }

    /**
     * 获取关联需求的测试。
     * Get related stories test.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getRelatedStoriesTest(array $storyIdList): array
    {
        $cases = array();
        foreach($storyIdList as $storyID)
        {
            $case = new stdclass();
            $case->story = $storyID;

            $cases[] = $case;
        }

        return $this->objectModel->getRelatedStories($cases);
    }

    /**
     * 获取关联用例的测试。
     * Get related cases test.
     *
     * @param  array  $linkCases
     * @access public
     * @return array
     */
    public function getRelatedCasesTest(array $linkCases): array
    {
        $cases = array();
        foreach($linkCases as $linkCase)
        {
            $case = new stdclass();
            $case->linkCase = $linkCase;

            $cases[] = $case;
        }

        return $this->objectModel->getRelatedCases($cases);
    }

    /**
     * 更新的测试用例。
     * Test update a case.
     *
     * @param  array  $param
     * @access public
     * @return bool|array
     */
    public function updateTest(int $caseID, array $param = array()): bool|array
    {
        $oldCase = $this->objectModel->getByID($caseID);

        $case = new stdclass();
        $case->title          = $oldCase->title;
        $case->color          = $oldCase->color;
        $case->precondition   = $oldCase->precondition;
        $case->steps          = array('用例步骤描述1');
        $case->stepType       = array('step');
        $case->expects        = array('这是用例预期结果1');
        $case->comment        = '';
        $case->labels         = '';
        $case->lastEditedDate = $oldCase->lastEditedDate;
        $case->product        = $oldCase->product;
        $case->module         = $oldCase->module;
        $case->story          = $oldCase->story;
        $case->type           = $oldCase->type;
        $case->stage          = $oldCase->stage;
        $case->pri            = $oldCase->pri;
        $case->status         = $oldCase->status;
        $case->keywords       = $oldCase->keywords;
        $case->linkBug        = array();

        foreach($param as $field => $value) $case->$field = $value;

        $changes = $this->objectModel->update($case, $oldCase);
        if($changes == array()) $changes = '没有数据更新';

        if(dao::isError()) return dao::getError();

        return $changes;
    }

    /**
     * 测试评审用例。
     * Test review case.
     *
     * @param  int    $caseID
     * @param  object $case
     * @access public
     * @return array
     */
    public function reviewTest(int $caseID, object $case)
    {
        $oldCase = $this->objectModel->getByID($caseID);
        $objects = $this->objectModel->review($case, $oldCase);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($caseID);
    }

    /**
     * Test batch review cases.
     *
     * @param  array  $caseIdList
     * @param  string $result
     * @access public
     * @return array
     */
    public function batchReviewTest($caseIdList, $result)
    {
        $objects = $this->objectModel->batchReview($caseIdList, $result);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByList($caseIdList);
    }

    /**
     * Test the batch delete method.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @access public
     * @return bool
     */
    public function batchDeleteTest($caseIdList, $sceneIdList)
    {
        return $this->objectModel->batchDelete($caseIdList, $sceneIdList);
    }

    /**
     * Test batch change branch.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeBranchTest($caseIdList, $sceneIdList, $branchID)
    {
        return $this->objectModel->batchChangeBranch($caseIdList, $sceneIdList, $branchID);
    }

    /**
     * Test batch change branch of cases.
     *
     * @param  array  $caseIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeCaseBranchTest($caseIdList, $branchID)
    {
        return $this->objectModel->batchChangeCaseBranch($caseIdList, $branchID);
    }

    /**
     * Test batch change branch of scenes.
     *
     * @param  array  $sceneIdList
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function batchChangeSceneBranchTest($sceneIdList, $branchID)
    {
        return $this->objectModel->batchChangeSceneBranch($sceneIdList, $branchID);
    }

    /**
     * Test batch change module.
     *
     * @param  array  $caseIdList
     * @param  array  $sceneIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeModuleTest($caseIdList, $sceneIdList, $moduleID)
    {
        return $this->objectModel->batchChangeModule($caseIdList, $sceneIdList, $moduleID);
    }

    /**
     * Test batch change module of cases.
     *
     * @param  array  $caseIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeCaseModuleTest($caseIdList, $moduleID)
    {
        return $this->objectModel->batchChangeCaseModule($caseIdList, $moduleID);
    }

    /**
     * Test batch change module of scenes.
     *
     * @param  array  $sceneIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeSceneModuleTest($sceneIdList, $moduleID)
    {
        return $this->objectModel->batchChangeSceneModule($sceneIdList, $moduleID);
    }

    /**
     * Test batch case type change.
     *
     * @param  array  $caseIdList
     * @param  string $type
     * @access public
     * @return array
     */
    public function batchChangeTypeTest($caseIdList, $type)
    {
        return $this->objectModel->batchChangeType($caseIdList, $type);
    }

    /**
     * 批量修改用例所属场景。
     * Batch change scene of cases.
     *
     * @param  array  $caseIdList
     * @param  int    $sceneID
     * @access public
     * @return bool
     */
    public function batchChangeSceneTest(array $caseIdList, int $sceneID): bool
    {
        return $this->objectModel->batchChangeScene($caseIdList, $sceneID);
    }

    /**
     * 批量确认需求变动。
     * Batch confirm story change of cases.
     *
     * @param  array  $caseIdList
     * @access public
     * @return bool
     */
    public function batchConfirmStoryChangeTest(array $caseIdList): bool
    {
        return $this->objectModel->batchConfirmStoryChange($caseIdList);
    }

    /**
     * Test join steps to a string, thus can diff them.
     *
     * @param  array  $stepIDList
     * @access public
     * @return string
     */
    public function joinStepTest($stepIDList)
    {
        global $tester;
        $steps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('id')->in($stepIDList)->fetchAll();

        $string = $this->objectModel->joinStep($steps);

        if(dao::isError()) return dao::getError();

        $string = str_replace("\n", ' ', $string);
        return $string;
    }

    /**
     * Test get fields for import.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getImportFieldsTest($productID = 0)
    {
        $object = $this->objectModel->getImportFields($productID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试追加 bug 和执行结果信息。
     * Test append bugs and results.
     *
     * @param  array  $cases
     * @param  string $type
     * @param  array  $caseIdList
     * @access public
     * @return array
     */
    public function appendDataTest(array $cases, string $type = 'case', array $caseIdList = array()): array
    {
        $objects = $this->objectModel->appendData($cases, $type, $caseIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check whether force not review.
     *
     * @access public
     * @return int
     */
    public function forceNotReviewTest()
    {
        $object = $this->objectModel->forceNotReview();

        if(dao::isError()) return dao::getError();

        return $object ? 1 : 2;
    }

    /**
     * Test sync case to project.
     *
     * @param  int    $caseID
     * @access public
     * @return int
     */
    public function syncCase2ProjectTest($caseID)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->exec();
        $case = $this->objectModel->getByID($caseID);
        $this->objectModel->syncCase2Project($case, $caseID);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->fetchAll();
        return count($objects);
    }

    /**
     *
     * 处理用例和项目的关系的测试用例。
     * Test deal with the relationship between the case and project when edit the case.
     *
     * @param  int    $caseID
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function updateCase2ProjectTest(int $caseID, string $objectType, int $objectID): array
    {
        $oldCase = $this->objectModel->getByID($caseID);

        $case = new stdclass();
        $case->title          = $oldCase->title;
        $case->color          = $oldCase->color;
        $case->precondition   = $oldCase->precondition;
        $case->lastEditedDate = $oldCase->lastEditedDate;
        $case->product        = $objectType == 'product' ? $objectID : $oldCase->product;
        $case->module         = $oldCase->module;
        $case->story          = $objectType == 'story' ? $objectID : $oldCase->story;
        $case->type           = $oldCase->type;
        $case->stage          = $oldCase->stage;
        $case->pri            = $oldCase->pri;
        $case->status         = $oldCase->status;
        $case->keywords       = $oldCase->keywords;
        $case->version        = $oldCase->version + 1;
        $case->linkCase       = $oldCase->linkCase;
        $case->lastEditedBy   = $oldCase->lastEditedBy;
        $case->branch         = $oldCase->branch;

        $this->objectModel->updateCase2Project($oldCase, $case);

        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->fetchAll();
    }

    /**
     * 关联 bug 的测试用例。
     * Link bugs test.
     *
     * @param  int $caseID
     * @param  array $toLinkBugs
     * @access public
     * @return array
     */
    public function linkBugsTest(int $caseID, array $toLinkBugs): array
    {
        $oldCase = $this->objectModel->getByID($caseID);

        $case = new stdclass();
        $case->linkBug      = $toLinkBugs;
        $case->version      = $oldCase->version + 1;
        $case->story        = 0;
        $case->storyVersion = 0;

        $this->objectModel->linkBugs($caseID, array_keys($oldCase->toBugs), $case);

        if(dao::isError()) return dao::getError();

        global $tester;
        $bugs = $tester->dao->select('id,`case`')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        return $bugs;
    }

    /**
     * Test get status for different method.
     *
     * @param  string $methodName
     * @param  object $case
     * @param  array  $param
     * @access public
     * @return array
     */
    public function getStatusTest($methodName, $case = null, $param = array())
    {
        if($methodName == 'update')
        {
            $case = $this->objectModel->getByID(1);
            $_POST['title']          = $case->title;
            $_POST['color']          = $case->color;
            $_POST['precondition']   = $case->precondition;
            $_POST['steps']          = array('用例步骤描述1');
            $_POST['stepType']       = array('step');
            $_POST['expects']        = array('这是用例预期结果1');
            $_POST['comment']        = '';
            $_POST['labels']         = '';
            $_POST['lastEditedDate'] = $case->lastEditedDate;
            $_POST['product']        = $case->product;
            $_POST['module']         = $case->module;
            $_POST['story']          = $case->story;
            $_POST['type']           = $case->type;
            $_POST['stage']          = $case->stage;
            $_POST['pri']            = $case->pri;
            $_POST['status']         = $case->status;
            $_POST['keywords']       = $case->keywords;

            foreach($param as $field => $value) $_POST[$field] = $value;
        }

        $objects = $this->objectModel->getStatus($methodName, $case);

        unset($_POST);

        if(dao::isError()) return dao::getError()[0];

        return $objects;
    }

    /**
     * 测试添加步骤。
     * Test append steps.
     *
     * @param  array  $steps
     * @param  int    $count
     * @access public
     * @return array
     */
    public function appendStepsTest(array $steps, int $count = 0)
    {
        $objects = $this->objectModel->appendSteps($steps, $count);

        return count($objects);
    }

    /**
     * 测试插入步骤。
     * Test insert steps.
     *
     * @param  int    $caseID
     * @param  array  $steps
     * @param  array  $expects
     * @param  array  $stepTypes
     * @access public
     * @return string
     */
    public function insertStepsTest(int $caseID, array $steps, array $expects, array $stepTypes): string
    {
        $objects = $this->objectModel->insertSteps($caseID, $steps, $expects, $stepTypes);
        if(dao::isError()) return dao::getError()[0];
        global $tester;
        $steps  = $tester->dao->select('id')->from(TABLE_CASESTEP)->where('case')->eq($caseID)->fetchAll('id');
        return implode(',', array_keys($steps));
    }

    /**
     * 测试获取步骤。
     * Test get steps.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return string
     */
    public function getStepsTest(int $caseID, int $version): string
    {
        $steps = $this->objectModel->getSteps($caseID, $version);
        if(dao::isError()) return dao::getError()[0];
        $return = '';
        foreach($steps as $step) $return .= "{$step->name} ";
        return trim($return, ' ');
    }

    /**
     * 获取用例基本信息。
     * Fetch base info of a case.
     *
     * @param  int    $caseID
     * @access public
     * @return object|bool
     */
    public function fetchBaseInfoTest(int $caseID): object|bool
    {
        return $this->objectModel->fetchBaseInfo($caseID);
    }

    /**
     * 测试获取步骤。
     * Test fetch steps by id list.
     *
     * @param  array  $caseIdList
     * @access public
     * @return string
     */
    public function fetchStepsByListTest(array $caseIdList): string
    {
        $caseSteps = $this->objectModel->fetchStepsByList($caseIdList);
        if(dao::isError()) return dao::getError()[0];
        $return = '';
        foreach($caseSteps as $caseID => $steps)
        {
            $return .= "{$caseID}: ";
            foreach($steps as $step) $return .= "{$step->id},";
            $return = trim($return, ',');
            $return .= '; ';
        }
        return trim($return, ' ');
    }

    /**
     * 测试插入步骤。
     * Test insert steps.
     *
     * @param  array  $caseIdList
     * @access public
     * @return string
     */
    public function importStepsTest(int $caseID, int $oldCaseID): string
    {
        global $tester;
        $steps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($oldCaseID)->fetchAll('id');

        $this->objectModel->importSteps($caseID, $steps);

        if(dao::isError()) return dao::getError()[0];

        $return    = '';
        $steps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->fetchAll('id');
        foreach($steps as $step) $return .= "{$step->id},";
        return trim($return, ',');
    }

    /**
     * 测试插入文件。
     * Test insert files.
     *
     * @param  array  $caseIdList
     * @access public
     * @return string
     */
    public function importFilesTest(int $caseID, int $oldCaseID): string
    {
        global $tester;
        $files = $tester->dao->select('*')->from(TABLE_FILE)->where('`objectID`')->eq($oldCaseID)->andWhere('objectType')->eq('testcase')->fetchAll('id');

        $this->objectModel->importFiles($caseID, $files);

        if(dao::isError()) return dao::getError()[0];

        $return    = '';
        $files = $tester->dao->select('*')->from(TABLE_FILE)->where('`objectID`')->eq($caseID)->andWhere('objectType')->eq('testcase')->fetchAll('id');
        foreach($files as $file) $return .= "{$file->id},";
        return trim($return, ',');
    }

    /**
     * 测试创建一个用例。
     * Test create a case.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function doCreateTest($param)
    {
        $case = new stdclass();
        $case->product      = 1;
        $case->module       = 1821;
        $case->type         = 'feature';
        $case->stage        = ',unittest';
        $case->story        = 4;
        $case->color        = '';
        $case->pri          = 3;
        $case->precondition = '前置条件';
        $case->steps        = array('1' => '1','1.1' => '1.1', '1.2' => '1.2', '2' => '2', '3' => '3', '4' => '');
        $case->stepType     = array('1' => 'group','1.1' => 'item', '1.2' => 'item', '2' => 'step', '3' => 'item', '4' => 'step');
        $case->expects      = array('1' => '','1.1' => '', '1.2' => '', '2' => '', '3' => '', '4' => '');
        $case->keywords     = '关键词1,关键词2';
        $case->status       = 'normal';

        foreach($param as $field => $value) $case->{$field} = $value;

        $this->objectModel->doCreate($case);

        unset($_POST);

        if(dao::isError()) return isset($param['type']) ? dao::getError()['type'][0] : dao::getError()['title'][0];

        global $tester;
        $caseID = $tester->dao->lastInsertID();
        return $this->objectModel->fetchBaseInfo($caseID);
    }
}
