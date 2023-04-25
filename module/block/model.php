<?php
declare(strict_types=1);
/**
 * The model file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class blockModel extends model
{
    /**
     * Get a block by blockID. 
     * 根据区块ID获取区块信息.
     *
     * @param  int    $blockID
     * @access public
     * @return object|bool
     */
    public function getByID(int $blockID): object|bool
    {
        $block = $this->blockTao->fetch($blockID);
        if(empty($block)) return false;

        $block->params = json_decode($block->params);
        if($block->block == 'html') $block->params->html = $this->loadModel('file')->setImgSize($block->params->html);
        return $block;
    }

    /**
     * Get max order number by block module. 
     * 获取对应模块下区块的最大排序号.
     *
     * @param  string $module
     * @access public
     * @return int
     */
    public function getMaxOrderByModule(string $module): int
    {
        return $this->blockTao->fetchMaxOrderByModule($module);
    }

    /**
     * Get block list for account.
     * 获取区块列表.
     *
     * @param  string $module
     * @param  string $type
     * @param  int    $hidden
     * @access public
     * @return array|bool
     */
    public function getList(string $module, string $type = '', int $hidden = 0): array|bool
    {
        return $this->blockTao->fetchList($module, $type, $hidden);
    }

    /**
     * Get hidden blocks
     * 获取隐藏的区块列表.
     *
     * @param  string $module
     * @access public
     * @return array|bool
     */
    public function getHiddenBlocks(string $module): array|bool
    {
        return $this->blockTao->fetchList($module, $type = '', $hidden = 1);
    }

    /**
     * Save a block.
     *
     * @param  int    $blockID
     * @param  string $type
     * @param  string $module
     * @access public
     * @return bool|int
     */
    public function save(int $blockID, string $type, string $module = 'my'): bool|int
    {
        $block = $blockID ? $this->getByID($blockID) : null;
        $data = fixer::input('post')
            ->setIF($blockID, 'id', $blockID)
            ->add('account', $this->app->user->account)
            ->add('module', $module)
            ->add('order', $block ? $block->order : ($this->getMaxOrderByModule($module) + 1))
            ->add('hidden', 0)
            ->setDefault('vision', $this->config->vision)
            ->setDefault('grid', '4')
            ->setDefault('params', array())
            ->stripTags('html', $this->config->allowedTags)
            ->remove('uid,actionLink,modules,moduleBlock')
            ->get();

        $data->source = $this->post->moduleBlock ? $this->post->modules     : '';
        $data->block  = $this->post->moduleBlock ? $this->post->moduleBlock : $this->post->modules;

        if($block) $data->height = $block->height;
        if($type == 'html')
        {
            $uid  = $this->post->uid;
            $data = $this->loadModel('file')->processImgURL($data, 'html', $uid);
            $data->params['html'] = $data->html;
            unset($data->html);
            unset($_SESSION['album'][$uid]);
        }

        $data->params = helper::jsonEncode($data->params);

        $this->blockTao->replace($data);
        if(dao::isError()) return false;

        $this->loadModel('score')->create('block', 'set');

        $blockID = $blockID ? $blockID : $this->dao->lastInsertID(); 
        return $blockID; 
    }

    /**
     * Get data of welcome block.
     *
     * @access public
     * @return array
     */
    public function getWelcomeBlockData()
    {
        $data = array();

        $tasks = $this->dao->select("count(distinct t1.id) as tasks, count(distinct if(t1.status = 'done', 1, null)) as doneTasks")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on("t1.execution = t3.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on("t4.task = t1.id and t4.account = '{$this->app->user->account}'")
            ->where("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t4.`account` = '{$this->app->user->account}' and t1.status != 'closed' and t4.status != 'done') )")
            ->andWhere('(t2.status')->ne('suspended')
            ->orWhere('t3.status')->ne('suspended')
            ->markRight(1)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t1.status')->notin('closed,cancel')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t3.vision')->eq($this->config->vision)->fi()
            ->fetch();

        $data['tasks']     = isset($tasks->tasks)     ? $tasks->tasks : 0;
        $data['doneTasks'] = isset($tasks->doneTasks) ? $tasks->doneTasks : 0;

        $data['bugs']       = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on("t1.product = t2.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->ne('closed')
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('count');
        $data['stories']    = (int)$this->dao->select('count(*) AS count')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->fetch('count');
        $data['executions'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_EXECUTION)
            ->where('status')->notIN('done,closed')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch('count');
        $data['products']   = (int)$this->dao->select('count(*) AS count')->from(TABLE_PRODUCT)
            ->where('status')->ne('closed')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        $today = date('Y-m-d');
        $data['delayTask'] = (int)$this->dao->select('count(t1.id) AS count')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on("t1.execution = t3.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('(t2.status')->ne('suspended')
            ->orWhere('t3.status')->ne('suspended')
            ->markRight(1)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('t1.deadline')->notZeroDate()
            ->andWhere('t1.deadline')->lt($today)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetch('count');
        $data['delayBug'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.status')->eq('active')
            ->andWhere('t1.deadline')->notZeroDate()
            ->andWhere('t1.deadline')->lt($today)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('count');
        $data['delayProject'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_PROJECT)
            ->where('status')->in('wait,doing')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('end')->lt($today)
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        return $data;
    }

    /**
     * Init block when account use first.
     * 用户首次加载时初始化区块数据.
     *
     * @param  string $module
     * @param  string $type
     * @access public
     * @return bool
     */
    public function initBlock(string $module, string $type = ''): bool
    {
        if(!$module) return false;

        $flow    = isset($this->config->global->flow) ? $this->config->global->flow : 'full';
        $account = $this->app->user->account;
        $vision  = $this->config->vision;

        if($module == 'project')
        {
            $blocks = $this->lang->block->default[$type]['project'];

            /* Mark project block has init. */
            $this->loadModel('setting')->setItem("$account.$module.{$type}common.blockInited@$vision", '1');
        }
        else
        {
            $blocks = $module == 'my' ? $this->lang->block->default[$flow][$module] : $this->lang->block->default[$module];

            /* Mark this app has init. */
            $this->loadModel('setting')->setItem("$account.$module.common.blockInited@$vision", '1');
        }

        $this->loadModel('setting')->setItem("$account.$module.block.initVersion", $this->config->block->version);
        foreach($blocks as $index => $block)
        {
            $block['order']   = $index;
            $block['module']  = $module;
            $block['type']    = $type;
            $block['account'] = $account;
            $block['params']  = isset($block['params']) ? helper::jsonEncode($block['params']) : '';
            $block['vision']  = $this->config->vision;
            if(!isset($block['source'])) $block['source'] = $module;

            $this->blockTao->replace($block);
        }
        return !dao::isError();
    }

    /**
     * Get block json that user can add.
     * 获取允许用户添加的区块列表.
     *
     * @param  string $module
     * @param  string $dashboard ''|project
     * @param  string $type      scrum|waterfall|kanban
     * @access public
     * @return string
     */
    public function getAvailableBlocks(string $module = '', string $dashboard = '', string $type = ''): string
    {
        if($dashboard == 'project')
        {
            $blocks = $this->lang->block->modules[$type]['index']->availableBlocks;
        }
        else
        {
            if($module and isset($this->lang->block->modules[$module]))
            { 
                $blocks = $this->lang->block->modules[$module]->availableBlocks;
            }
            else
            {
                $blocks = $this->lang->block->availableBlocks;
            }
        }

        if(isset($this->config->block->closed))
        {
            foreach($blocks as $blockKey => $blockName)
            {
                if(strpos(",{$this->config->block->closed},", ",{$module}|{$blockKey},") !== false) unset($blocks->$blockKey);
            }
        }

        return json_encode($blocks);
    }

    /**
     * Get block set form params.
     * 获取不同区块所需的参数配置.
     * 
     * @param  string $type 
     * @param  string $module 
     * @access public
     * @return string
     */
    public function getParams(string $type, string $module): string
    {
        $type = $type == 'todo' ? $module : $type;
        $params = zget($this->config->block->params, $type, '');
        return json_encode($params);
    }

    /**
     * Get closed block pairs.
     *
     * @param  string $closedBlock
     * @access public
     * @return array
     */
    public function getClosedBlockPairs($closedBlock)
    {
        $blockPairs = array();
        if(empty($closedBlock)) return $blockPairs;

        foreach(explode(',', $closedBlock) as $block)
        {
            $block = trim($block);
            if(empty($block)) continue;

            list($moduleName, $blockKey) = explode('|', $block);
            if(empty($moduleName))
            {
                if(isset($this->lang->block->$blockKey)) $blockPairs[$block] = $this->lang->block->$blockKey;
                if($blockKey == 'html')    $blockPairs[$block] = 'HTML';
                if($blockKey == 'guide')   $blockPairs[$block] = $this->lang->block->guide;
                if($blockKey == 'dynamic') $blockPairs[$block] = $this->lang->block->dynamic;
                if($blockKey == 'welcome') $blockPairs[$block] = $this->lang->block->welcome;
            }
            else
            {
                $blockName = $blockKey;
                if(isset($this->lang->block->modules[$moduleName]->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules[$moduleName]->availableBlocks->$blockKey;
                if(isset($this->lang->block->availableBlocks->$blockKey)) $blockName = $this->lang->block->availableBlocks->$blockKey;
                if(isset($this->lang->block->modules['scrum']['index']->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules['scrum']['index']->availableBlocks->$blockKey;
                if(isset($this->lang->block->modules['waterfall']['index']->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules['waterfall']['index']->availableBlocks->$blockKey;

                $blockPairs[$block]  = isset($this->lang->block->moduleList[$moduleName]) ? "{$this->lang->block->moduleList[$moduleName]}|" : '';
                $blockPairs[$block] .= $blockName;
            }
        }

        return $blockPairs;
    }

    /**
     * Check whether long block.
     *
     * @param  object    $block
     * @access public
     * @return book
     */
    public function isLongBlock($block)
    {
        if(empty($block)) return true;
        return $block->grid >= 6;
    }

    /**
     * Check API for ranzhi
     *
     * @param  string    $hash
     * @access public
     * @return bool
     */
    public function checkAPI($hash)
    {
        if(empty($hash)) return false;

        $key = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('sso')
            ->andWhere('`key`')->eq('key')
            ->fetch('value');

        return $key == $hash;
    }

    /**
     * Get the total estimated man hours required.
     *
     * @param  array $storyID
     * @access public
     * @return string
     */
    public function getStorysEstimateHours($storyID)
    {
        return $this->dao->select('count(estimate) as estimate')->from(TABLE_STORY)->where('id')->in($storyID)->fetch('estimate');
    }

    /**
     * Get zentao.net data.
     *
     * @param  string $minTime
     * @access public
     * @return array
     */
    public function getZentaoData($minTime = '')
    {
        return $this->dao->select('type,params')->from(TABLE_BLOCK)
            ->where('account')->eq('system')
            ->andWhere('vision')->eq('rnd')
            ->andWhere('module')->eq('zentao')
            ->beginIF($minTime)->andWhere('source')->ge($minTime)->fi()
            ->andWhere('type')->in('plugin,patch,publicclass,news')
            ->fetchPairs('type');
    }

    /**
     * Set zentao data.
     *
     * @param  string $type
     * @param  string $params
     * @access public
     * @return void
     */
    public function setZentaoData($type = 'patch', $params = '')
    {
        $data = new stdclass();
        $data->account = 'system';
        $data->vision  = 'rnd';
        $data->module  = 'zentao';
        $data->type    = $type;
        $data->source  = date('Y-m-d');
        $data->params  = json_encode($params);

        $this->dao->replace(TABLE_BLOCK)->data($data)->exec();
    }
}
