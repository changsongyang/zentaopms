<?php
/**
 * The model file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class groupModel extends model
{
    /**
     * Create a group.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $group = fixer::input('post')->get();
        if(isset($group->limited))
        {
            unset($group->limited);
            $group->role = 'limited';
        }
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)->batchCheck($this->config->group->create->requiredFields, 'notempty')->check('name', 'unique')->exec();
        $groupID = $this->dao->lastInsertId();

        $data         = new stdclass();
        $data->group  = $groupID;
        $data->module = 'index';
        $data->method = 'index';
        $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

        return $groupID;
    }

    /**
     * Update a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function update($groupID)
    {
        $group = fixer::input('post')->get();
        $this->lang->error->unique = $this->lang->group->repeat;
        return $this->dao->update(TABLE_GROUP)->data($group)->batchCheck($this->config->group->edit->requiredFields, 'notempty')->check('name', 'unique', "id != {$groupID}")->where('id')->eq($groupID)->exec();
    }

    /**
     * Copy a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
        $group = fixer::input('post')->remove('options')->get();
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)->check('name', 'unique')->check('name', 'notempty')->exec();
        if($this->post->options == false) return;
        if(!dao::isError())
        {
            $newGroupID = $this->dao->lastInsertID();
            $options    = join(',', $this->post->options);
            if(strpos($options, 'copyPriv') !== false) $this->copyPriv($groupID, $newGroupID);
            if(strpos($options, 'copyUser') !== false) $this->copyUser($groupID, $newGroupID);
        }
    }

    /**
     * Copy privileges.
     *
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyPriv($fromGroup, $toGroup)
    {
        $privs = $this->dao->findByGroup($fromGroup)->from(TABLE_GROUPPRIV)->fetchAll();
        foreach($privs as $key => $priv)
        {
            $privs[$key]->group = $toGroup;
        }
        $this->insertPrivs($privs);
    }

    /**
     * Copy user.
     *
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyUser($fromGroup, $toGroup)
    {
        $users = $this->dao->findByGroup($fromGroup)->from(TABLE_USERGROUP)->fetchAll();
        foreach($users as $user)
        {
            $user->group = $toGroup;
            $this->dao->insert(TABLE_USERGROUP)->data($user)->exec();
        }
    }

    /**
     * Get group lists.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getList($projectID = 0)
    {
        return $this->dao->select('*')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * Get group pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairs($projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('id')->fetchPairs();
    }

    /**
     * Get group by id.
     *
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function getByID($groupID)
    {
        $group = $this->dao->findById($groupID)->from(TABLE_GROUP)->fetch();
        if($group->acl) $group->acl = json_decode($group->acl, true);
        if(!isset($group->acl) || !is_array($group->acl)) $group->acl = array();
        return $group;
    }

    /**
     * Get group by account.
     *
     * @param  string    $account
     * @param  bool      $allVision
     * @access public
     * @return array
     */
    public function getByAccount($account, $allVision = false)
    {
        return $this->dao->select('t2.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t2.project')->eq(0)
            ->beginIF(!$allVision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->fetchAll('id');
    }

    /**
     * Get groups by accounts.
     *
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getByAccounts($accounts)
    {
        return $this->dao->select('t1.account, t2.acl, t2.id')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t1.account')->in($accounts)
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->fetchGroup('account');
    }

    /**
     * Get the account number in the group.
     *
     * @param  array  $groupIdList
     * @access public
     * @return array
     */
    public function getGroupAccounts($groupIdList = array())
    {
        $groupIdList = array_filter($groupIdList);
        if(empty($groupIdList)) return array();
        return $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groupIdList)->fetchPairs('account');
    }

    /**
     * Get privileges of a groups.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivs($groupID)
    {
        $privs = array();
        $stmt  = $this->dao->select('module, method')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->orderBy('module')->query();
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }

    /**
     * Get user pairs of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getUserPairs($groupID)
    {
        return $this->dao->select('t2.account, t2.realname')
            ->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('`group`')->eq((int)$groupID)
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t2.account')
            ->fetchPairs();
    }

    /**
     * Get object for manage admin group.
     *
     * @access public
     * @return void
     */
    public function getObject4AdminGroup()
    {
        $objects = $this->dao->select('id, name, path, type, project, grade, parent')->from(TABLE_PROJECT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('type')->ne('program')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $productList = $this->dao->select('id, name, program')->from(TABLE_PRODUCT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        /* Get the list of program sets under administrator permission. */
        if(!$this->app->user->admin)
        {
            $this->app->user->admin = true;
            $changeAdmin            = true;
        }
        $programs = $this->loadModel('program')->getParentPairs('', '', false);
        if(!empty($changeAdmin)) $this->app->user->admin = false;

        $projects   = array();
        $executions = array();
        $products   = array();
        foreach($objects as $object)
        {
            $type  = $object->type;
            $path  = explode(',', trim($object->path, ','));
            $topID = $path[0];

            if($type == 'project')
            {
                if($topID != $object->id) $object->name = isset($objects[$topID]) ? $objects[$topID]->name . '/' . $object->name : $object->name;
                $projects[$object->id] = $object->name;
            }
            else
            {
                if($object->grade == 2)
                {
                    unset($objects[$object->parent]);
                    unset($executions[$object->parent]);
                }

                $object->name = isset($objects[$object->project]) ? $objects[$object->project]->name . '/' . $object->name : $object->name;
                $executions[$object->id] = $object->name;
            }
        }

        foreach($productList as $id => $product)
        {
            if(isset($programs[$product->program]) and $this->config->systemMode == 'ALM') $product->name = $programs[$product->program] . '/' . $product->name;
            $products[$product->id] = $product->name;
        }

        return array($programs, $projects, $products, $executions);
    }

    /**
     * Get project admins for manage project admin.
     *
     * @access public
     * @return array
     */
    public function getProjectAdmins()
    {
        $admins = $this->dao->select('*')->from(TABLE_PROJECTADMIN)->fetchGroup('group', 'account');

        $projectAdmins = array();
        foreach($admins as $groupID => $adminGroup)
        {
            if(!empty($adminGroup))
            {
                $accounts = implode(',', array_keys($adminGroup));
                $projectAdmins[$accounts] = current($adminGroup);
            }
        }

        return $projectAdmins;
    }

    /**
     * Get admins by object id list.
     *
     * @param  int    $idList
     * @param  string $field
     * @access public
     * @return void
     */
    public function getAdmins($idList, $field = 'programs')
    {
        $objects = array();
        foreach($idList as $id)
        {
            $objects[$id] = $this->dao->select('DISTINCT account')->from(TABLE_PROJECTADMIN)
                ->where("CONCAT(',', $field, ',')")->like("%$id%")
                ->orWhere($field)->eq('all')
                ->fetchPairs();
        }

        return $objects;
    }

    /**
     * Get the ID of the group that has access to the program.
     *
     * @access public
     * @return array
     */
    public function getAccessProgramGroup()
    {
        $accessibleGroup   = $this->getList();
        $accessibleGroupID = array(0);
        foreach($accessibleGroup as $group)
        {
            if($group->acl) $group->acl = json_decode($group->acl, true);
            if(!isset($group->acl) || !is_array($group->acl)) $group->acl = array();

            if(empty($group->acl))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }

            if(!isset($group->acl['views']) || empty($group->acl['views']))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }

            if(in_array('program', $group->acl['views']))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }
        }
        return $accessibleGroupID;
    }

    /**
     * Delete a group.
     *
     * @param  int    $groupID
     * @param  null   $null      compatible with that of model::delete()
     * @access public
     * @return void
     */
    public function delete($groupID, $null = null)
    {
        $this->dao->delete()->from(TABLE_GROUP)->where('id')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();
    }

    /**
     * Update privilege of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return bool
     */
    public function updatePrivByGroup($groupID, $menu, $version)
    {
        /* Delete old. */
        /* Set priv when have version. */
        if($version)
        {
            $noCheckeds = trim($this->post->noChecked, ',');
            if($noCheckeds)
            {
                $noCheckeds = explode(',', $noCheckeds);
                foreach($noCheckeds as $noChecked)
                {
                    /* Delete no checked priv*/
                    list($module, $method) = explode('-', $noChecked);
                    $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->andWhere('module')->eq($module)->andWhere('method')->eq($method)->exec();
                }
            }
        }
        else
        {
            $privs = !empty($menu) ? $this->getPrivsListByView($menu) : array();
            $privs = !empty($menu) ? $this->getCustomPrivs($menu, $privs) : array();
            if(!empty($privs) or empty($menu))
            {
                $this->dao->delete()->from(TABLE_GROUPPRIV)
                    ->where('`group`')->eq($groupID)
                    ->beginIF(!empty($menu))->andWhere("CONCAT(module, '-', method)")->in(array_keys($privs))->fi()
                    ->exec();
            }
        }

        $data         = new stdclass();
        $data->group  = $groupID;
        $data->module = 'index';
        $data->method = 'index';
        $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

        /* Insert new. */
        if($this->post->actions)
        {
            $depentedPrivs = array();
            $privs         = array();
            foreach($this->post->actions as $moduleName => $moduleActions)
            {
                if(empty($moduleName) or empty($moduleActions)) continue;
                $privIdList    = $this->dao->select('id')->from(TABLE_PRIV)->where('module')->eq($moduleName)->andWhere('method')->in($moduleActions)->fetchPairs();
                $relationPrivs = $this->getPrivRelationsByIdList($privIdList, 'depend', 'idGroup');
                $depentedPrivs = array_merge($depentedPrivs, array_keys(zget($relationPrivs, 'depend', array())));
                foreach($moduleActions as $actionName)
                {
                    $data         = new stdclass();
                    $data->group  = $groupID;
                    $data->module = $moduleName;
                    $data->method = $actionName;
                    $privs[]      = $data;
                }
            }
            $this->insertPrivs($privs);
            $depentedPrivs = $this->getPrivByIdList($depentedPrivs);
            foreach($depentedPrivs as $privID => $priv)
            {
                if(!empty($_POST['actions'][$priv->module]) and in_array($priv->method, $_POST['actions'][$priv->module]))
                {
                    unset($depentedPrivs[$privID]);
                    continue;
                }
                $data         = new stdclass();
                $data->group  = $groupID;
                $data->module = $priv->module;
                $data->method = $priv->method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
            $recommendPrivs = $this->getPrivByIdList(zget($_POST, 'recommendPrivs', '0'));
            foreach($recommendPrivs as $privID => $priv)
            {
                if(in_array($priv->method, zget($_POST['actions'], $priv->module, array())))
                {
                    unset($recommendPrivs[$privID]);
                    continue;
                }
                $data         = new stdclass();
                $data->group  = $groupID;
                $data->module = $priv->module;
                $data->method = $priv->method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return !empty($depentedPrivs) ? true : false;
    }

    /**
     * Insert privs.
     *
     * @param  array $privs
     * @access protected
     * @return bool
     */
    protected function insertPrivs($privs)
    {
        $groups = array();
        foreach($privs as $priv) $groups[$priv->group] = $priv->group;

        $privMap  = array();
        $privList = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('group')->in($groups)->fetchAll();
        foreach($privList as $priv) $privMap[$priv->group . '-' . $priv->module . '-' . $priv->method] = true;

        foreach($privs as $priv)
        {
            if(!isset($privMap[$priv->group . '-' . $priv->module . '-' . $priv->method]))
            {
                $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
            }
        }

        return true;
    }

    /**
     * Update view priv.
     *
     * @param  int    $groupID
     * @access public
     * @return bool
     */
    public function updateView($groupID)
    {
        $actions  = $this->post->actions;
        $oldGroup = $this->getByID($groupID);
        $projects = isset($actions['projects']) ? $actions['projects'] : array();
        $sprints  = isset($actions['sprints'])  ? $actions['sprints']  : array();

        /* Add shadow productID when select noProduct project or execution. */
        if(($projects or $sprints) and isset($actions['products']))
        {
            /* Get all noProduct projects and executions . */
            $noProductList       = $this->loadModel('project')->getNoProductList();
            $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();
            $noProductObjects    = array_merge($projects, $sprints);

            foreach($noProductObjects as $objectID)
            {
                if(isset($noProductList[$objectID])) $actions['products'][] = $noProductList[$objectID]->product;
            }
        }

        if(isset($_POST['allchecker']))$actions['views']   = array();
        if(!isset($actions['actions']))$actions['actions'] = array();

        if(isset($actions['actions']['project']['started']))   $actions['actions']['project']['syncproject'] = 'syncproject';
        if(isset($actions['actions']['execution']['started'])) $actions['actions']['execution']['syncexecution'] = 'syncexecution';

        $dynamic = $actions['actions'];
        if(!isset($_POST['allchecker']))
        {
            $dynamic = array();
            foreach($actions['actions'] as $moduleName => $moduleActions)
            {
                $groupName = $moduleName;
                if(isset($this->lang->navGroup->$moduleName)) $groupName = $this->lang->navGroup->$moduleName;
                if($moduleName == 'case') $groupName = $this->lang->navGroup->testcase;
                if($groupName != 'my' and isset($actions['views']) and !in_array($groupName, $actions['views'])) continue;

                $dynamic[$moduleName] = $moduleActions;
            }
        }
        $actions['actions'] = $dynamic;

        $actions = empty($actions) ? '' : json_encode($actions);
        $this->dao->update(TABLE_GROUP)->set('acl')->eq($actions)->where('id')->eq($groupID)->exec();
        return dao::isError() ? false : true;
    }

    /**
     * Update privilege by module.
     *
     * @access public
     * @return void
     */
    public function updatePrivByModule()
    {
        if($this->post->module == false or $this->post->actions == false or $this->post->groups == false) return false;

        $privs = array();
        foreach($this->post->actions as $action)
        {
            list($module, $method) = explode('-', $action);
            foreach($this->post->groups as $group)
            {
                $data         = new stdclass();
                $data->group  = $group;
                $data->module = $module;
                $data->method = $method;
                $privs[]      = $data;
            }
        }

        return $this->insertPrivs($privs);
    }

    /**
     * Update users.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateUser($groupID)
    {
        $members    = $this->post->members ? $this->post->members : array();
        $groupUsers = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->fetchPairs('account');
        $newUsers   = array_diff($members, $groupUsers);
        $delUsers   = array_diff($groupUsers, $members);

        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->andWhere('account')->in($delUsers)->exec();

        if($newUsers)
        {
            foreach($newUsers as $account)
            {
                $data          = new stdclass();
                $data->account = $account;
                $data->group   = $groupID;
                $data->project = '';
                $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
            }
        }

        /* Update whitelist. */
        $acl = $this->dao->select('acl')->from(TABLE_GROUP)->where('id')->eq($groupID)->fetch('acl');
        $acl = json_decode($acl);

        /* Adjust user view. */
        $changedUsers = array_merge($newUsers, $delUsers);
        if(!empty($changedUsers))
        {
            $this->loadModel('user');
            foreach($changedUsers as $account) $this->user->computeUserView($account, true);
        }
    }

    /**
     * Update project admins.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateProjectAdmin($groupID)
    {
        $this->loadModel('user');

        $allUsers = $this->dao->select('account')->from(TABLE_PROJECTADMIN)->fetchPairs();
        $this->dao->delete()->from(TABLE_PROJECTADMIN)->exec();

        $members       = $this->post->members      ? $this->post->members      : array();
        $programs      = $this->post->program      ? $this->post->program      : array();
        $projects      = $this->post->project      ? $this->post->project      : array();
        $products      = $this->post->product      ? $this->post->product      : array();
        $executions    = $this->post->execution    ? $this->post->execution    : array();
        $programAll    = $this->post->programAll   ? $this->post->programAll   : '';
        $projectAll    = $this->post->projectAll   ? $this->post->projectAll   : '';
        $productAll    = $this->post->productAll   ? $this->post->productAll   : '';
        $executionAll  = $this->post->executionAll ? $this->post->executionAll : '';
        $noProductList = $this->loadModel('project')->getNoProductList();
        $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();

        foreach($members as $lineID => $accounts)
        {
            $programs[$lineID]   = isset($programs[$lineID])   ? $programs[$lineID]   : array();
            $projects[$lineID]   = isset($projects[$lineID])   ? $projects[$lineID]   : array();
            $products[$lineID]   = isset($products[$lineID])   ? $products[$lineID]   : array();
            $executions[$lineID] = isset($executions[$lineID]) ? $executions[$lineID] : array();

            if(($projects[$lineID] or $executions[$lineID]) and !empty($products[$lineID]))
            {
                $objects = array_merge($projects[$lineID], $executions[$lineID]);
                foreach($objects as $objectID)
                {
                    if(isset($noProductList[$objectID])) $products[$lineID][] = $noProductList[$objectID]->product;
                }
            }

            if($executionAll[$lineID] or $projectAll[$lineID]) $products[$lineID] = array_merge($products[$lineID], $shadowProductIDList);

            if(empty($accounts)) continue;
            foreach($accounts as $account)
            {
                $program   = isset($programAll[$lineID])   ? 'all' : implode(',', $programs[$lineID]);
                $project   = isset($projectAll[$lineID])   ? 'all' : implode(',', $projects[$lineID]);
                $product   = isset($productAll[$lineID])   ? 'all' : implode(',', $products[$lineID]);
                $execution = isset($executionAll[$lineID]) ? 'all' : implode(',', $executions[$lineID]);

                $data = new stdclass();
                $data->group      = $lineID;
                $data->account    = $account;
                $data->programs   = $program;
                $data->projects   = $project;
                $data->products   = $product;
                $data->executions = $execution;

                $this->dao->replace(TABLE_PROJECTADMIN)->data($data)->exec();

                $allUsers[$account] = $account;
            }
        }

        foreach($allUsers as $account)
        {
            if(!$account) continue;
            $this->user->computeUserView($account, true);
        }

        if(!dao::isError()) return true;
        return false;
    }

    /**
     * Sort resource.
     *
     * @access public
     * @return void
     */
    public function sortResource()
    {
        $resources = $this->lang->resource;
        $this->lang->resource = new stdclass();

        /* sort moduleOrder. */
        ksort($this->lang->moduleOrder, SORT_ASC);
        foreach($this->lang->moduleOrder as $moduleName)
        {
            if(!isset($resources->$moduleName)) continue;

            $resource = $resources->$moduleName;
            unset($resources->$moduleName);
            $this->lang->resource->$moduleName = $resource;
        }
        foreach($resources as $key => $resource)
        {
            $this->lang->resource->$key = $resource;
        }

        /* sort methodOrder. */
        foreach($this->lang->resource as $moduleName => $resources)
        {
            $resources    = (array)$resources;
            $tmpResources = new stdclass();

            if(isset($this->lang->$moduleName->methodOrder))
            {
                ksort($this->lang->$moduleName->methodOrder, SORT_ASC);
                foreach($this->lang->$moduleName->methodOrder as $key)
                {
                    if(isset($resources[$key]))
                    {
                        $tmpResources->$key = $resources[$key];
                        unset($resources[$key]);
                    }
                }
                if($resources)
                {
                    foreach($resources as $key => $resource)
                    {
                        $tmpResources->$key = $resource;
                    }
                }
                $this->lang->resource->$moduleName = $tmpResources;
                unset($tmpResources);
            }
        }
    }

    /**
     * Check menu have module
     *
     * @param  string    $menu
     * @param  string    $moduleName
     * @access public
     * @return void
     */
    public function checkMenuModule($menu, $moduleName)
    {
        if(empty($menu)) return true;
        if($menu == 'general' and (isset($this->lang->navGroup->$moduleName) or isset($this->lang->mainNav->$moduleName))) return false;
        if($menu != 'general' and !($moduleName == $menu or (isset($this->lang->navGroup->$moduleName) and $this->lang->navGroup->$moduleName == $menu))) return false;
        if($menu == 'project' and strpos('caselib|testsuite|report', $moduleName) !== false) return false;
        return true;
    }

    /**
     * Get modules in menu
     *
     * @param  string  $menu
     * @param  bool    $translateLang
     * @access public
     * @return array
     */
    public function getMenuModules($menu = '', $translateLang = false)
    {
        $modules = array();
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->checkMenuModule($menu, $moduleName))
            {
                $modules[$moduleName] = $moduleName;
                if($translateLang)
                {
                    if(!isset($this->lang->{$moduleName}->common)) $this->app->loadLang($moduleName);
                    $modules[$moduleName] = isset($this->lang->{$moduleName}->common) ? $this->lang->{$moduleName}->common : $moduleName;
                    if($moduleName == 'requirement') $modules[$moduleName] = $this->lang->URCommon;
                }
            }
        }
        return $modules;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object $group
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($group, $action)
    {
        $action = strtolower($action);

        if($action == 'manageview' && $group->role == 'limited') return false;
        if($action == 'copy' && $group->role == 'limited') return false;
        if($group->role == 'projectAdmin' && in_array($action, array('manageview', 'managepriv', 'managemember', 'edit', 'copy'))) return false;
        if($group->role != 'projectAdmin' && $action == 'manageprojectadmin') return false;

        return true;
    }

    /**
     * Create a privilege package.
     *
     * @access public
     * @return int
     */
    public function createPrivPackage()
    {
        if(empty($_POST['value'])) dao::$errors['value'] = sprintf($this->lang->error->notempty, $this->lang->privpackage->name);
        if(empty($_POST['parent'])) dao::$errors['parent'] = sprintf($this->lang->error->notempty, $this->lang->privpackage->module);
        if(dao::isError()) return false;

        $module = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('code')->eq($_POST['parent'])->andWhere('type')->eq('module')->fetch('id');
        $package = fixer::input('post')
            ->add('type', 'package')
            ->add('parent', $module)
            ->remove('value,desc')
            ->get();
        $packages = $this->getPrivPackagesByModule($package->parent);
        $package->order = (count($packages) + 1) * 5;

        $this->dao->insert(TABLE_PRIVMANAGER)->data($package)->exec();
        $packageID = $this->dao->lastInsertId();

        $packageLang = fixer::input('post')
            ->add('objectID', $packageID)
            ->add('objectType', 'manager')
            ->add('lang', $this->app->getClientLang())
            ->remove('parent')
            ->get();

        $this->dao->insert(TABLE_PRIVLANG)->data($packageLang)->exec();

        $this->loadModel('action')->create('privpackage', $packageID, 'Opened');
        return $packageID;
    }

    /**
     * Update a privilege package.
     *
     * @param  int    $packageID
     * @access public
     * @return array
     */
    public function updatePrivPackage($packageID)
    {
        $oldPackage = $this->getPrivPackageByID($packageID);

        if(empty($_POST['value'])) dao::$errors['value'] = sprintf($this->lang->error->notempty, $this->lang->privpackage->name);
        if(empty($_POST['parent'])) dao::$errors['parent'] = sprintf($this->lang->error->notempty, $this->lang->privpackage->module);
        if(dao::isError()) return false;

        $module = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('code')->eq($_POST['parent'])->andWhere('type')->eq('module')->fetch('id');

        $package = fixer::input('post')
            ->add('parent', $module)
            ->remove('value,desc')
            ->get();

        if($oldPackage->parent != $package->parent)
        {
            $packages = $this->getPrivPackagesByModule($package->parent);
            $package->order = (count($packages) + 1) * 5;
        }

        $this->dao->update(TABLE_PRIVMANAGER)->data($package)->where('id')->eq($packageID)->exec();

        $packageLang = fixer::input('post')
            ->remove('parent')
            ->get();

        $this->dao->update(TABLE_PRIVLANG)->data($packageLang)->where('objectID')->eq($packageID)->andWhere('objectType')->eq('manager')->andWhere('lang')->eq($this->app->getClientLang())->exec();

        if(dao::isError()) return false;

        $package = $this->getPrivPackageByID($packageID);
        $changes = common::createChanges($oldPackage, $package);

        return $changes;
    }

    /**
     * Delete a priv package.
     *
     * @param  int    $packageID
     * @access public
     * @return bool
     */
    public function deletePrivPackage($packageID, $moduleID)
    {
        $this->dao->delete()->from(TABLE_PRIVMANAGER)->where('id')->eq($packageID)->exec();
        $this->dao->delete()->from(TABLE_PRIVLANG)->where('objectID')->eq($packageID)->andWhere('objectType')->eq('manager')->exec();
        if(dao::isError()) return false;
        $this->dao->update(TABLE_PRIV)->set('parent')->eq($moduleID)->where('parent')->eq($packageID)->exec();
    }

    /**
     * Get priv package by id.
     *
     * @param  int    $packageID
     * @access public
     * @return object
     */
    public function getPrivPackageByID($packageID)
    {
        return $this->dao->select('distinct t1.*,t2.`value` as name, t2.desc')->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.id')->eq($packageID)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.objectType')->eq('manager')
            ->fetch();
    }

    /**
     * Get priv packages by module.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPrivPackagesByModule($module)
    {
        $moduleID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('`code`')->eq($module)->andWhere('type')->eq('module')->fetch('id');

        return $this->dao->select('t1.*,t2.value as name, t2.desc')->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.parent')->eq($moduleID)
            ->andWhere('t1.type')->eq('package')
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

//    /**
//     * Get priv packages group by module.
//     *
//     * @param  array  $modules
//     * @access public
//     * @return array
//     */
//    public function getPrivPackageGroupByModules($modules = array())
//    {
//        return $this->dao->select('t1.*,t2.`code` as parentCode')->from(TABLE_PRIVMANAGER)->alias('t1')
//            ->leftJoin(TABLE_PRIVMANAGER)->alias('t2')->on('t1.parent=t2.id')
//            ->where('t1.`type`')->eq('package')
//            ->beginIF(!empty($modules))->andWhere('t2.`code`')->in($modules)->fi()
//            ->andWhere('t2.`type`')->eq('module')
//            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
//            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
//            ->fetchGroup('parentCode', 'id');
//    }

    /**
     * Get priv package pairs by view.
     *
     * @access public
     * @return array
     */
    public function getPrivPackagePairs($view = '', $module = '', $field = '`value` as name')
    {
        $modules = '';
        if(!empty($module))
        {
            $modules = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('type')->eq('module')->andWhere('code')->eq($module)->fetch('id');
        }
        else
        {
            $modules = $this->getPrivManagerPairs('module', $view);
            $modules = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('type')->eq('module')->andWhere('code')->in(array_keys($modules))->fetchPairs('id');
        }

        return $this->dao->select("t1.id, $field")
            ->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->where('1=1')
            ->andWhere('t2.objectType')->eq('manager')
            ->andWhere('t1.type')->eq('package')
            ->beginIF(!empty($modules))->andWhere('t1.parent')->in($modules)->fi()
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t3.type')->eq('module')
            ->orderBy('t1.order_asc')
            ->fetchPairs();
    }

    /**
     * Get priv modules.
     *
     * @param  string $viewName
     * @param  string $param
     * @access public
     * @return array
     */
    public function getPrivModules($viewName = '', $param = '')
    {
        $this->loadModel('setting');

        $tree       = array();
        $views      = empty($viewName) ? $this->setting->getItem("owner=system&module=priv&key=views") : $viewName;
        $views      = explode(',', $views);
        $modules    = array();
        $moduleLang = $this->getMenuModules('', true);
        foreach($views as $view)
        {
            $viewModules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
            if(empty($viewModules)) continue;

            $viewModules = explode(',', $viewModules);
            foreach($viewModules as $index => $module)
            {
                $modules[$module] = $param == 'noViewName' ? zget($moduleLang, $module) : $this->lang->{$view}->common . '/' . zget($moduleLang, $module);
                unset($viewModules[$index]);
            }
        }

        return $modules;
    }

    /**
     * Get priv module view pairs.
     *
     * @access public
     * @return void
     */
    public function getPrivModuleViewPairs()
    {
        $this->loadModel('setting');

        $views = $this->setting->getItem("owner=system&module=priv&key=views");
        if(empty($views)) return array();
        $views = explode(',', $views);

        $pairs = array();
        foreach($views as $viewIndex => $view)
        {
            $viewModules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
            $viewModules = explode(',', $viewModules);

            foreach($viewModules as $module) $pairs[$module] = $view;
        }

        return $pairs;
    }

    /**
     * Get priv package tree list.
     *
     * @access public
     * @return void
     */
    public function getPrivPackageTreeList()
    {
        $this->loadModel('setting');

        $views = $this->getPrivManagers('view');

        $tree = array();
        foreach($views as $viewID => $view)
        {
            if(empty($view->name)) continue;

            $treeView         = new stdclass();
            $treeView->id     = $viewID;
            $treeView->type   = 'view';
            $treeView->name   = $view->name;
            $treeView->parent = 0;
            $treeView->path   = ",{$viewID},";
            $treeView->grade  = 1;
            $treeView->order  = $view->order;
            $treeView->desc   = '';
            $tree[$viewID]    = $treeView;

            $viewModules = $this->getPrivManagers('module', $view->code);
            if(empty($viewModules)) continue;

            foreach($viewModules as $moduleID => $module)
            {
                $treeModule         = new stdclass();
                $treeModule->id     = $moduleID;
                $treeModule->type   = 'module';
                $treeModule->name   = $module->name;
                $treeModule->parent = $viewID;
                $treeModule->path   = ",{$viewID},{$moduleID},";
                $treeModule->grade  = 2;
                $treeModule->order  = $moduleID;
                $treeModule->desc   = '';
                $tree[$moduleID]    = $treeModule;

                $packages = $this->getPrivManagers('package', $module->code);
                foreach($packages as $packageID => $package)
                {
                    $treePackage = new stdclass();
                    $treePackage->id     = $packageID;
                    $treePackage->type   = 'package';
                    $treePackage->name   = $package->name;
                    $treePackage->parent = $moduleID;
                    $treePackage->path   = ",{$viewID},{$moduleID},{$packageID},";
                    $treePackage->grade  = 3;
                    $treePackage->desc   = !empty($package->desc) ? $package->desc : '';
                    $treePackage->order  = $package->order;
                    $tree[$packageID]    = $treePackage;
                }
            }
        }

        return $tree;
    }

    /**
     * Super Model: Init Privs.
     *
     * @param  bool   $onlyUpdateModule
     * @access public
     * @return void
     */
    public function initPrivs($onlyUpdateModule = true)
    {
        $this->sortResource();
        $resource = json_decode(json_encode($this->lang->resource), true);
        if(!$onlyUpdateModule)
        {
            $this->dao->delete()->from(TABLE_PRIVLANG)->exec();
            $this->dao->delete()->from(TABLE_PRIVRELATION)->exec();
            $this->dao->delete()->from(TABLE_PRIV)->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('priv')->exec();
            $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' auto_increment = 1');
        }

        $viewModules = array();
        $this->loadModel('setting');
        foreach($resource as $moduleName => $methods)
        {
            $groupKey = $moduleName;
            $view     = isset($this->lang->navGroup->{$groupKey}) ? $this->lang->navGroup->{$groupKey} : $moduleName;
            $viewModules[$view][] = $moduleName;

            if($onlyUpdateModule) continue;
            $order = 1;
            foreach($methods as $methodName => $methodLang)
            {
                $priv = new stdclass();
                $priv->moduleName = $moduleName;
                $priv->methodName = $methodName;
                $priv->module     = $moduleName;
                $priv->package    = 0;
                $priv->system     = 1;
                $priv->order      = $order * 5;
                $order ++;

                $this->dao->replace(TABLE_PRIV)->data($priv)->exec();
                if(!dao::isError())
                {
                    $privID = $this->dao->lastInsertId();

                    $this->app->loadLang($moduleName);
                    foreach($this->config->langs as $lang => $langValue)
                    {
                        if($lang != 'zh-cn') continue;
                        $privLang = new stdclass();
                        $privLang->priv = $privID;
                        $privLang->lang = $lang;
                        $privLang->name = isset($this->lang->{$moduleName}->{$methodLang}) ? $this->lang->{$moduleName}->{$methodLang} : "{$moduleName}-{$methodLang}";
                        $privLang->desc = '';
                        $this->dao->replace(TABLE_PRIVLANG)->data($privLang)->exec();
                    }
                }
            }
        }

        foreach($viewModules as $viewName => $modules)
        {
            $modules = implode(',', $viewModules[$viewName]);
            $this->setting->setItem("system.priv.{$viewName}Modules", $modules);
        }

        $views = array_keys($viewModules);
        $this->setting->setItem("system.priv.views", implode(',', $views));

        if(!dao::isError()) return true;
    }

    /**
     * Super Model: Init Data for priv package.
     *
     * @access public
     * @return void
     */
    public function initData()
    {
        $allResourceFile = $this->app->getModuleRoot() . 'group/lang/allresources.php';

        $views   = $this->loadModel('setting')->getItem("owner=system&module=priv&key=views");
        $views   = array_filter(explode(',', $views));
        $views[] = 'general';

        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " CHANGE `priv` `objectID` mediumint(8) unsigned NOT NULL;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD `objectType` enum('priv','manager') NOT NULL DEFAULT 'priv' AFTER `objectID`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD `key` varchar(100) NOT NULL AFTER `lang`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " CHANGE `name` `value` varchar(255) NOT NULL;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD UNIQUE KEY `objectlang` (`objectID`,`objectType`,`lang`);");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " DROP INDEX `privlang`;");
        $this->dbh->exec("DROP TABLE IF EXISTS " . TABLE_PRIVMANAGER . ";");
        $this->dbh->exec("CREATE TABLE IF NOT EXISTS " . TABLE_PRIVMANAGER . " ( `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, `parent` varchar(30) NOT NULL, `code` varchar(100) NOT NULL, `type` enum('view','module','package') NOT NULL DEFAULT 'package', `order` tinyint(3) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " ADD `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,' AFTER `package`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " ADD `vision` varchar(30) NOT NULL DEFAULT ',rnd,' AFTER `edition`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVMANAGER . " ADD `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,' AFTER `type`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVMANAGER . " ADD `vision` varchar(30) NOT NULL DEFAULT ',rnd,' AFTER `edition`;");

        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `order` `order` mediumint(8) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIVMANAGER . ' CHANGE `order` `order` mediumint(8) NOT NULL;');

        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `package` `package` varchar(100) NOT NULL;");
        $this->dbh->exec("UPDATE " . TABLE_PRIV . " SET `package`=`module` WHERE `package`=0;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `package` `parent` varchar(100) NOT NULL;");

        $this->loadModel('dev');

        /* 插入权限所有语言项 */
        $storedPrivs = array();
        $privList    = $this->dao->select('*')->from(TABLE_PRIV)->fetchAll('id');
        foreach($privList as $privID => $priv)
        {
            foreach($this->config->langs as $lang => $langValue)
            {
                $methodLang = isset($this->lang->resource->{$priv->moduleName}->{$priv->methodName}) ? $this->lang->resource->{$priv->moduleName}->{$priv->methodName} : $priv->methodName;

                $privLang = new stdclass();
                $privLang->objectID   = $privID;
                $privLang->objectType = 'priv';
                $privLang->lang       = $lang;
                $privLang->key        = "{$priv->moduleName}-{$methodLang}";
                $privLang->value      = '';
                $privLang->desc       = '';
                $this->dao->replace(TABLE_PRIVLANG)->data($privLang)->exec();
            }

            $storedPrivs["{$priv->moduleName}-{$priv->methodName}"] = $priv;
        }

        $originResource = json_decode(json_encode($this->lang->resource), true);
        $originPrivs    = array();
        foreach($originResource as $moduleName => $methods)
        {
            foreach($methods as $methodName => $methodLang)
            {
                $originPrivs["$moduleName-$methodName"] = "$moduleName-$methodName";
            }
        }

        /* 删掉语言项中不存在的权限 */
        foreach($storedPrivs as $moduleMethod => $storedPriv)
        {
            if(!empty($originPrivs[$moduleMethod])) continue;
            $this->dao->delete()->from(TABLE_PRIV)->where('id')->eq($storedPriv->id)->exec();
            $this->dao->delete()->from(TABLE_PRIVLANG)->where('objectType')->eq('priv')->andWhere('objectID')->eq($storedPriv->id)->exec();
        }

        /* 迁移权限包数据   privpackage => privmanager */
        /* 迁移权限包语言项 privpackage => privlang */
        $packageList = $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->fetchAll('id');
        foreach($packageList as $packageID => $package)
        {
            $packageData = new stdclass();
            $packageData->id     = $packageID;
            $packageData->parent = $package->module;
            $packageData->type   = 'package';
            $packageData->order  = $package->order;
            $packageData->vision = ',rnd,lite,';

            $this->dao->insert(TABLE_PRIVMANAGER)->data($packageData)->exec();

            $packageLang = new stdclass();
            $packageLang->objectID   = $packageID;
            $packageLang->objectType = 'manager';
            $packageLang->lang       = 'zh-cn';
            $packageLang->key        = '';
            $packageLang->value      = $package->name;
            $packageLang->desc       = $package->desc;

            $this->dao->insert(TABLE_PRIVLANG)->data($packageLang)->exec();
        }

        /* 初始化视图和模块到zt_privmanager */
        $viewPairs   = array();
        $modulePairs = array();
        $indexMenu   = array();
        $hasStored   = false;
        $this->app->loadLang('index');
        $this->lang->mainNav = json_decode(json_encode($this->lang->mainNav), true);

        $indexMenu['index']  = "{$this->lang->navIcons['my']} {$this->lang->index->common}|index|index|";
        $this->lang->mainNav = (object)array_merge($indexMenu, $this->lang->mainNav);
        foreach($this->config->langs as $lang => $langValue)
        {
            /* 视图 */
            $viewOrder   = 1;
            $moduleOrder = 1;
            $this->lang->mainNav->general = "{$this->lang->navIcons['my']} {$this->lang->my->shortCommon}|my|index|";
            foreach($views as $moduleMenu)
            {
                $viewID = 0;
                if($moduleMenu != 'general' and isset($this->lang->mainNav->{$moduleMenu}))
                {
                    if(!$hasStored)
                    {
                        $viewData = new stdclass();
                        $viewData->parent = '';
                        $viewData->code   = $moduleMenu;
                        $viewData->type   = 'view';
                        $viewData->order  = $viewOrder * 10;
                        $viewOrder ++;

                        $this->dao->insert(TABLE_PRIVMANAGER)->data($viewData)->exec();
                        $viewID = $this->dao->lastInsertId();
                    }
                    else
                    {
                        $viewID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
                            ->where('`type`')->eq('view')
                            ->andWhere('code')->eq($moduleMenu)
                            ->fetch('id');
                    }

                    $viewLang = new stdclass();
                    $viewLang->objectID   = $viewID;
                    $viewLang->objectType = 'manager';
                    $viewLang->lang       = $lang;
                    $viewLang->key        = $moduleMenu;
                    $viewLang->value      = '';
                    $viewLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($viewLang)->exec();
                    $viewPairs[$moduleMenu] = $viewID;
                }

                /* 模块 */
                $modules     = $this->getMenuModules($moduleMenu);
                $viewModules = $this->setting->getItem("owner=system&module=priv&key={$moduleMenu}Modules");
                $viewModules = array_filter(explode(',', $viewModules));
                foreach($viewModules as $moduleName)
                {
                    if(!$hasStored)
                    {
                        $moduleData = new stdclass();
                        $moduleData->parent = (isset($this->lang->navGroup->{$moduleName}) and !in_array($moduleMenu, array('misc', 'conference'))) ? $viewPairs[$moduleMenu] : 0;
                        $moduleData->code   = $moduleName;
                        $moduleData->type   = 'module';
                        $moduleData->order  = $moduleOrder * 10;
                        $moduleOrder ++;

                        $this->dao->insert(TABLE_PRIVMANAGER)->data($moduleData)->exec();
                        $moduleID = $this->dao->lastInsertId();
                    }
                    else
                    {
                        $moduleID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
                            ->where('`type`')->eq('module')
                            ->andWhere('code')->eq($moduleName)
                            ->fetch('id');
                    }

                    $moduleLang = new stdclass();
                    $moduleLang->objectID   = $moduleID;
                    $moduleLang->objectType = 'manager';
                    $moduleLang->lang       = $lang;
                    $moduleLang->key        = $moduleName;
                    $moduleLang->value      = '';
                    $moduleLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($moduleLang)->exec();
                    $modulePairs[$moduleName] = $moduleID;

                    /* 更新权限包的parent字段 */
                    $this->dao->update(TABLE_PRIVMANAGER)->set('`parent`')->eq($moduleID)
                        ->where('`parent`')->eq($moduleName)
                        ->andWhere('`type`')->eq('package')
                        ->exec();

                    /* 更新权限的parent字段 */
                    $this->dao->update(TABLE_PRIV)->set('`parent`')->eq($moduleID)
                        ->where('`parent`')->eq($moduleName)
                        ->exec();
                }
            }

            $hasStored = true;
        }

        /* 初始化 view 的edition和vision字段 */
        include $allResourceFile;
        $editionMap = array('open' => ',open,biz,max,', 'biz' => ',biz,max,', 'max' => ',max,');
        foreach($views as $edition => $visions)
        {
            foreach($visions as $vision => $viewList)
            {
                $vision = $vision == 'lite' ? ',rnd,lite,' : ',rnd,';
                $viewList = unserialize($viewList);
                foreach($viewList as $view)
                {
                    if($view == 'menuOrder') continue;

                    $this->dao->update(TABLE_PRIVMANAGER)
                        ->set('edition')->eq("{$editionMap[$edition]}")
                        ->set('vision')->eq("$vision")
                        ->where('id')->eq($viewPairs[$view])
                        ->exec();
                }
            }
        }

        /* 初始化 module 和 priv 的edition和vision字段 */
        foreach($resources as $edition => $visions)
        {
            foreach($visions as $vision => $modules)
            {
                $vision = $vision == 'lite' ? ',rnd,lite,' : ',rnd,';
                $modules = unserialize($modules);
                foreach($modules as $module => $resourceList)
                {
                    if(!empty($modulePairs[$module]))
                    {
                        $this->dao->update(TABLE_PRIVMANAGER)
                            ->set('edition')->eq("{$editionMap[$edition]}")
                            ->set('vision')->eq("$vision")
                            ->where('id')->eq($modulePairs[$module])
                            ->exec();
                    }

                    foreach($resourceList as $method => $methodLang)
                    {
                        $this->dao->update(TABLE_PRIV)
                            ->set('edition')->eq("{$editionMap[$edition]}")
                            ->set('vision')->eq("$vision")
                            ->where('moduleName')->eq($module)
                            ->andWhere('methodName')->eq($method)
                            ->exec();
                    }
                }
            }
        }

        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' DROP `module`;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `moduleName` `module` varchar(30) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `methodName` `method` varchar(30) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' DROP INDEX `priv`;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' ADD UNIQUE `priv` (`module`,`method`);');
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `parent` `parent` mediumint(8) unsigned NOT NULL;");
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIVMANAGER . ' CHANGE `parent` `parent` mediumint(8) unsigned NOT NULL;');
        $this->dbh->exec('DROP TABLE IF EXISTS ' . TABLE_PRIVPACKAGE . ';');

        return print('success');
    }

    /**
     * Super Model: Init system view, module and privileges.
     *
     * @access public
     * @return void
     */
    public function initSystemResources()
    {
        $allResourceFile = $this->app->getModuleRoot() . 'group/lang/allresources.php';
        if(!file_exists("$allResourceFile")) return print("Please execute the commands: touch $allResourceFile; chmod 777 $allResourceFile");

        $resourceContents = file_get_contents($allResourceFile);
        if(!$resourceContents) file_put_contents($allResourceFile, "<?php\n\$views     = array();\n\$resources = array();\n");

        $this->sortResource();
        $resource = json_decode(json_encode($this->lang->resource), true);
        $resource = serialize($resource);
        $view     = array_keys(json_decode(json_encode($this->lang->mainNav), true));
        $view     = serialize($view);

        file_put_contents($allResourceFile, "\$views['{$this->config->edition}']['{$this->config->vision}'] = '$view';\n", FILE_APPEND);
        file_put_contents($allResourceFile, "\$resources['{$this->config->edition}']['{$this->config->vision}'] = '$resource';\n", FILE_APPEND);
        return print('success');
    }

    /**
     * Get priv by id.
     *
     * @param  int    $privID
     * @param  string $lang
     * @access public
     * @return object
     */
    public function getPrivByID($privID, $lang = '')
    {
        if(empty($lang)) $lang = $this->app->getClientLang();
        $priv = $this->dao->select('t1.*,t2.`key`,t2.value,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.id')->eq((int)$privID)
            ->andWhere('t2.objectType')->eq('priv')
            ->andWhere('t2.lang')->eq($lang)
            ->fetch();
        if(!empty($priv->value)) $priv->name = $priv->value;
        if(empty($priv->value) and !empty($priv->key))
        {
            list($moduleName, $methodLang) = explode('-', $priv->key);
            if($moduleName == 'requirement') $moduleName = 'story';
            $this->app->loadLang($moduleName);

            $hasLang = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->resource->{$priv->module}) and isset($this->lang->resource->{$priv->module}->{$priv->method}));
            if($hasLang) $priv->name = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->{$moduleName}->$methodLang)) ? $this->lang->{$moduleName}->$methodLang : $priv->method;
        }

        return $priv;
    }

    /**
     * Get priv by id list.
     *
     * @param  string    $privIdList
     * @access public
     * @return array
     */
    public function getPrivByIdList($privIdList)
    {
        return $this->dao->select("t1.*,t2.`key`,t2.value,t2.desc,IF(t3.type='package', t4.`code`, t3.`code`) as moduleCode")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t4')->on('t3.parent=t4.id')
            ->where('t1.id')->in($privIdList)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t2.objectType')->eq('priv')
            ->andWhere('(t3.type')->eq('module')
            ->orWhere('(t3.type')->eq('package')->andWhere('t4.type')->eq('module')->markRight(2)
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * Get priv by module.
     *
     * @param  array    $modules
     * @access public
     * @return array
     */
    public function getPrivByModule($modules)
    {
        $moduleIdList = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
            ->where('code')->in($modules)
            ->andWhere('`type`')->eq('module')
            ->fetchPairs('id');

        $packageIdList = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
            ->where('`parent`')->in(array_keys($moduleIdList))
            ->fetchPairs('id');

        $privs = $this->dao->select("t1.*,IF(t3.type='package', t3.`id`, '0') as packageID,IF(t3.type='package', t4.`code`, t3.`code`) as moduleCode,t2.`key`,t2.value,t2.`desc`")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t4')->on('t3.parent=t4.id')
            ->where('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t2.objectType')->eq('priv')

            ->andWhere('((t3.type')->eq('module')->andWhere('t3.id')->in($moduleIdList)->markRight(1)
            ->orWhere('(t3.type')->eq('package')->andWhere('t3.id')->in($packageIdList)->andWhere('t4.type')->eq('module')->markRight(2)

            ->orderBy('t1.`order`')
            ->fetchAll('id');
        $privs = $this->transformPrivLang($privs);

        $privList = array();
        foreach($privs as $priv) $privList[$priv->moduleCode][$priv->packageID][$priv->id] = $priv;

        return $privList;
    }

    /**
     * Get priv group by package.
     *
     * @param  array  $parentList
     * @access public
     * @return array
     */
    public function getPrivByParent($parentList)
    {
        return $this->dao->select('t1.*,t2.`key`,t2.value')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.parent')->in($parentList)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.`objectType`')->eq('priv')
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * Get privs list by module.
     *
     * @param  string $view
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPrivsListByView($view = '', $pager = null)
    {
        $modules = $this->getPrivManagerPairs('module', $view);
        $modules = array_keys($modules);

        $privs = $this->dao->select("t1.id, t1.module, t1.method, CONCAT(t1.module, '-', t1.method) AS action, IF(t3.type = 'module', 0, t1.parent) as parent, t1.order, t2.`key`, t2.`value`, t2.desc, IF(t3.type = 'module', t3.code, t4.code) as parentCode")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t4')->on('t3.parent=t4.id')
            ->where('1=1')
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t2.objectType')->eq('priv')
            ->andWhere('((t3.type')->eq('package')
            ->andWhere('t4.type')->eq('module')
            ->beginIF(!empty($view) and $view != 'general')->andWhere('t4.code')->in($modules)->fi()
            ->beginIF(!empty($view) and $view == 'general')->andWhere('t4.parent')->eq('0')->fi()
            ->markRight(1)
            ->orWhere('(t3.type')->eq('module')
            ->andWhere('t4.type')->eq('view')
            ->beginIF(!empty($view) and $view != 'general')->andWhere('t3.code')->in($modules)->fi()
            ->beginIF(!empty($view) and $view == 'general')->andWhere('t3.parent')->eq('0')->fi()
            ->markRight(2)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->orderBy("t3.order asc, `order` asc")
            ->page($pager)
            ->fetchGroup('parentCode', 'action');
        $privList = array();
        foreach($modules as $module)
        {
            $privList = array_merge($privList, zget($privs, $module, array()));
        }

        return $privList;
    }

    /**
     * Get privs list by module.
     *
     * @param  int    $queryID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPrivsListBySearch($queryID = 0, $pager = null)
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
        if($query)
        {
            $this->session->set('privQuery', $query->sql);
            $this->session->set('privForm', $query->form);
        }
        if($this->session->privQuery == false) $this->session->set('privQuery', ' 1 = 1');

        $privQuery = $this->session->privQuery;

        $this->loadModel('setting');
        if(strpos($privQuery, '`view`') !== false)
        {
            preg_match_all("/`view`[^']+'([^']+)'/Ui", $privQuery, $out);
            $privQuery = str_replace(array('`view` =', '`view` LIKE', '`view`  =', '`view` !=', '`view`  NOT LIKE'), array('`view` IN', '`view` IN', '`view` IN', '`view` NOT IN', '`view` NOT IN'), $privQuery);
            foreach($out[1] as $view)
            {
                $view = str_replace('%', '', $view);
                $modules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
                $modules = str_replace(',', "','", $modules);
                $privQuery = preg_replace("/`view`([^']+)'([%]?{$view}[%]?)'/Ui", "`module`$1('{$modules}')", $privQuery);
            }
        }

        if(strpos($privQuery, '`recommendPrivs`') !== false)
        {
            preg_match_all("/`recommendPrivs`[^']+'([^']+)'/Ui", $privQuery, $out);
            foreach($out[1] as $priv)
            {
                $priv = str_replace('%', '', $priv);
                if(!empty($priv))
                {
                    $privQuery = preg_replace(array('/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+LIKE/', '/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+!=/', '/`recommendPrivs`[ ]+NOT LIKE/'), array('`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN'), $privQuery);
                    $privs     = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('recommend')->fetchPairs();
                }
                else
                {
                    $privQuery = preg_replace(array('/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+LIKE/', '/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+!=/', '/`recommendPrivs`[ ]+NOT LIKE/'), array('`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN', '`recommendPrivs` IN', '`recommendPrivs` IN'), $privQuery);
                    $privs     = $this->dao->select('priv,relationPriv')->from(TABLE_PRIVRELATION)->where('type')->eq('recommend')->fetchPairs();
                    $privs     = array_unique(array_keys($privs) + array_values($privs));
                }
                $privs     = implode("','", $privs);
                $privs     = !empty($privs) ? $privs : 0;
                $privQuery = preg_replace("/`recommendPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", "t1.`id`$1('{$privs}')", $privQuery);
            }
        }
        if(strpos($privQuery, '`dependPrivs`') !== false)
        {
            preg_match_all("/`dependPrivs`[^']+'([^']*)'/Ui", $privQuery, $out);
            foreach($out[1] as $priv)
            {
                $priv = str_replace('%', '', $priv);
                if(!empty($priv))
                {
                    $privQuery = preg_replace(array('/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+LIKE/', '/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+!=/', '/`dependPrivs`[ ]+NOT LIKE/'), array('`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` NOT IN', '`dependPrivs` NOT IN'), $privQuery);
                    $privs     = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('depend')->fetchPairs();
                }
                else
                {
                    $privQuery = preg_replace(array('/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+LIKE/', '/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+!=/', '/`dependPrivs`[ ]+NOT LIKE/'), array('`dependPrivs` NOT IN', '`dependPrivs` NOT IN', '`dependPrivs` NOT IN', '`dependPrivs` IN', '`dependPrivs` IN'), $privQuery);
                    $privs     = $this->dao->select('priv,relationPriv')->from(TABLE_PRIVRELATION)->where('type')->eq('depend')->fetchPairs();
                    $privs     = array_unique(array_keys($privs) + array_values($privs));
                }
                $privs     = implode("','", $privs);
                $privs     = !empty($privs) ? $privs : 0;
                $privQuery = preg_replace("/`dependPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", "t1.`id`$1('{$privs}')", $privQuery);
            }
        }
        if(strpos($privQuery, '`name`') !== false) $privQuery = str_replace('`name`', 't2.`value`', $privQuery);
        if(strpos($privQuery, '`module`') !== false) $privQuery = str_replace('`module`', 't1.`module`', $privQuery);
        if(strpos($privQuery, '`desc`') !== false) $privQuery = str_replace('`desc`', 't2.`desc`', $privQuery);

        $views   = empty($view) ? $this->setting->getItem("owner=system&module=priv&key=views") : $view;
        $views   = explode(',', $views);
        $modules = '';
        foreach($views as $view) $modules .= ',' . $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
        $modules = trim($modules, ',');

        return $this->dao->select("t1.*,t2.value,t2.desc, INSTR('$modules', t1.`module`) as moduleOrder")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->leftJoin(TABLE_PRIVPACKAGE)->alias('t3')->on('t1.parent=t3.id')
            ->where('1=1')
            ->andWhere($privQuery)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy("moduleOrder asc, t3.order asc, `order` asc")
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get priv relation.
     *
     * @param  int     $priv
     * @param  string  $type    depend|recommend
     * @param  string  $module
     * @access public
     * @return array
     */
    public function getPrivRelation($priv, $type = '', $module = '')
    {
        $relations = $this->dao->select("t1.type,t2.*,t3.`key`,t3.value,IF(t4.type='package', t5.`code`, t4.`code`) as moduleCode")->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.relationPriv=t2.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t4')->on('t2.parent=t4.id')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t5')->on('t4.parent=t5.id')
            ->where('t1.priv')->eq($priv)
            ->andWhere('t2.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t2.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t3.objectType')->eq('priv')
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()

            ->andWhere('((t4.type')->eq('module')
            ->beginIF($module)->andWhere('t4.code')->eq($module)->fi()
            ->markRight(1)
            ->orWhere('(t4.type')->eq('package')
            ->andWhere('t5.type')->eq('module')
            ->beginIF($module)->andWhere('t5.code')->eq($module)->fi()
            ->markRight(2)
            ->fetchGroup('type', 'id');

        foreach($relations as $relationType => $privList) $relations[$relationType] = $this->transformPrivLang($privList);

        if(!empty($type)) return zget($relations, $type, array());
        return $relations;
    }

    /**
     * Get priv relation.
     *
     * @param  array  $privs
     * @param  string $type    depend|recommend
     * @access public
     * @return array
     */
    public function getPrivRelationsByIdList($privs, $type = '', $returnType = 'name')
    {
        $privs = $this->dao->select('DISTINCT t1.priv,t1.type,t1.relationPriv,t3.module,t3.method,t4.`key`,t4.value')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.priv=t2.id')
            ->leftJoin(TABLE_PRIV)->alias('t3')->on('t1.relationPriv=t3.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t4')->on('t3.id=t4.objectID')
            ->where("CONCAT(t2.module, '-', t2.method)")->in($privs)
            ->andWhere('t3.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t3.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t4.objectType')->eq('priv')
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->fetchGroup('type');
        if($returnType == 'idGroup') return $privs;

        $relationPrivs = array();
        foreach($privs as $type => $typePrivs)
        {
            $typePrivs = $this->transformPrivLang($typePrivs);
            $relationPrivs[$type] = array();
            foreach($typePrivs as $priv) $relationPrivs[$type][$priv->priv] = empty($relationPrivs[$type][$priv->priv]) ? $priv->name : "{$relationPrivs[$type][$priv->priv]},{$priv->name}";
        }

        return $relationPrivs;
    }

    /**
     * Save relation.
     *
     * @param  array    $privIdList
     * @param  string   $type    depend|recommend
     * @access public
     * @return bool
     */
    public function saveRelation($privIdList, $type)
    {
        if(is_string($privIdList)) $privIdList = explode(',', $privIdList);
        $data = fixer::input('post')->get();
        if(empty($data->relation)) return false;

        foreach($privIdList as $privID)
        {
            $relation = new stdclass();
            $relation->priv = $privID;
            $relation->type = $type;
            foreach($data->relation as $privModule => $privRelations)
            {
                foreach($privRelations as $privRelation)
                {
                    if($privID == $privRelation) continue;
                    $relation->relationPriv = $privRelation;
                    $this->dao->replace(TABLE_PRIVRELATION)->data($relation)->exec();
                }
            }
        }
        return true;
    }

    /**
     * Get priv package tree.
     *
     * @param  string $type
     * @return array
     **/
    public function getModuleAndPackageTree($type = 'all')
    {
        $modules = $this->getMenuModules(null, true);

        $tree = array();

        foreach($modules as $module => $moduleName)
        {
            if($type == 'all') $tree[$module] = $moduleName;
            $packages = $this->getPrivPackagesByModule($module);
            foreach($packages as $packageID => $package)
            {
                $tree[$module . ',' . $packageID] = $moduleName . '/' . $package->name;
            }
        }
        return $tree;
    }

    /**
     * Create a privilege package.
     *
     * @access public
     * @return int
     */
    public function createPriv()
    {
        $data = fixer::input('post')->get();

        if(!empty($data->moduleName) and !empty($data->methodName))
        {
            $method = $this->dao->select('`module`,`method`')->from(TABLE_PRIV)->where('`module`')->eq($data->moduleName)->andWhere('`method`')->eq($data->methodName)->fetchPairs();
            if(count($method) > 0) dao::$errors['methodName'] = $this->lang->group->repeatPriv;
        }

        $this->config->priv->create->requiredFields = explode(',', $this->config->priv->create->requiredFields);
        foreach($this->config->priv->create->requiredFields as $field)
        {
            if(isset($data->{$field}) and empty($data->{$field}))
            {
                $langField = 'priv' . ucfirst($field);
                dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->group->{$langField});
            }
        }

        if(dao::isError()) return false;

        $parent = !empty($data->package) ? $data->package : $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('code')->eq($data->module)->andWhere('type')->eq('module')->fetch('id');

        $priv = new stdclass();
        $priv->module = $data->moduleName;
        $priv->method = $data->methodName;
        $priv->parent = $parent;
        $priv->order  = $this->dao->select('(count(`id`) + 1) * 5 as `order`')->from(TABLE_PRIV)->where('`parent`')->eq($parent)->fetch('order');
        $this->dao->insert(TABLE_PRIV)->data($priv)->exec();
        if(dao::isError()) return false;

        $privID = $this->dao->lastInsertId();

        $privLang = new stdclass();
        $privLang->value      = $data->name;
        $privLang->desc       = $data->desc;
        $privLang->objectID   = $privID;
        $privLang->objectType = 'priv';
        $privLang->lang = $this->app->clientLang;

        $this->dao->insert(TABLE_PRIVLANG)->data($privLang)->exec();

        $this->loadModel('action')->create('privlang', $privID, 'Opened');
        return $privID;
    }

    /**
     * Update priv info
     *
     * @param   int    $privID
     * @param   string $lang
     * @return  void
     **/
    public function updatePriv($privID, $lang)
    {
        $oldPriv = $this->getPrivByID($privID, $lang);

        $data = fixer::input('post')->get();

        if(!empty($data->moduleName) and !empty($data->methodName))
        {
            $method = $this->dao->select('module,method')->from(TABLE_PRIV)->where('`module`')->eq($data->moduleName)->andWhere('`method`')->eq($data->methodName)->andWhere('id')->ne($privID)->fetchAll('methodName');
            if(count($method) > 0) dao::$errors['methodName'] = $this->lang->group->repeatPriv;
        }

        $this->config->priv->edit->requiredFields = explode(',', $this->config->priv->edit->requiredFields);
        foreach($this->config->priv->edit->requiredFields as $field)
        {
            if(isset($data->{$field}) and empty($data->{$field}))
            {
                $langField = 'priv' . ucfirst($field);
                dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->group->{$langField});
            }
        }

        if(dao::isError()) return false;

        $parent = !empty($data->package) ? $data->package : $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('code')->eq($data->module)->andWhere('type')->eq('module')->fetch('id');

        $priv = new stdclass();
        $priv->module = $data->moduleName;
        $priv->method = $data->methodName;
        $priv->parent = $parent;

        if($priv->parent != $oldPriv->parent) $priv->order = $this->dao->select('(count(`id`) + 1) * 5 as `order`')->from(TABLE_PRIV)->where('`parent`')->eq($priv->parent)->fetch('order');
        $this->dao->update(TABLE_PRIV)->data($priv)->where('id')->eq($privID)->exec();

        $privLang = new stdclass();
        $privLang->value = $data->name;
        $privLang->desc  = $data->desc;
        $this->dao->update(TABLE_PRIVLANG)->data($privLang)->where('objectID')->eq($privID)->andWhere('objectType')->eq('priv')->andWhere('lang')->eq($lang)->exec();

        $priv = $this->getPrivByID($privID, $lang);

        $changes = common::createChanges($oldPriv, $priv);
        return $changes;

    }

    /**
     * Delete a priv.
     *
     * @param  int    $privID
     * @access public
     * @return bool
     */
    public function deletePriv($privID)
    {
        $this->dao->delete()->from(TABLE_PRIV)->where('id')->eq($privID)->exec();
        $this->dao->delete()->from(TABLE_PRIVLANG)->where('priv')->eq($privID)->exec();
        $this->dao->delete()->from(TABLE_PRIVRELATION)->where('priv')->eq($privID)->orWhere('relationPriv')->eq($privID)->exec();
        if(dao::isError()) return false;
    }

    /**
     * Batch change package.
     *
     * @param  array  $privIdList
     * @param  string $module
     * @param  int    $packageID
     * @access public
     * @return void
     */
    public function batchChangePackage($privIdList, $module, $packageID)
    {
        $oldPrivs = $this->getPrivByIdList($privIdList);
        foreach($privIdList as $privID)
        {
            $oldPriv = $oldPrivs[$privID];
            if($packageID == $oldPriv->package and $module == $oldPriv->module) continue;

            $priv = new stdclass();
            $priv->module  = $module;
            $priv->package = $packageID;

            $this->dao->update(TABLE_PRIV)->data($priv)->autoCheck()->where('id')->eq((int)$privID)->exec();
            if(!dao::isError()) $allChanges[$privID] = common::createChanges($oldPriv, $priv);
        }
        return $allChanges;
    }

    /**
     * Build priv search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildPrivSearchForm($queryID, $actionURL)
    {
        $this->config->group->priv->search['actionURL'] = $actionURL;
        $this->config->group->priv->search['queryID']   = $queryID;

        $this->loadModel('setting');

        $modules        = $this->getPrivManagerPairs('module');
        $packages       = $this->getPrivManagerPairs('package');
        $packageModules = $this->getPrivManagerPairs('package', '', 'parentCode');
        foreach($packages as $packageID => $package)
        {
            $packages[$packageID] = $modules[$packageModules[$packageID]] . '/' . $package;
        }

        $privs    = array();
        $privList = $this->getPrivsListByView();
        $privList = $this->transformPrivLang($privList);
        foreach($privList as $privID => $priv)
        {
            $privs[$privID] = (isset($packages[$priv->parent]) ? $packages[$priv->parent] : $modules[$priv->parentCode]) . '/' . $priv->name;
        }

        $this->config->group->priv->search['params']['view']['values']           = array('' => '') + $this->getPrivManagerPairs('view');
        $this->config->group->priv->search['params']['module']['values']         = array('' => '') + $modules;
        $this->config->group->priv->search['params']['package']['values']        = array('' => '') + $packages;
        $this->config->group->priv->search['params']['recommendPrivs']['values'] = array('' => '') + $privs;
        $this->config->group->priv->search['params']['dependPrivs']['values']    = array('' => '') + $privs;

        $this->loadModel('search')->setSearchParams($this->config->group->priv->search);
    }

    /**
     * Get all priv's lang pairs.
     *
     * @access public
     * @return array
     */
    public function getPrivLangPairs()
    {
        return $this->dao->select('objectID as priv,value as name')->from(TABLE_PRIVLANG)
            ->where('lang')->eq($this->app->clientLang)
            ->fetchPairs();
    }

    /**
     * Update priv order.
     *
     * @access public
     * @return void
     */
    public function updatePrivOrder()
    {
        $data = fixer::input('post')->get();
        foreach($data->orders as $privID => $order) $this->dao->update(TABLE_PRIV)->set('order')->eq($order)->where('id')->eq($privID)->exec();
    }

    /**
     * Get priv manager pairs.
     *
     * @param  string $type
     * @param  string $parent
     * @access public
     * @return array
     */
    public function getPrivManagerPairs($type, $parent = '', $field = 'name', $hasParentName = false)
    {
        $parentType = $type == 'package' ? 'module' : 'view';
        $parent     = !empty($parent) ? $this->dao->select('id as parent')->from(TABLE_PRIVMANAGER)->where('type')->eq($parentType)->andWhere('code')->eq($parent)->fetch('parent') : 0;

        $moduleLang = $type == 'module' ? $this->getMenuModules('', true) : array();

        $orderBy = $type == 'module' ? 'parentOrder_asc, t1.order_asc' : 't1.order asc';
        $managers = $this->dao->select('DISTINCT t1.id,t1.code,t2.`key`,t1.`order`,t2.value,t3.code AS parentCode,IFNULL(t3.order, 999) as parentOrder,t4.key AS parentKey,t4.value AS parentValue')
            ->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t4')->on('t3.id=t4.objectID')
            ->where('t1.type')->eq($type)
            ->andWhere('t2.objectType')->eq('manager')
            ->beginIF($type == 'package')->andWhere('t4.objectType')->eq('manager')->fi()
            ->beginIF($type == 'module')->andWhere('t1.code')->in(array_keys($moduleLang))->fi()
            ->beginIF($type == 'package')->andWhere('t3.type')->eq('module')->fi()
            ->beginIF(!empty($parent))->andWhere('t1.parent')->eq($parent)->fi()
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->beginIF($type == 'package')->andWhere('t4.lang')->eq($this->app->getClientLang())->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        $pairs = array();
        foreach($managers as $managerID => $manager)
        {
            $key = $type == 'package' ? $managerID : $manager->code;
            if($field == 'name' and !empty($manager->value)) $pairs[$key] = $manager->value;
            if($field == 'name' and empty($manager->value) and $type == 'view' and isset($this->lang->{$manager->key}->common)) $pairs[$key] = $this->lang->{$manager->key}->common;
            if($field == 'name' and empty($manager->value) and $type == 'module') $pairs[$key] = isset($moduleLang[$manager->key]) ? $moduleLang[$manager->key] : $this->lang->{$manager->key}->common;
            if($field == 'parentCode') $pairs[$key] = $manager->parentCode;
            if($hasParentName)
            {
                $parentName = '';
                if(!empty($manager->parentValue)) $parentName = $manager->parentValue;
                if(empty($manager->parentValue) and $type == 'module')  $parentName = $this->lang->{$manager->parentKey}->common;
                if(empty($manager->parentValue) and $type == 'package') $parentName = isset($moduleLang[$manager->parentKey]) ? $moduleLang[$manager->parentKey] : $this->lang->{$manager->parentKey}->common;
                $pairs[$key] =  $parentName . '/' . $pairs[$key];
            }
        }
        return $pairs;
    }

    /**
     * Get priv managers.
     *
     * @param  string $type
     * @param  string $parent
     * @access public
     * @return array
     */
    public function getPrivManagers($type, $parent = '')
    {
        $parentType = $type == 'package' ? 'module' : 'view';
        $parent     = !empty($parent) ? $this->dao->select('id as parent')->from(TABLE_PRIVMANAGER)->where('type')->eq($parentType)->andWhere('code')->eq($parent)->fetch('parent') : 0;

        $moduleLang = $type == 'module' ? $this->getMenuModules('', true) : array();

        $managers = $this->dao->select('t1.id,t1.code,t2.`key`,t2.value, t1.`order`')
            ->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.type')->eq($type)
            ->andWhere('t2.objectType')->eq('manager')
            ->beginIF($type == 'module')->andWhere('t1.code')->in(array_keys($moduleLang))->fi()
            ->beginIF($parent !== '')->andWhere('t1.parent')->eq($parent)->fi()
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy('t1.order asc')
            ->fetchAll('id');

        foreach($managers as $managerID => $manager)
        {
            if(!empty($manager->value)) $managers[$managerID]->name = $manager->value;
            if(empty($manager->value) and $type == 'view')   $managers[$managerID]->name = $this->lang->{$manager->key}->common;
            if(empty($manager->value) and $type == 'module') $managers[$managerID]->name = isset($moduleLang[$manager->key]) ? $moduleLang[$manager->key] : $this->lang->{$manager->key}->common;
        }
        return $managers;
    }

    /**
     * Transform priv lang.
     *
     * @param  array    $privs
     * @param  bool     $needPairs
     * @access public
     * @return array
     */
    public function transformPrivLang($privs, $needPairs = false)
    {
        $privPairs = array();
        foreach($privs as $moduleMethod => $priv)
        {
            $priv->name = '';
            if(!empty($priv->value))
            {
                $priv->name = $priv->value;
            }
            else
            {
                list($moduleName, $methodLang) = explode('-', $priv->key);
                $actualModule = $moduleName == 'requirement' ? 'story' : $moduleName;
                $this->app->loadLang($actualModule);

                $hasLang = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->resource->{$priv->module}) and isset($this->lang->resource->{$priv->module}->{$priv->method}));
                if(!$hasLang)
                {
                    unset($privs[$moduleMethod]);
                    continue;
                }

                $priv->name = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->{$moduleName}->$methodLang)) ? $this->lang->{$moduleName}->$methodLang : $priv->method;
            }

            $privPairs[$moduleMethod] = $priv->name;
        }

        return $needPairs ? $privPairs : $privs;
    }

    /**
     * Get custom privs.
     *
     * @param  string $menu
     * @param  array  $privs
     * @access public
     * @return array
     */
    public function getCustomPrivs($menu, $privs = array())
    {
        $allPrivs = $this->dao->select('module,method')->from(TABLE_PRIV)->fetchGroup('module', 'method');
        foreach($this->lang->resource as $module => $methods)
        {
            if(isset($this->lang->$module->menus) and (empty($menu) or $menu == 'general'))
            {
                foreach($this->lang->$module->menus as $method => $value)
                {
                    $key  = "{$module}-{$method}";
                    $priv = new stdclass();
                    $priv->id          = $key;
                    $priv->module      = $module;
                    $priv->method      = $method;
                    $priv->action      = $key;
                    $priv->parent      = 0;
                    $priv->key         = "{$module}-{$method}";
                    $priv->parentCode  = $module;
                    $priv->moduleOrder = 0;
                    $priv->name        = $value;

                    $privs[$key] = $priv;
                }
            }

            foreach($methods as $method => $methodLabel)
            {
                if(isset($allPrivs[$module][$method])) continue;
                if(!$this->checkMenuModule($menu, $module)) continue;
                if(!isset($this->lang->{$module}->{$methodLabel})) $this->app->loadLang($module);
                if(isset($this->lang->$module->menus) and $method == 'browse') continue;

                $key  = "{$module}-{$method}";
                $priv = new stdclass();
                $priv->id          = $key;
                $priv->module      = $module;
                $priv->method      = $method;
                $priv->action      = $key;
                $priv->parent      = 0;
                $priv->key         = "{$module}-{$methodLabel}";
                $priv->parentCode  = $module;
                $priv->moduleOrder = 0;
                $priv->name        = isset($this->lang->{$module}->{$methodLabel}) ? $this->lang->{$module}->{$methodLabel} : $method;

                $privs[$key] = $priv;
            }
        }
        return $privs;
    }

    /**
     * Get related privs.
     *
     * @param  array  $privIdList
     * @param  string $type
     * @param  array  $excludePrivs
     * @param  array  $recommedSelect
     * @access public
     * @return array
     */
    public function getRelatedPrivs($privIdList, $type = '', $excludePrivs = array(), $recommedSelect = array())
    {
        $modulePairs = $this->getPrivManagerPairs('module');
        $modules     = array_keys($modulePairs);
        $privs = $this->dao->select("t1.relationPriv,t1.type,t2.parent,t2.module,t2.method,t2.`order`,t3.`key`,t3.value, IF(t4.type = 'module', t4.code, t5.code) as parentCode")->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.relationPriv=t2.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t1.relationPriv=t3.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t4')->on('t2.parent=t4.id')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t5')->on('t4.parent=t5.id')
            ->where('t1.priv')->in($privIdList)
            ->andWhere('t5.code')->in(array_keys($modulePairs))
            ->andWhere('(t1.relationPriv')->notin($privIdList)

            ->beginIF(!empty($recommedSelect))
            ->orWhere('(t1.relationPriv')->in($recommedSelect)
            ->andWhere('t1.type')->eq('recommend')->markRight(1)
            ->fi()

            ->markRight(1)
            ->beginIF(!empty($excludePrivs))->andWhere('t1.relationPriv')->notin($excludePrivs)->fi()
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->andWhere('t3.objectType')->eq('priv')
            ->andWhere('t2.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t2.vision')->like("%,{$this->config->vision},%")
            ->andWhere('((t4.type')->eq('package')
            ->andWhere('t5.type')->eq('module')
            ->beginIF(!empty($type) and $type != 'general')->andWhere('t5.code')->in($modules)->fi()
            ->markRight(1)
            ->orWhere('(t4.type')->eq('module')
            ->andWhere('t5.type')->eq('view')
            ->beginIF(!empty($type) and $type != 'general')->andWhere('t1.module')->in($modules)->fi()
            ->markRight(2)
            ->orderBy('t2.`order`_asc, t1.`type` desc')
            ->fetchGroup('parentCode', 'relationPriv');

        $relatedPrivs = array();
        foreach($modules as $module) $relatedPrivs = array_merge($relatedPrivs, zget($privs, $module, array()));

        $privList = empty($type) ? array('depend' => array(), 'recommend' => array()) : array($type => array());
        if(empty($relatedPrivs)) return $privList;

        $managerList  = $this->dao->select('*')->from(TABLE_PRIVMANAGER)->fetchAll('id');
        $relatedPrivs = $this->transformPrivLang($relatedPrivs);

        foreach($relatedPrivs as $relatedPriv)
        {
            $module = $managerList[$relatedPriv->parent]->type == 'package' ? $managerList[$managerList[$relatedPriv->parent]->parent]->code : $managerList[$relatedPriv->parent]->code;
            if(!isset($privList[$relatedPriv->type][$module])) $privList[$relatedPriv->type][$module] = array();
            $privList[$relatedPriv->type][$module]['title']      = $modulePairs[$module];
            $privList[$relatedPriv->type][$module]['id']         = $relatedPriv->parent;
            $privList[$relatedPriv->type][$module]['children'][] = array('title' => $relatedPriv->name, 'relationPriv' => $relatedPriv->relationPriv, 'parent' => $relatedPriv->parent, 'module' => $relatedPriv->module, 'method' => $relatedPriv->method);
        }

        if(empty($type) or $type == 'depend')    $privList['depend']    = array_values($privList['depend']);
        if(empty($type) or $type == 'recommend') $privList['recommend'] = array_values($privList['recommend']);
        return $privList;
    }

    /**
     * Get unassigned privs by module.
     *
     * @param  string  $module
     * @access public
     * @return array
     */
    public function getUnassignedPrivsByModule($module)
    {
        return $this->dao->select('t1.*')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t2')->on('t1.parent=t2.id')
            ->where('t2.`code`')->eq($module)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.type')->eq('module')
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * Get privs id list by group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function getPrivsIdListByGroup($groupID)
    {
        $modulePairs = $this->getPrivManagerPairs('module');
        $modules     = array_keys($modulePairs);
        $actions    = $this->dao->select("CONCAT(module, '-',  method) AS action")->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->andWhere('module')->in($modules)->fetchPairs();
        $actions    = implode("','", $actions);
        $privIdList = $this->dao->select("*")->from(TABLE_PRIV)
            ->where("CONCAT(module, '-',  method) IN ('$actions')")
            ->andWhere('edition')->like("%,{$this->config->edition},%")
            ->andWhere('vision')->like("%,{$this->config->vision},%")
            ->fetchAll('id');
        return $privIdList;
    }
}
