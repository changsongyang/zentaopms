<?php
/**
 * The story module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: zh-cn.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
global $config;
$lang->story->create            = "提{$lang->productSRCommon}";
$lang->story->createStory       = "提{$lang->productSRCommon}";
$lang->story->createRequirement = "提{$lang->productSRCommon}";

$lang->story->requirement       = zget($lang, 'productURCommon', "用户需求");
$lang->story->story             = zget($lang, 'productSRCommon', "软件需求");
$lang->story->createStory       = '添加' . $lang->story->story;
$lang->story->createRequirement = '添加' . $lang->story->requirement;
$lang->story->affectedStories   = "影响的{$lang->story->story}";

$lang->story->batchCreate        = "批量创建";
$lang->story->change             = "变更";
$lang->story->changeAction       = "变更{$lang->productSRCommon}";
$lang->story->changed            = "{$lang->productSRCommon}变更";
$lang->story->assignTo           = '指派';
$lang->story->assignAction       = "指派{$lang->productSRCommon}";
$lang->story->review             = '评审';
$lang->story->reviewAction       = "评审{$lang->productSRCommon}";
$lang->story->needReview         = '需要评审';
$lang->story->batchReview        = '批量评审';
$lang->story->edit               = "编辑";
$lang->story->batchEdit          = "批量编辑";
$lang->story->subdivide          = '细分';
$lang->story->link               = '关联';
$lang->story->unlink             = '移除';
$lang->story->track              = '跟踪矩阵';
$lang->story->processStoryChange = '处理需求变更';
$lang->story->subdivideAction    = "细分{$lang->productSRCommon}";
$lang->story->splitRequirent     = '拆分';
$lang->story->close              = '关闭';
$lang->story->closeAction        = "关闭{$lang->productSRCommon}";
$lang->story->batchClose         = '批量关闭';
$lang->story->activate           = '激活';
$lang->story->activateAction     = "激活{$lang->productSRCommon}";
$lang->story->delete             = "删除";
$lang->story->deleteAction       = "删除{$lang->productSRCommon}";
$lang->story->view               = "{$lang->productSRCommon}详情";
$lang->story->setting            = "设置";
$lang->story->tasks              = "相关任务";
$lang->story->bugs               = "相关Bug";
$lang->story->cases              = "相关用例";
$lang->story->taskCount          = '任务数';
$lang->story->bugCount           = 'Bug数';
$lang->story->caseCount          = '用例数';
$lang->story->taskCountAB        = 'T';
$lang->story->bugCountAB         = 'B';
$lang->story->caseCountAB        = 'C';
$lang->story->linkStory          = "关联{$lang->productSRCommon}";
$lang->story->unlinkStory        = "移除相关{$lang->productSRCommon}";
$lang->story->export             = "导出数据";
$lang->story->exportAction       = "导出{$lang->productSRCommon}";
$lang->story->zeroCase           = "零用例{$lang->productSRCommon}";
$lang->story->zeroTask           = "只列零任务{$lang->productSRCommon}";
$lang->story->reportChart        = "统计报表";
$lang->story->reportAction       = "统计报表";
$lang->story->copyTitle          = "同{$lang->productSRCommon}名称";
$lang->story->batchChangePlan    = "批量修改计划";
$lang->story->batchChangeBranch  = "批量修改分支";
$lang->story->batchChangeStage   = "批量修改阶段";
$lang->story->batchAssignTo      = "批量指派";
$lang->story->batchChangeModule  = "批量修改模块";
$lang->story->viewAll            = '查看全部';
$lang->story->toTask             = '转任务';
$lang->story->batchToTask        = '批量转任务';
$lang->story->convertRelations   = '换算关系';

$lang->story->skipStory       = '需求：%s 为父需求，将不会被关闭。';
$lang->story->closedStory     = '需求：%s 已关闭，将不会被关闭。';
$lang->story->batchToTaskTips = "此操作会创建与所选{$lang->productSRCommon}同名的任务，并将{$lang->productSRCommon}关联到任务中，已关闭的需求不会转为任务。";
$lang->story->successToTask   = '批量转任务成功';

$lang->story->common         = $lang->productSRCommon;
$lang->story->id             = '编号';
$lang->story->parent         = '父需求';
$lang->story->product        = "所属{$lang->productCommon}";
$lang->story->project        = "所属项目";
$lang->story->branch         = "分支/平台";
$lang->story->module         = '所属模块';
$lang->story->moduleAB       = '模块';
$lang->story->source         = "来源";
$lang->story->sourceNote     = '来源备注';
$lang->story->fromBug        = '来源Bug';
$lang->story->title          = "{$lang->productSRCommon}名称";
$lang->story->type           = "类型";
$lang->story->color          = '标题颜色';
$lang->story->toBug          = '转Bug';
$lang->story->spec           = "描述";
$lang->story->assign         = '指派给';
$lang->story->verify         = '验收标准';
$lang->story->pri            = '优先级';
$lang->story->estimate       = "预计{$lang->hourCommon}";
$lang->story->estimateAB     = '预计';
$lang->story->hour           = $lang->hourCommon;
$lang->story->status         = '当前状态';
$lang->story->subStatus      = '子状态';
$lang->story->stage          = '所处阶段';
$lang->story->stageAB        = '阶段';
$lang->story->stagedBy       = '设置阶段者';
$lang->story->mailto         = '抄送给';
$lang->story->openedBy       = '由谁创建';
$lang->story->openedDate     = '创建日期';
$lang->story->assignedTo     = '指派给';
$lang->story->assignedDate   = '指派日期';
$lang->story->lastEditedBy   = '最后修改';
$lang->story->lastEditedDate = '最后修改日期';
$lang->story->closedBy       = '由谁关闭';
$lang->story->closedDate     = '关闭日期';
$lang->story->closedReason   = '关闭原因';
$lang->story->rejectedReason = '拒绝原因';
$lang->story->reviewedBy     = '由谁评审';
$lang->story->reviewedDate   = '评审时间';
$lang->story->version        = '版本号';
$lang->story->plan           = "所属计划";
$lang->story->planAB         = '计划';
$lang->story->comment        = '备注';
$lang->story->children       = "子{$lang->productSRCommon}";
$lang->story->childrenAB     = "子";
$lang->story->linkStories    = "相关{$lang->productSRCommon}";
$lang->story->childStories   = "细分{$lang->productSRCommon}";
$lang->story->duplicateStory = "重复{$lang->productSRCommon}ID";
$lang->story->reviewResult   = '评审结果';
$lang->story->preVersion     = '之前版本';
$lang->story->keywords       = '关键词';
$lang->story->newStory       = "继续添加{$lang->productSRCommon}";
$lang->story->colorTag       = '颜色标签';
$lang->story->files          = '附件';
$lang->story->copy           = "复制{$lang->productSRCommon}";
$lang->story->total          = "总{$lang->productSRCommon}";
$lang->story->allStories     = "所有{$lang->productSRCommon}";
$lang->story->draft          = '草稿';
$lang->story->unclosed       = '未关闭';
$lang->story->deleted        = '已删除';
$lang->story->released       = "已发布{$lang->productSRCommon}数";
$lang->story->URChanged      = '用需变更';
$lang->story->design         = '相关设计';
$lang->story->case           = '相关用例';
$lang->story->bug            = '相关Bug';
$lang->story->repoCommit     = '相关提交';
$lang->story->noRequirement  = '无需求';
$lang->story->one            = '一个';
$lang->story->field          = '同步的字段';
$lang->story->completeRate   = '完成率';

$lang->story->ditto       = '同上';
$lang->story->dittoNotice = "该{$lang->productSRCommon}与上一{$lang->productSRCommon}不属于同一产品！";

$lang->story->needNotReviewList[0] = '需要评审';
$lang->story->needNotReviewList[1] = '不需要评审';

$lang->story->useList[0] = '不使用';
$lang->story->useList[1] = '使用';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = '草稿';
$lang->story->statusList['active']    = '激活';
$lang->story->statusList['closed']    = '已关闭';
$lang->story->statusList['changed']   = '已变更';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未开始';
$lang->story->stageList['planned']    = "已计划";
$lang->story->stageList['projected']  = '已立项';
$lang->story->stageList['developing'] = '研发中';
$lang->story->stageList['developed']  = '研发完毕';
$lang->story->stageList['testing']    = '测试中';
$lang->story->stageList['tested']     = '测试完毕';
$lang->story->stageList['verified']   = '已验收';
$lang->story->stageList['released']   = '已发布';
$lang->story->stageList['closed']     = '已关闭';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '已完成';
$lang->story->reasonList['subdivided'] = '已细分';
$lang->story->reasonList['duplicate']  = '重复';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
$lang->story->reasonList['bydesign']   = '设计如此';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = '确认通过';
$lang->story->reviewResultList['revert']  = '撤销变更';
$lang->story->reviewResultList['clarify'] = '有待明确';
$lang->story->reviewResultList['reject']  = '拒绝';

$lang->story->reviewList[0] = '否';
$lang->story->reviewList[1] = '是';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = '客户';
$lang->story->sourceList['user']       = '用户';
$lang->story->sourceList['po']         = $lang->productCommon . '经理';
$lang->story->sourceList['market']     = '市场';
$lang->story->sourceList['service']    = '客服';
$lang->story->sourceList['operation']  = '运营';
$lang->story->sourceList['support']    = '技术支持';
$lang->story->sourceList['competitor'] = '竞争对手';
$lang->story->sourceList['partner']    = '合作伙伴';
$lang->story->sourceList['dev']        = '开发人员';
$lang->story->sourceList['tester']     = '测试人员';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = '论坛';
$lang->story->sourceList['other']      = '其他';

$lang->story->priList[]  = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = '不变更';
$lang->story->changeList['yes'] = '变更';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = "{$lang->productSRCommon}的一生";
$lang->story->legendRelated        = '相关信息';
$lang->story->legendMailto         = '抄送给';
$lang->story->legendAttatch        = '附件';
$lang->story->legendProjectAndTask = $lang->executionCommon . '任务';
$lang->story->legendBugs           = '相关Bug';
$lang->story->legendFromBug        = '来源Bug';
$lang->story->legendCases          = '相关用例';
$lang->story->legendLinkStories    = "相关{$lang->productSRCommon}";
$lang->story->legendChildStories   = "细分{$lang->productSRCommon}";
$lang->story->legendSpec           = "{$lang->productSRCommon}描述";
$lang->story->legendVerify         = '验收标准';
$lang->story->legendMisc           = '其他相关';

$lang->story->lblChange            = "变更{$lang->productSRCommon}";
$lang->story->lblReview            = "评审{$lang->productSRCommon}";
$lang->story->lblActivate          = "激活{$lang->productSRCommon}";
$lang->story->lblClose             = "关闭{$lang->productSRCommon}";
$lang->story->lblTBC               = '任务Bug用例';

$lang->story->checkAffection       = '影响范围';
$lang->story->affectedProjects     = '影响的' . $lang->executionCommon;
$lang->story->affectedBugs         = '影响的Bug';
$lang->story->affectedCases        = '影响的用例';

$lang->story->specTemplate          = "建议参考的模板：作为一名<某种类型的用户>，我希望<达成某些目的>，这样可以<开发的价值>。";
$lang->story->needNotReview         = '不需要评审';
$lang->story->successSaved          = "{$lang->productSRCommon}成功添加，";
$lang->story->confirmDelete         = "您确认删除该{$lang->productSRCommon}吗?";
$lang->story->errorEmptyChildStory  = "『细分{$lang->productSRCommon}』不能为空。";
$lang->story->errorNotSubdivide     = "状态不是激活，或者阶段不是未开始的{$lang->productSRCommon}，或者是子需求，则不能细分。";
$lang->story->mustChooseResult      = '必须选择评审结果';
$lang->story->mustChoosePreVersion  = '必须选择回溯的版本';
$lang->story->noStory               = "暂时没有{$lang->productSRCommon}。";
$lang->story->noRequirement         = "暂时没有{$lang->productURCommon}。";
$lang->story->ignoreChangeStage     = "{$lang->productSRCommon} %s 为草稿状态或已关闭状态，没有修改其阶段。";
$lang->story->cannotDeleteParent    = "不能删除父{$lang->productSRCommon}";
$lang->story->moveChildrenTips      = "修改父{$lang->productSRCommon}的所属产品会将其下的子{$lang->productSRCommon}也移动到所选产品下。";
$lang->story->changeTips            = '该软件需求关联的用户需求有变更，点击“不变更”忽略此条变更，点击“变更”来进行该软件需求的变更。';

$lang->story->form = new stdclass();
$lang->story->form->area      = "该{$lang->productSRCommon}所属范围";
$lang->story->form->desc      = "描述及标准，什么{$lang->productSRCommon}？如何验收？";
$lang->story->form->resource  = '资源分配，有谁完成？需要多少时间？';
$lang->story->form->file      = "附件，如果该{$lang->productSRCommon}有相关文件，请点此上传。";

$lang->story->action = new stdclass();
$lang->story->action->reviewed            = array('main' => '$date, 由 <strong>$actor</strong> 记录评审结果，结果为 <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->story->action->closed              = array('main' => '$date, 由 <strong>$actor</strong> 关闭，原因为 <strong>$extra</strong> $appendLink。', 'extra' => 'reasonList');
$lang->story->action->linked2plan         = array('main' => '$date, 由 <strong>$actor</strong> 关联到计划 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromplan    = array('main' => '$date, 由 <strong>$actor</strong> 从计划 <strong>$extra</strong> 移除。');
$lang->story->action->linked2project      = array('main' => '$date, 由 <strong>$actor</strong> 关联到' . $lang->executionCommon . ' <strong>$extra</strong>。');
$lang->story->action->unlinkedfromproject = array('main' => '$date, 由 <strong>$actor</strong> 从' . $lang->executionCommon . ' <strong>$extra</strong> 移除。');
$lang->story->action->linked2build        = array('main' => '$date, 由 <strong>$actor</strong> 关联到版本 <strong>$extra</strong>。');
$lang->story->action->unlinkedfrombuild   = array('main' => '$date, 由 <strong>$actor</strong> 从版本 <strong>$extra</strong> 移除。');
$lang->story->action->linked2release      = array('main' => '$date, 由 <strong>$actor</strong> 关联到发布 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromrelease = array('main' => '$date, 由 <strong>$actor</strong> 从发布 <strong>$extra</strong> 移除。');
$lang->story->action->linkrelatedstory    = array('main' => "\$date, 由 <strong>\$actor</strong> 关联相关{$lang->productSRCommon} <strong>\$extra</strong>。");
$lang->story->action->subdividestory      = array('main' => "\$date, 由 <strong>\$actor</strong> 细分为{$lang->productSRCommon}   <strong>\$extra</strong>。");
$lang->story->action->unlinkrelatedstory  = array('main' => "\$date, 由 <strong>\$actor</strong> 移除相关{$lang->productSRCommon} <strong>\$extra</strong>。");
$lang->story->action->unlinkchildstory    = array('main' => "\$date, 由 <strong>\$actor</strong> 移除细分{$lang->productSRCommon} <strong>\$extra</strong>。");

/* 统计报表。*/
$lang->story->report = new stdclass();
$lang->story->report->common = '报表';
$lang->story->report->select = '请选择报表类型';
$lang->story->report->create = '生成报表';
$lang->story->report->value  = "{$lang->productSRCommon}数";

$lang->story->report->charts['storysPerProduct']        = $lang->productCommon . "{$lang->productSRCommon}数量";
$lang->story->report->charts['storysPerModule']         = "模块{$lang->productSRCommon}数量";
$lang->story->report->charts['storysPerSource']         = "按{$lang->productSRCommon}来源统计";
$lang->story->report->charts['storysPerPlan']           = "按计划进行统计";
$lang->story->report->charts['storysPerStatus']         = '按状态进行统计';
$lang->story->report->charts['storysPerStage']          = '按所处阶段进行统计';
$lang->story->report->charts['storysPerPri']            = '按优先级进行统计';
$lang->story->report->charts['storysPerEstimate']       = "按预计{$lang->hourCommon}进行统计";
$lang->story->report->charts['storysPerOpenedBy']       = '按由谁创建来进行统计';
$lang->story->report->charts['storysPerAssignedTo']     = '按当前指派来进行统计';
$lang->story->report->charts['storysPerClosedReason']   = '按关闭原因来进行统计';
$lang->story->report->charts['storysPerChange']         = '按变更次数来进行统计';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph   = new stdclass();
$lang->story->report->options->type    = 'pie';
$lang->story->report->options->width   = 500;
$lang->story->report->options->height  = 140;

$lang->story->report->storysPerProduct      = new stdclass();
$lang->story->report->storysPerModule       = new stdclass();
$lang->story->report->storysPerSource       = new stdclass();
$lang->story->report->storysPerPlan         = new stdclass();
$lang->story->report->storysPerStatus       = new stdclass();
$lang->story->report->storysPerStage        = new stdclass();
$lang->story->report->storysPerPri          = new stdclass();
$lang->story->report->storysPerOpenedBy     = new stdclass();
$lang->story->report->storysPerAssignedTo   = new stdclass();
$lang->story->report->storysPerClosedReason = new stdclass();
$lang->story->report->storysPerEstimate     = new stdclass();
$lang->story->report->storysPerChange       = new stdclass();

$lang->story->report->storysPerProduct->item      = $lang->productCommon;
$lang->story->report->storysPerModule->item       = '模块';
$lang->story->report->storysPerSource->item       = '来源';
$lang->story->report->storysPerPlan->item         = '计划';
$lang->story->report->storysPerStatus->item       = '状态';
$lang->story->report->storysPerStage->item        = '阶段';
$lang->story->report->storysPerPri->item          = '优先级';
$lang->story->report->storysPerOpenedBy->item     = '由谁创建';
$lang->story->report->storysPerAssignedTo->item   = '指派给';
$lang->story->report->storysPerClosedReason->item = '原因';
$lang->story->report->storysPerEstimate->item     = "预计{$lang->hourCommon}";
$lang->story->report->storysPerChange->item       = '变更次数';

$lang->story->report->storysPerProduct->graph      = new stdclass();
$lang->story->report->storysPerModule->graph       = new stdclass();
$lang->story->report->storysPerSource->graph       = new stdclass();
$lang->story->report->storysPerPlan->graph         = new stdclass();
$lang->story->report->storysPerStatus->graph       = new stdclass();
$lang->story->report->storysPerStage->graph        = new stdclass();
$lang->story->report->storysPerPri->graph          = new stdclass();
$lang->story->report->storysPerOpenedBy->graph     = new stdclass();
$lang->story->report->storysPerAssignedTo->graph   = new stdclass();
$lang->story->report->storysPerClosedReason->graph = new stdclass();
$lang->story->report->storysPerEstimate->graph     = new stdclass();
$lang->story->report->storysPerChange->graph       = new stdclass();

$lang->story->report->storysPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storysPerModule->graph->xAxisName       = '模块';
$lang->story->report->storysPerSource->graph->xAxisName       = '来源';
$lang->story->report->storysPerPlan->graph->xAxisName         = '计划';
$lang->story->report->storysPerStatus->graph->xAxisName       = '状态';
$lang->story->report->storysPerStage->graph->xAxisName        = '所处阶段';
$lang->story->report->storysPerPri->graph->xAxisName          = '优先级';
$lang->story->report->storysPerOpenedBy->graph->xAxisName     = '由谁创建';
$lang->story->report->storysPerAssignedTo->graph->xAxisName   = '当前指派';
$lang->story->report->storysPerClosedReason->graph->xAxisName = '关闭原因';
$lang->story->report->storysPerEstimate->graph->xAxisName     = '预计时间';
$lang->story->report->storysPerChange->graph->xAxisName       = '变更次数';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = '选择评审人...';

$lang->story->notice = new stdClass();
$lang->story->notice->closed = "您选择的{$lang->productSRCommon}已经被关闭了！";

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = '所属模块';
$lang->story->convertToTask->fieldList['spec']       = "{$lang->productSRCommon}描述";
$lang->story->convertToTask->fieldList['pri']        = '优先级';
$lang->story->convertToTask->fieldList['mailto']     = '抄送给';
$lang->story->convertToTask->fieldList['assignedTo'] = '指派给';
