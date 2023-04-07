<?php
/**
 * The group module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: zh-cn.php 4719 2013-05-03 02:20:28Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->group->common             = '权限分组';
$lang->group->browse             = '浏览分组';
$lang->group->create             = '新增分组';
$lang->group->edit               = '编辑分组';
$lang->group->copy               = '复制分组';
$lang->group->delete             = '删除分组';
$lang->group->manageView         = '视野维护';
$lang->group->managePriv         = '权限维护';
$lang->group->managePrivByGroup  = '权限维护';
$lang->group->managePrivByModule = '按模块分配权限';
$lang->group->byModuleTips       = '<span class="tips">（可以按住Shift或者Ctrl键进行多选）</span>';
$lang->group->allTips            = '勾选此项后，管理员可管理系统中所有对象，包括后续创建的对象。';
$lang->group->manageMember       = '成员维护';
$lang->group->manageProjectAdmin = "维护{$lang->projectCommon}管理员";
$lang->group->editManagePriv     = '维护权限';
$lang->group->confirmDelete      = '您确定删除“%s”用户分组吗？';
$lang->group->confirmDeleteAB    = '您确定删除吗？';
$lang->group->successSaved       = '成功保存';
$lang->group->errorNotSaved      = '没有保存，请确认选择了权限数据。';
$lang->group->viewList           = '可访问视图';
$lang->group->object             = '可管理对象';
$lang->group->manageProgram      = '可管理项目集';
$lang->group->manageProject      = '可管理' . $lang->projectCommon;
$lang->group->manageExecution    = '可管理' . $lang->execution->common;
$lang->group->manageProduct      = '可管理' . $lang->productCommon;
$lang->group->programList        = '可访问项目集';
$lang->group->productList        = '可访问' . $lang->productCommon;
$lang->group->projectList        = '可访问' . $lang->projectCommon;
$lang->group->executionList      = "可访问{$lang->execution->common}";
$lang->group->dynamic            = '可查看动态';
$lang->group->noticeVisit        = '空代表没有访问限制';
$lang->group->noticeNoChecked    = '请先勾选权限！';
$lang->group->noneProgram        = "暂时没有项目集";
$lang->group->noneProduct        = "暂时没有{$lang->productCommon}";
$lang->group->noneExecution      = "暂时没有{$lang->execution->common}";
$lang->group->project            = $lang->projectCommon;
$lang->group->group              = '分组';
$lang->group->more               = '更多';
$lang->group->allCheck           = '全部';
$lang->group->noGroup            = '暂时没有分组。';
$lang->group->repeat             = "『%s』已经有『%s』这条记录了，请调整后再试。";
$lang->group->noneProject        = '暂时没有' . $lang->projectCommon;
$lang->group->createPriv         = '新增权限';
$lang->group->editPriv           = '编辑权限';
$lang->group->deletePriv         = '删除权限';
$lang->group->privName           = '权限名称';
$lang->group->privDesc           = '描述';
$lang->group->add                = '添加';
$lang->group->privModuleName     = '模块名';
$lang->group->privMethodName     = '方法名';
$lang->group->privView           = '所属视图';
$lang->group->privModule         = '所属模块';
$lang->group->repeatPriv         = '同一模块名下的方法名不能相同，请修改后再试。';
$lang->group->dependPrivTips     = '此处所列的是左侧选中权限的依赖权限列表，是必须要分配的。';
$lang->group->recommendPrivTips  = '此处所列的是左侧选中权限的推荐权限列表，推荐分配。';
$lang->group->dependPrivsSaveTip = '权限及依赖权限保存成功';

$lang->group->batchActions              = '批量操作';
$lang->group->batchSetDependency        = '批量设置依赖';
$lang->group->batchSetRecommendation    = '批量设置推荐';
$lang->group->batchDeleteDependency     = '批量删除依赖';
$lang->group->batchDeleteRecommendation = '批量删除推荐';
$lang->group->managePrivPackage         = '维护权限包';
$lang->group->createPrivPackage         = '新增权限包';
$lang->group->editPrivPackage           = '编辑权限包';
$lang->group->deletePrivPackage         = '删除权限包';
$lang->group->sortPrivPackages          = '权限包排序';
$lang->group->addRecommendation         = '添加推荐';
$lang->group->addDependent              = '添加依赖';
$lang->group->deleteRecommendation      = '删除推荐';
$lang->group->deleteDependent           = '删除依赖';
$lang->group->selectedPrivs             = '选中权限：%s';
$lang->group->selectModule              = '选择模块';
$lang->group->recommendPrivs            = '推荐的权限';
$lang->group->dependentPrivs            = '依赖的权限';
$lang->group->addRelation               = '添加权限关联';
$lang->group->deleteRelation            = '删除权限关联';
$lang->group->batchDeleteRelation       = '批量删除权限关联';
$lang->group->batchChangePackage        = '批量修改权限包';

$lang->group->id         = '编号';
$lang->group->name       = '分组名称';
$lang->group->desc       = '分组描述';
$lang->group->role       = '角色';
$lang->group->acl        = '权限';
$lang->group->users      = '用户列表';
$lang->group->module     = '模块';
$lang->group->method     = '方法';
$lang->group->priv       = '权限';
$lang->group->option     = '选项';
$lang->group->inside     = '组内用户';
$lang->group->outside    = '组外用户';
$lang->group->limited    = '受限用户组';
$lang->group->general    = '通用';
$lang->group->all        = '所有权限';
$lang->group->config     = '配置';
$lang->group->unassigned = '未分配';
$lang->group->view       = '视图';

if(!isset($lang->privpackage)) $lang->privpackage = new stdclass();
$lang->privpackage->common = '权限包';
$lang->privpackage->id     = '编号';
$lang->privpackage->name   = '权限包名称';
$lang->privpackage->module = '所属模块';
$lang->privpackage->desc   = '权限包说明';
$lang->privpackage->belong = '所属权限包';

$lang->group->copyOptions['copyPriv'] = '复制权限';
$lang->group->copyOptions['copyUser'] = '复制用户';

$lang->group->versions['']           = '修改历史';
$lang->group->versions['16_5_beta1'] = '禅道16.5.beta1';
$lang->group->versions['16_4']       = '禅道16.4';
$lang->group->versions['16_3']       = '禅道16.3';
$lang->group->versions['16_2']       = '禅道16.2';
$lang->group->versions['16_1']       = '禅道16.1';
$lang->group->versions['16_0']       = '禅道16.0';
$lang->group->versions['16_0_beta1'] = '禅道16.0.beta1';
$lang->group->versions['15_8']       = '禅道15.8';
$lang->group->versions['15_7']       = '禅道15.7';
$lang->group->versions['15_0_rc1']   = '禅道15.0.rc1';
$lang->group->versions['12_5']       = '禅道12.5';
$lang->group->versions['12_3']       = '禅道12.3';
$lang->group->versions['11_6_2']     = '禅道11.6.2';
$lang->group->versions['10_6']       = '禅道10.6';
$lang->group->versions['10_1']       = '禅道10.1';
$lang->group->versions['10_0_alpha'] = '禅道10.0.alpha';
$lang->group->versions['9_8']        = '禅道9.8';
$lang->group->versions['9_6']        = '禅道9.6';
$lang->group->versions['9_5']        = '禅道9.5';
$lang->group->versions['9_2']        = '禅道9.2';
$lang->group->versions['9_1']        = '禅道9.1';
$lang->group->versions['9_0']        = '禅道9.0';
$lang->group->versions['8_4']        = '禅道8.4';
$lang->group->versions['8_3']        = '禅道8.3';
$lang->group->versions['8_2_beta']   = '禅道8.2.beta';
$lang->group->versions['8_0_1']      = '禅道8.0.1';
$lang->group->versions['8_0']        = '禅道8.0';
$lang->group->versions['7_4_beta']   = '禅道7.4.beta';
$lang->group->versions['7_3']        = '禅道7.3';
$lang->group->versions['7_2']        = '禅道7.2';
$lang->group->versions['7_1']        = '禅道7.1';
$lang->group->versions['6_4']        = '禅道6.4';
$lang->group->versions['6_3']        = '禅道6.3';
$lang->group->versions['6_2']        = '禅道6.2';
$lang->group->versions['6_1']        = '禅道6.1';
$lang->group->versions['5_3']        = '禅道5.3';
$lang->group->versions['5_1']        = '禅道5.1';
$lang->group->versions['5_0_beta2']  = '禅道5.0.beta2';
$lang->group->versions['5_0_beta1']  = '禅道5.0.beta1';
$lang->group->versions['4_3_beta']   = '禅道4.3.beta';
$lang->group->versions['4_2_beta']   = '禅道4.2.beta';
$lang->group->versions['4_1']        = '禅道4.1';
$lang->group->versions['4_0_1']      = '禅道4.0.1';
$lang->group->versions['4_0']        = '禅道4.0';
$lang->group->versions['4_0_beta2']  = '禅道4.0.beta2';
$lang->group->versions['4_0_beta1']  = '禅道4.0.beta1';
$lang->group->versions['3_3']        = '禅道3.3';
$lang->group->versions['3_2_1']      = '禅道3.2.1';
$lang->group->versions['3_2']        = '禅道3.2';
$lang->group->versions['3_1']        = '禅道3.1';
$lang->group->versions['3_0_beta2']  = '禅道3.0.beta2';
$lang->group->versions['3_0_beta1']  = '禅道3.0.beta1';
$lang->group->versions['2_4']        = '禅道2.4';
$lang->group->versions['2_3']        = '禅道2.3';
$lang->group->versions['2_2']        = '禅道2.2';
$lang->group->versions['2_1']        = '禅道2.1';
$lang->group->versions['2_0']        = '禅道2.0';
$lang->group->versions['1_5']        = '禅道1.5';
$lang->group->versions['1_4']        = '禅道1.4';
$lang->group->versions['1_3']        = '禅道1.3';
$lang->group->versions['1_2']        = '禅道1.2';
$lang->group->versions['1_1']        = '禅道1.1';
$lang->group->versions['1_0_1']      = '禅道1.0.1';

include (dirname(__FILE__) . '/resource.php');
