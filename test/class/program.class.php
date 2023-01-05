<?php
class programTest
{
    /**
     * __construct
     *
     * @param  mixed $user
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $tester;

        $this->program = $tester->loadModel('program');
    }

    /**
     * Test create program.
     *
     * @param  array $data
     * @access public
     * @return object
     */
    public function createTest($data)
    {
        $_POST = $data;

        $programID = $this->program->create();

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getByID($programID);

        return $program;
    }

    /**
     * Create stakeholder.
     *
     * @param  int    $programID
     * @param  array  $accounts
     * @access public
     * @return void
     */
    public function createStakeholderTest($programID, $accounts = array())
    {
        $_POST['accounts'] = $accounts;
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }

    /**
     * Create default program.
     *
     * @access public
     * @return void
     */
    public function createDefaultProgramTest()
    {
        $programID = $this->program->createDefaultProgram();

        return $programID > 0;
    }

    /**
     * Test get list.
     *
     * @param  mixed  $status
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $type       top|child
     * @param  mixed  $idList
     * @access public
     * @return void
     */
    public function getList($status = 'all', $orderBy = 'id_asc', $pager = NULL, $type = '', $idList = '')
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status, $orderBy, $pager, $type, $idList);

        if(dao::isError()) return array('message' => dao::getError());

        return $programs;
    }


    /**
     * Test update program.
     *
     * @param  mixed  $proguamID
     * @param  mixed  $data
     * @access public
     * @return void
     */
    public function updateTest($programID, $data)
    {
        $_POST = $data;
        $this->program->update($programID);
        if(dao::isError()) return array('message' => dao::getError());

        return $this->program->getByID($programID);
    }

    /**
     * Get program by id.
     *
     * @param  int    $proguamID
     * @access public
     * @return object
     */
    public function getByIDTest($programID)
    {
        $program = $this->program->getByID($programID);
        if(dao::isError()) return array('message' => dao::getError());

        return $program;
    }

    /**
     * Get budget left.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getBudgetLeftTest($programID)
    {
        $program = $this->program->getByID($programID);
        $budget  = $this->program->getBudgetLeft($program);

        if(dao::isError()) return array('message' => dao::getError());

        return $budget;
    }

    /**
     * Set tree path.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function setTreePathTest($programID)
    {
        $this->program->setTreePath($programID);
        $program = $this->program->getByID($programID);

        return $program;
    }

    /**
     * Get children.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getChildrenTest($programID)
    {
        return $this->program->getChildren($programID);
    }

    /**
     * Check clickable.
     *
     * @param  int    $programID
     * @param  string $status
     * @access public
     * @return int
     */
    public function isClickableTest($programID, $status)
    {
        return $this->program->isClickable($programID, $status);
    }

    /**
     * Has unfinished.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function hasUnfinishedTest($programID)
    {
        $program = $this->program->getByID($programID);
        return $this->program->hasUnfinished($program);
    }

    /*
     * get involved programs.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getInvolvedProgramsTest($account)
    {
        return $this->program->getInvolvedPrograms($account);
    }

    /*
     * Get Tree menu.
     *
     * @param  string $programID
     * @access public
     * @return array
     */
    public function getTreeMenuTest($programID)
    {
        return $this->program->getTreeMenu($programID);
    }

    /**
     * Get top pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @param  bool   $isQueryAll
     * @access public
     * @return void
     */
    public function getTopPairsTest($model = '', $mode = '', $isQueryAll = false)
    {
        return $this->program->getTopPairs($model, $mode, $isQueryAll);
    }

    /**
     * Get kanban group.
     *
     * @access public
     * @return array
     */
    public function getKanbanGroupTest()
    {
        return $this->program->getKanbanGroup();
    }
}
