<?php
/**
 * The all avaliabe actions in ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */

/* Module order. */
$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';

$lang->moduleOrder[15]  = 'program';
$lang->moduleOrder[20]  = 'personnel';
$lang->moduleOrder[25]  = 'product';
$lang->moduleOrder[30]  = 'story';
$lang->moduleOrder[35]  = 'productplan';
$lang->moduleOrder[40]  = 'release';
$lang->moduleOrder[45]  = 'projectstory';

$lang->moduleOrder[50]  = 'project';
$lang->moduleOrder[55]  = 'task';
$lang->moduleOrder[60]  = 'build';

$lang->moduleOrder[65]  = 'qa';
$lang->moduleOrder[70]  = 'bug';
$lang->moduleOrder[75]  = 'testcase';
$lang->moduleOrder[80]  = 'testtask';
$lang->moduleOrder[85]  = 'testsuite';
$lang->moduleOrder[90]  = 'testreport';
$lang->moduleOrder[95]  = 'caselib';

$lang->moduleOrder[100]  = 'doc';
$lang->moduleOrder[105]  = 'report';

$lang->moduleOrder[110] = 'company';
$lang->moduleOrder[115] = 'dept';
$lang->moduleOrder[120] = 'group';
$lang->moduleOrder[125] = 'user';

$lang->moduleOrder[130] = 'admin';
$lang->moduleOrder[135] = 'extension';
$lang->moduleOrder[140] = 'custom';
$lang->moduleOrder[145] = 'action';

$lang->moduleOrder[150] = 'mail';
$lang->moduleOrder[155] = 'svn';
$lang->moduleOrder[160] = 'git';
$lang->moduleOrder[165] = 'search';
$lang->moduleOrder[170] = 'tree';
$lang->moduleOrder[175] = 'api';
$lang->moduleOrder[180] = 'file';
$lang->moduleOrder[185] = 'misc';
$lang->moduleOrder[190] = 'backup';
$lang->moduleOrder[195] = 'cron';
$lang->moduleOrder[200] = 'dev';
$lang->moduleOrder[205] = 'message';

$lang->moduleOrder[210] = 'design';
$lang->moduleOrder[215] = 'programplan';
$lang->moduleOrder[220] = 'issue';
$lang->moduleOrder[225] = 'risk';
$lang->moduleOrder[230] = 'stage';

$lang->moduleOrder[235] = 'budget';
$lang->moduleOrder[240] = 'workestimation';
$lang->moduleOrder[245] = 'durationestimation';

$lang->moduleOrder[250] = 'subject';
$lang->moduleOrder[255] = 'holiday';

$lang->resource = new stdclass();

/* Index module. */
$lang->resource->index = new stdclass();
$lang->resource->index->index = 'index';

$lang->index->methodOrder[0] = 'index';

/* My module. */
$lang->resource->my = new stdclass();
$lang->resource->my->index           = 'index';
$lang->resource->my->todo            = 'todo';
$lang->resource->my->calendar        = 'calendar';
$lang->resource->my->work            = 'work';
$lang->resource->my->contribute      = 'contribute';
$lang->resource->my->project         = 'project';
$lang->resource->my->profile         = 'profile';
$lang->resource->my->uploadAvatar    = 'uploadAvatar';
$lang->resource->my->preference      = 'preference';
$lang->resource->my->dynamic         = 'dynamic';
$lang->resource->my->editProfile     = 'editProfile';
$lang->resource->my->changePassword  = 'changePassword';
$lang->resource->my->manageContacts  = 'manageContacts';
$lang->resource->my->deleteContacts  = 'deleteContacts';
$lang->resource->my->score           = 'score';
$lang->resource->my->unbind          = 'unbind';
$lang->resource->my->team            = 'team';

$lang->my->methodOrder[1]  = 'index';
$lang->my->methodOrder[5]  = 'todo';
$lang->my->methodOrder[10] = 'work';
$lang->my->methodOrder[15] = 'contribute';
$lang->my->methodOrder[20] = 'project';
$lang->my->methodOrder[25] = 'profile';
$lang->my->methodOrder[30] = 'uploadAvatar';
$lang->my->methodOrder[35] = 'preference';
$lang->my->methodOrder[40] = 'dynamic';
$lang->my->methodOrder[45] = 'editProfile';
$lang->my->methodOrder[50] = 'changePassword';
$lang->my->methodOrder[55] = 'manageContacts';
$lang->my->methodOrder[60] = 'deleteContacts';
$lang->my->methodOrder[65] = 'score';
$lang->my->methodOrder[70] = 'unbind';
$lang->my->methodOrder[75] = 'team';

/* Todo. */
$lang->resource->todo = new stdclass();
$lang->resource->todo->create       = 'create';
$lang->resource->todo->createcycle  = 'createCycle';
$lang->resource->todo->batchCreate  = 'batchCreate';
$lang->resource->todo->edit         = 'edit';
$lang->resource->todo->batchEdit    = 'batchEdit';
$lang->resource->todo->view         = 'view';
$lang->resource->todo->delete       = 'delete';
$lang->resource->todo->export       = 'export';
$lang->resource->todo->start        = 'start';
$lang->resource->todo->finish       = 'finish';
$lang->resource->todo->batchFinish  = 'batchFinish';
$lang->resource->todo->import2Today = 'import2Today';
$lang->resource->todo->assignTo     = 'assignAction';
$lang->resource->todo->activate     = 'activate';
$lang->resource->todo->close        = 'close';
$lang->resource->todo->batchClose   = 'batchClose';

$lang->todo->methodOrder[5]  = 'create';
$lang->todo->methodOrder[10] = 'createCycle';
$lang->todo->methodOrder[15] = 'batchCreate';
$lang->todo->methodOrder[20] = 'edit';
$lang->todo->methodOrder[25] = 'batchEdit';
$lang->todo->methodOrder[30] = 'view';
$lang->todo->methodOrder[35] = 'delete';
$lang->todo->methodOrder[40] = 'export';
$lang->todo->methodOrder[45] = 'start';
$lang->todo->methodOrder[50] = 'finish';
$lang->todo->methodOrder[55] = 'batchFinish';
$lang->todo->methodOrder[60] = 'import2Today';
$lang->todo->methodOrder[65] = 'assignTo';
$lang->todo->methodOrder[70] = 'activate';
$lang->todo->methodOrder[75] = 'close';
$lang->todo->methodOrder[80] = 'batchClose';

/* Program. */
$lang->resource->program = new stdclass();
$lang->resource->program->createGuide          = 'createGuide';
//$lang->resource->program->PGMIndex             = 'PGMIndex';
$lang->resource->program->PGMBrowse            = 'PGMBrowse';
$lang->resource->program->PGMProduct           = 'PGMProduct';
$lang->resource->program->PGMCreate            = 'PGMCreate';
$lang->resource->program->PGMEdit              = 'PGMEdit';
$lang->resource->program->PGMStart             = 'PGMStart';
$lang->resource->program->PGMSuspend           = 'PGMSuspend';
$lang->resource->program->PGMActivate          = 'PGMActivate';
$lang->resource->program->PGMClose             = 'PGMClose';
$lang->resource->program->PGMDelete            = 'PGMDelete';
$lang->resource->program->PGMProject           = 'PGMProject';
$lang->resource->program->PGMStakeholder       = 'PGMStakeholder';
$lang->resource->program->createStakeholder    = 'createStakeholder';
$lang->resource->program->unlinkStakeholder    = 'unlinkStakeholder';
$lang->resource->program->export               = 'export';
$lang->resource->program->index                = 'PRJIndex';
$lang->resource->program->PRJBrowse            = 'PRJBrowse';
$lang->resource->program->PRJProgramTitle      = 'PRJModuleOpen';
$lang->resource->program->PRJCreate            = 'PRJCreate';
$lang->resource->program->PRJEdit              = 'PRJEdit';
$lang->resource->program->PRJGroup             = 'PRJGroup';
$lang->resource->program->PRJCreateGroup       = 'PRJCreateGroup';
$lang->resource->program->PRJManageView        = 'PRJManageView';
$lang->resource->program->PRJManagePriv        = 'PRJManagePriv';
$lang->resource->program->PRJManageMembers     = 'PRJManageMembers';
$lang->resource->program->PRJManageGroupMember = 'PRJManageGroupMember';
$lang->resource->program->PRJCopyGroup         = 'PRJCopyGroup';
$lang->resource->program->PRJEditGroup         = 'PRJEditGroup';
$lang->resource->program->PRJStart             = 'PRJStart';
$lang->resource->program->PRJSuspend           = 'PRJSuspend';
$lang->resource->program->PRJClose             = 'PRJClose';
$lang->resource->program->PRJActivate          = 'PRJActivate';
$lang->resource->program->PRJDelete            = 'PRJDelete';
$lang->resource->program->PRJView              = 'PRJView';
$lang->resource->program->PRJWhitelist         = 'PRJWhitelist';
$lang->resource->program->PRJAddWhitelist      = 'PRJAddWhitelist';
$lang->resource->program->unbindWhielist       = 'unbindWhielist';
$lang->resource->program->PRJManageProducts    = 'PRJManageProducts';
$lang->resource->program->view                 = 'view';

$lang->program->methodOrder[0]   = 'createGuide';
//$lang->program->methodOrder[5]   = 'PGMIndex';
$lang->program->methodOrder[10]  = 'PGMBrowse';
$lang->program->methodOrder[15]  = 'PGMProduct';
$lang->program->methodOrder[20]  = 'PGMCreate';
$lang->program->methodOrder[25]  = 'PGMEdit';
$lang->program->methodOrder[30]  = 'PGMStart';
$lang->program->methodOrder[35]  = 'PGMSuspend';
$lang->program->methodOrder[40]  = 'PGMClose';
$lang->program->methodOrder[45]  = 'PGMActivate';
$lang->program->methodOrder[55]  = 'PGMDelete';
$lang->program->methodOrder[60]  = 'PGMProject';
$lang->program->methodOrder[65]  = 'PGMStakeholder';
$lang->program->methodOrder[67]  = 'createStakeholder';
$lang->program->methodOrder[70]  = 'unlinkStakeholder';
$lang->program->methodOrder[75]  = 'export';
$lang->program->methodOrder[80]  = 'PRJIndex';
$lang->program->methodOrder[85]  = 'PRJBrowse';
$lang->program->methodOrder[90]  = 'PRJProgramTitle';
$lang->program->methodOrder[95]  = 'PRJCreate';
$lang->program->methodOrder[100] = 'PRJEdit';
$lang->program->methodOrder[105] = 'PRJGroup';
$lang->program->methodOrder[110] = 'PRJCreateGroup';
$lang->program->methodOrder[115] = 'PRJManageView';
$lang->program->methodOrder[120] = 'PRJManagePriv';
$lang->program->methodOrder[125] = 'PRJManageMembers';
$lang->program->methodOrder[130] = 'PRJManageGroupMember';
$lang->program->methodOrder[135] = 'PRJCopyGroup';
$lang->program->methodOrder[140] = 'PRJEditGroup';
$lang->program->methodOrder[145] = 'PRJStart';
$lang->program->methodOrder[150] = 'PRJSuspend';
$lang->program->methodOrder[155] = 'PRJClose';
$lang->program->methodOrder[160] = 'PRJActivate';
$lang->program->methodOrder[165] = 'PRJUpdateOrder';
$lang->program->methodOrder[170] = 'PRJDelete';
$lang->program->methodOrder[175] = 'PRJView';
$lang->program->methodOrder[180] = 'PRJWhitelist';
$lang->program->methodOrder[185] = 'PRJAddWhitelist';
$lang->program->methodOrder[190] = 'unbindWhielist';
$lang->program->methodOrder[195] = 'PRJManageProducts';
$lang->program->methodOrder[200] = 'view';

/* Personnel . */
$lang->resource->personnel = new stdclass();
$lang->resource->personnel->accessible     = 'accessible';
$lang->resource->personnel->putInto        = 'putInto';
$lang->resource->personnel->whitelist      = 'whitelist';
$lang->resource->personnel->addWhitelist   = 'addWhitelist';
$lang->resource->personnel->unbindWhielist = 'unbindWhielist';

$lang->personnel->methodOrder[5]  = 'accessible';
$lang->personnel->methodOrder[10] = 'putInto';
$lang->personnel->methodOrder[15] = 'whitelist';
$lang->personnel->methodOrder[20] = 'addWhitelist';
$lang->personnel->methodOrder[25] = 'unbindWhielist';

/* Issue . */
$lang->resource->issue = new stdclass();
$lang->resource->issue->browse        = 'browse';
$lang->resource->issue->create        = 'create';
$lang->resource->issue->batchCreate   = 'batchCreate';
$lang->resource->issue->delete        = 'delete';
$lang->resource->issue->edit          = 'edit';
$lang->resource->issue->confirm       = 'confirm';
$lang->resource->issue->assignTo      = 'assignTo';
$lang->resource->issue->close         = 'close';
$lang->resource->issue->cancel        = 'cancel';
$lang->resource->issue->activate      = 'activate';
$lang->resource->issue->resolve       = 'resolve';
$lang->resource->issue->view          = 'view';

$lang->issue->methodOrder[5]  = 'browse';
$lang->issue->methodOrder[10] = 'create';
$lang->issue->methodOrder[15] = 'batchCreate';
$lang->issue->methodOrder[20] = 'delete';
$lang->issue->methodOrder[25] = 'edit';
$lang->issue->methodOrder[30] = 'confirm';
$lang->issue->methodOrder[35] = 'assignTo';
$lang->issue->methodOrder[40] = 'close';
$lang->issue->methodOrder[45] = 'cancel';
$lang->issue->methodOrder[50] = 'activate';
$lang->issue->methodOrder[55] = 'resolve';
$lang->issue->methodOrder[60] = 'view';

/* Risk . */
$lang->resource->risk = new stdclass();
$lang->resource->risk->browse      = 'browse';
$lang->resource->risk->create      = 'create';
$lang->resource->risk->edit        = 'edit';
$lang->resource->risk->delete      = 'delete';
$lang->resource->risk->activate    = 'activate';
$lang->resource->risk->close       = 'close';
$lang->resource->risk->hangup      = 'hangup';
$lang->resource->risk->batchCreate = 'batchCreate';
$lang->resource->risk->cancel      = 'cancel';
$lang->resource->risk->track       = 'track';
$lang->resource->risk->view        = 'view';
$lang->resource->risk->assignTo    = 'assignTo';

$lang->risk->methodOrder[5]  = 'browse';
$lang->risk->methodOrder[10] = 'create';
$lang->risk->methodOrder[15] = 'edit';
$lang->risk->methodOrder[20] = 'delete';
$lang->risk->methodOrder[25] = 'activate';
$lang->risk->methodOrder[30] = 'close';
$lang->risk->methodOrder[35] = 'hangup';
$lang->risk->methodOrder[40] = 'batchCreate';
$lang->risk->methodOrder[45] = 'cancel';
$lang->risk->methodOrder[50] = 'track';
$lang->risk->methodOrder[55] = 'view';
$lang->risk->methodOrder[60] = 'assignTo';

/* Product. */
$lang->resource->product = new stdclass();
$lang->resource->product->index          = 'index';
$lang->resource->product->browse         = 'browse';
$lang->resource->product->create         = 'create';
$lang->resource->product->view           = 'view';
$lang->resource->product->edit           = 'edit';
$lang->resource->product->batchEdit      = 'batchEdit';
$lang->resource->product->delete         = 'delete';
$lang->resource->product->roadmap        = 'roadmap';
$lang->resource->product->doc            = 'doc';
$lang->resource->product->dynamic        = 'dynamic';
$lang->resource->product->project        = 'project';
$lang->resource->product->dashboard      = 'dashboard';
$lang->resource->product->close          = 'closeAction';
$lang->resource->product->updateOrder    = 'orderAction';
$lang->resource->product->all            = 'all';
$lang->resource->product->build          = 'build';
$lang->resource->product->export         = 'exportAction';
$lang->resource->product->whitelist      = 'whitelist';
$lang->resource->product->addWhitelist   = 'addWhitelist';
$lang->resource->product->unbindWhielist = 'unbindWhielist';

$lang->product->methodOrder[0]   = 'index';
$lang->product->methodOrder[5]   = 'browse';
$lang->product->methodOrder[10]  = 'create';
$lang->product->methodOrder[15]  = 'view';
$lang->product->methodOrder[20]  = 'edit';
$lang->product->methodOrder[25]  = 'batchEdit';
$lang->product->methodOrder[35]  = 'delete';
$lang->product->methodOrder[40]  = 'roadmap';
//$lang->product->methodOrder[45] = 'doc';
$lang->product->methodOrder[50]  = 'dynamic';
$lang->product->methodOrder[55]  = 'project';
$lang->product->methodOrder[60]  = 'dashboard';
$lang->product->methodOrder[65]  = 'close';
$lang->product->methodOrder[70]  = 'updateOrder';
$lang->product->methodOrder[75]  = 'all';
$lang->product->methodOrder[80]  = 'build';
$lang->product->methodOrder[85]  = 'export';
$lang->product->methodOrder[90]  = 'whitelist';
$lang->product->methodOrder[95]  = 'addWhitelist';
$lang->product->methodOrder[100] = 'unbindWhielist';

/* Branch. */
$lang->resource->branch = new stdclass();
$lang->resource->branch->manage = 'manage';
$lang->resource->branch->sort   = 'sort';
$lang->resource->branch->delete = 'delete';

$lang->branch->methodOrder[0]  = 'manage';
$lang->branch->methodOrder[5]  = 'sort';
$lang->branch->methodOrder[10] = 'delete';

/* Stakeholer. */
$lang->resource->stakeholder = new stdclass();
$lang->resource->stakeholder->browse       = 'browse';
$lang->resource->stakeholder->create       = 'create';
$lang->resource->stakeholder->batchCreate  = 'batchCreate';
$lang->resource->stakeholder->edit         = 'edit';
$lang->resource->stakeholder->delete       = 'delete';
$lang->resource->stakeholder->view         = 'view';
$lang->resource->stakeholder->issue        = 'issue';
$lang->resource->stakeholder->viewIssue    = 'viewIssue';
$lang->resource->stakeholder->communicate  = 'communicate';
$lang->resource->stakeholder->expect       = 'expect';
$lang->resource->stakeholder->expectation  = 'expectation';
$lang->resource->stakeholder->deleteExpect = 'deleteExpect';
$lang->resource->stakeholder->createExpect = 'createExpect';
$lang->resource->stakeholder->editExpect   = 'editExpect';
$lang->resource->stakeholder->viewExpect   = 'viewExpect';
$lang->resource->stakeholder->userIssue    = 'userIssue';

$lang->stakeholder->methodOrder[5]  = 'browse';
$lang->stakeholder->methodOrder[10] = 'create';
$lang->stakeholder->methodOrder[13] = 'batchCreate';
$lang->stakeholder->methodOrder[15] = 'edit';
$lang->stakeholder->methodOrder[25] = 'delete';
$lang->stakeholder->methodOrder[30] = 'view';
$lang->stakeholder->methodOrder[35] = 'issue';
$lang->stakeholder->methodOrder[40] = 'viewIssue';
$lang->stakeholder->methodOrder[45] = 'communicate';
$lang->stakeholder->methodOrder[50] = 'expect';
$lang->stakeholder->methodOrder[55] = 'expectation';
$lang->stakeholder->methodOrder[60] = 'deleteExpect';
$lang->stakeholder->methodOrder[65] = 'createExpect';
$lang->stakeholder->methodOrder[70] = 'editExpect';
$lang->stakeholder->methodOrder[75] = 'viewExpect';
$lang->stakeholder->methodOrder[80] = 'userIssue';

/* Story. */
$lang->resource->story = new stdclass();
$lang->resource->story->create      = 'create';
$lang->resource->story->batchCreate = 'batchCreate';
$lang->resource->story->edit        = 'edit';
$lang->resource->story->linkStory   = 'linkStory';
$lang->resource->story->batchEdit   = 'batchEdit';
$lang->resource->story->export      = 'exportAction';
$lang->resource->story->delete      = 'deleteAction';
$lang->resource->story->view        = 'view';
$lang->resource->story->change      = 'changeAction';
$lang->resource->story->review      = 'reviewAction';
$lang->resource->story->batchReview = 'batchReview';
$lang->resource->story->assignTo    = 'assignAction';
$lang->resource->story->close       = 'closeAction';
$lang->resource->story->batchClose  = 'batchClose';
$lang->resource->story->activate    = 'activateAction';
$lang->resource->story->tasks       = 'tasks';
$lang->resource->story->bugs        = 'bugs';
$lang->resource->story->cases       = 'cases';
$lang->resource->story->zeroCase    = 'zeroCase';
$lang->resource->story->report      = 'reportAction';
$lang->resource->story->batchChangePlan    = 'batchChangePlan';
$lang->resource->story->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->story->batchChangeStage   = 'batchChangeStage';
$lang->resource->story->batchAssignTo      = 'batchAssignTo';
$lang->resource->story->batchChangeModule  = 'batchChangeModule';
$lang->resource->story->batchToTask        = 'batchToTask';
$lang->resource->story->track              = 'track';
$lang->resource->story->processStoryChange = 'processStoryChange';

$lang->story->methodOrder[5]   = 'create';
$lang->story->methodOrder[10]  = 'batchCreate';
$lang->story->methodOrder[15]  = 'edit';
$lang->story->methodOrder[20]  = 'export';
$lang->story->methodOrder[25]  = 'delete';
$lang->story->methodOrder[30]  = 'view';
$lang->story->methodOrder[35]  = 'change';
$lang->story->methodOrder[40]  = 'review';
$lang->story->methodOrder[45]  = 'batchReview';
$lang->story->methodOrder[50]  = 'close';
$lang->story->methodOrder[55]  = 'batchClose';
$lang->story->methodOrder[60]  = 'batchChangePlan';
$lang->story->methodOrder[65]  = 'batchChangeStage';
$lang->story->methodOrder[70]  = 'assignTo';
$lang->story->methodOrder[75]  = 'batchAssignTo';
$lang->story->methodOrder[80]  = 'activate';
$lang->story->methodOrder[85]  = 'tasks';
$lang->story->methodOrder[90]  = 'bugs';
$lang->story->methodOrder[95]  = 'cases';
$lang->story->methodOrder[100] = 'zeroCase';
$lang->story->methodOrder[105] = 'report';
$lang->story->methodOrder[110] = 'linkStory';
$lang->story->methodOrder[115] = 'batchChangeBranch';
$lang->story->methodOrder[120] = 'batchChangeModule';
$lang->story->methodOrder[125] = 'batchToTask';
$lang->story->methodOrder[130] = 'track';
$lang->story->methodOrder[135] = 'processStoryChange';

/* Product plan. */
$lang->resource->productplan = new stdclass();
$lang->resource->productplan->browse           = 'browse';
$lang->resource->productplan->create           = 'create';
$lang->resource->productplan->edit             = 'edit';
$lang->resource->productplan->delete           = 'delete';
$lang->resource->productplan->view             = 'view';
$lang->resource->productplan->linkStory        = 'linkStory';
$lang->resource->productplan->unlinkStory      = 'unlinkStory';
$lang->resource->productplan->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->productplan->linkBug          = 'linkBug';
$lang->resource->productplan->unlinkBug        = 'unlinkBug';
$lang->resource->productplan->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->productplan->batchEdit        = 'batchEdit';

$lang->productplan->methodOrder[5]  = 'browse';
$lang->productplan->methodOrder[10] = 'create';
$lang->productplan->methodOrder[15] = 'edit';
$lang->productplan->methodOrder[20] = 'delete';
$lang->productplan->methodOrder[25] = 'view';
$lang->productplan->methodOrder[30] = 'linkStory';
$lang->productplan->methodOrder[35] = 'unlinkStory';
$lang->productplan->methodOrder[40] = 'batchUnlinkStory';
$lang->productplan->methodOrder[45] = 'linkBug';
$lang->productplan->methodOrder[50] = 'unlinkBug';
$lang->productplan->methodOrder[55] = 'batchUnlinkBug';
$lang->productplan->methodOrder[60] = 'batchEdit';

/* Project Story. */
$lang->resource->projectstory = new stdclass();
$lang->resource->projectstory->story       = 'story';
$lang->resource->projectstory->track       = 'track';
$lang->resource->projectstory->linkStory   = 'linkStory';
$lang->resource->projectstory->unlinkStory = 'unlinkStory';

$lang->productplan->methodOrder[5]  = 'story';
$lang->productplan->methodOrder[10] = 'track';
$lang->productplan->methodOrder[15] = 'linkStory';
$lang->productplan->methodOrder[20] = 'unlinkStory';

/* Release. */
$lang->resource->release = new stdclass();
$lang->resource->release->browse           = 'browse';
$lang->resource->release->create           = 'create';
$lang->resource->release->edit             = 'edit';
$lang->resource->release->delete           = 'delete';
$lang->resource->release->view             = 'view';
$lang->resource->release->export           = 'export';
$lang->resource->release->linkStory        = 'linkStory';
$lang->resource->release->unlinkStory      = 'unlinkStory';
$lang->resource->release->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->release->linkBug          = 'linkBug';
$lang->resource->release->unlinkBug        = 'unlinkBug';
$lang->resource->release->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->release->changeStatus     = 'changeStatus';

$lang->release->methodOrder[5]  = 'browse';
$lang->release->methodOrder[10] = 'create';
$lang->release->methodOrder[15] = 'edit';
$lang->release->methodOrder[20] = 'delete';
$lang->release->methodOrder[25] = 'view';
$lang->release->methodOrder[35] = 'export';
$lang->release->methodOrder[40] = 'linkStory';
$lang->release->methodOrder[45] = 'unlinkStory';
$lang->release->methodOrder[50] = 'batchUnlinkStory';
$lang->release->methodOrder[55] = 'linkBug';
$lang->release->methodOrder[60] = 'unlinkBug';
$lang->release->methodOrder[65] = 'batchUnlinkBug';
$lang->release->methodOrder[70] = 'changeStatus';

/* Release. */
$lang->resource->projectbuild = new stdclass();
$lang->resource->projectbuild->browse = 'browse';

/* Release. */
$lang->resource->projectrelease = new stdclass();
$lang->resource->projectrelease->browse           = 'browse';
$lang->resource->projectrelease->create           = 'create';
$lang->resource->projectrelease->edit             = 'edit';
$lang->resource->projectrelease->delete           = 'delete';
$lang->resource->projectrelease->view             = 'view';
$lang->resource->projectrelease->export           = 'export';
$lang->resource->projectrelease->linkStory        = 'linkStory';
$lang->resource->projectrelease->unlinkStory      = 'unlinkStory';
$lang->resource->projectrelease->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->projectrelease->linkBug          = 'linkBug';
$lang->resource->projectrelease->unlinkBug        = 'unlinkBug';
$lang->resource->projectrelease->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->projectrelease->changeStatus     = 'changeStatus';

$lang->projectrelease->methodOrder[5]  = 'browse';
$lang->projectrelease->methodOrder[10] = 'create';
$lang->projectrelease->methodOrder[15] = 'edit';
$lang->projectrelease->methodOrder[20] = 'delete';
$lang->projectrelease->methodOrder[25] = 'view';
$lang->projectrelease->methodOrder[35] = 'export';
$lang->projectrelease->methodOrder[40] = 'linkStory';
$lang->projectrelease->methodOrder[45] = 'unlinkStory';
$lang->projectrelease->methodOrder[50] = 'batchUnlinkStory';
$lang->projectrelease->methodOrder[55] = 'linkBug';
$lang->projectrelease->methodOrder[60] = 'unlinkBug';
$lang->projectrelease->methodOrder[65] = 'batchUnlinkBug';
$lang->projectrelease->methodOrder[70] = 'changeStatus';

/* Project. */
$lang->resource->project = new stdclass();
$lang->resource->project->index             = 'index';
$lang->resource->project->view              = 'view';
$lang->resource->project->browse            = 'browse';
$lang->resource->project->create            = 'create';
$lang->resource->project->edit              = 'edit';
$lang->resource->project->batchedit         = 'batchEdit';
$lang->resource->project->start             = 'startAction';
$lang->resource->project->activate          = 'activateAction';
$lang->resource->project->putoff            = 'delayAction';
$lang->resource->project->suspend           = 'suspendAction';
$lang->resource->project->close             = 'closeAction';
$lang->resource->project->delete            = 'delete';
$lang->resource->project->task              = 'task';
$lang->resource->project->grouptask         = 'groupTask';
$lang->resource->project->importtask        = 'importTask';
$lang->resource->project->importplanstories = 'importPlanStories';
$lang->resource->project->importBug         = 'importBug';
$lang->resource->project->story             = 'story';
$lang->resource->project->build             = 'build';
$lang->resource->project->testtask          = 'testtaskAction';
$lang->resource->project->bug               = 'bug';
$lang->resource->project->burn              = 'burn';
$lang->resource->project->computeBurn       = 'computeBurnAction';
$lang->resource->project->fixFirst          = 'fixFirst';
$lang->resource->project->burnData          = 'burnData';
$lang->resource->project->team              = 'teamAction';
$lang->resource->project->doc               = 'doc';
$lang->resource->project->dynamic           = 'dynamic';
$lang->resource->project->manageProducts    = 'manageProducts';
//$lang->resource->project->manageChilds   = 'manageChilds';
$lang->resource->project->manageMembers     = 'manageMembers';
$lang->resource->project->unlinkMember      = 'unlinkMember';
$lang->resource->project->linkStory         = 'linkStory';
$lang->resource->project->unlinkStory       = 'unlinkStory';
$lang->resource->project->batchUnlinkStory  = 'batchUnlinkStory';
$lang->resource->project->updateOrder       = 'updateOrder';
$lang->resource->project->kanban            = 'kanbanAction';
$lang->resource->project->printKanban       = 'printKanbanAction';
$lang->resource->project->tree              = 'treeAction';
$lang->resource->project->treeTask          = 'treeOnlyTask';
$lang->resource->project->treeStory         = 'treeOnlyStory';
$lang->resource->project->all               = 'allProjects';
$lang->resource->project->kanbanHideCols    = 'kanbanHideCols';
$lang->resource->project->kanbanColsColor   = 'kanbanColsColor';
$lang->resource->project->export            = 'exportAction';
$lang->resource->project->storyKanban       = 'storyKanban';
$lang->resource->project->storySort         = 'storySort';
$lang->resource->project->whitelist         = 'whitelist';
$lang->resource->project->addWhitelist      = 'addWhitelist';
$lang->resource->project->unbindWhielist    = 'unbindWhielist';

$lang->project->methodOrder[0]   = 'index';
$lang->project->methodOrder[5]   = 'view';
$lang->project->methodOrder[10]  = 'browse';
$lang->project->methodOrder[15]  = 'create';
$lang->project->methodOrder[20]  = 'edit';
$lang->project->methodOrder[25]  = 'batchedit';
$lang->project->methodOrder[30]  = 'start';
$lang->project->methodOrder[35]  = 'activate';
$lang->project->methodOrder[40]  = 'putoff';
$lang->project->methodOrder[45]  = 'suspend';
$lang->project->methodOrder[50]  = 'close';
$lang->project->methodOrder[60]  = 'delete';
$lang->project->methodOrder[65]  = 'task';
$lang->project->methodOrder[70]  = 'grouptask';
$lang->project->methodOrder[75]  = 'importtask';
$lang->project->methodOrder[80]  = 'importplanstories';
$lang->project->methodOrder[85]  = 'importBug';
$lang->project->methodOrder[90]  = 'story';
$lang->project->methodOrder[95]  = 'build';
$lang->project->methodOrder[100] = 'testtask';
$lang->project->methodOrder[105] = 'bug';
$lang->project->methodOrder[110] = 'burn';
$lang->project->methodOrder[115] = 'computeBurn';
$lang->project->methodOrder[120] = 'fixFirst';
$lang->project->methodOrder[125] = 'burnData';
$lang->project->methodOrder[130] = 'team';
//$lang->project->methodOrder[130] = 'doc';
$lang->project->methodOrder[135] = 'dynamic';
$lang->project->methodOrder[140] = 'manageProducts';
$lang->project->methodOrder[145] = 'manageMembers';
$lang->project->methodOrder[150] = 'unlinkMember';
$lang->project->methodOrder[155] = 'linkStory';
$lang->project->methodOrder[160] = 'unlinkStory';
$lang->project->methodOrder[165] = 'batchUnlinkStory';
$lang->project->methodOrder[170] = 'updateOrder';
$lang->project->methodOrder[175] = 'kanban';
$lang->project->methodOrder[180] = 'printKanban';
$lang->project->methodOrder[185] = 'kanbanHideCols';
$lang->project->methodOrder[190] = 'kanbanColsColor';
$lang->project->methodOrder[195] = 'tree';
$lang->project->methodOrder[200] = 'treeTask';
$lang->project->methodOrder[205] = 'treeStory';
$lang->project->methodOrder[210] = 'all';
$lang->project->methodOrder[215] = 'export';
$lang->project->methodOrder[220] = 'storyKanban';
$lang->project->methodOrder[225] = 'storySort';
$lang->project->methodOrder[230] = 'whitelist';
$lang->project->methodOrder[235] = 'addWhitelist';
$lang->project->methodOrder[240] = 'unbindWhielist';

/* Design. */
$lang->resource->design = new stdclass();
$lang->resource->design->browse       = 'browse';
$lang->resource->design->view         = 'view';
$lang->resource->design->create       = 'create';
$lang->resource->design->batchCreate  = 'batchCreate';
$lang->resource->design->edit         = 'edit';
$lang->resource->design->assignTo     = 'assignTo';
$lang->resource->design->delete       = 'delete';
$lang->resource->design->linkCommit   = 'linkCommit';
$lang->resource->design->viewCommit   = 'viewCommit';
$lang->resource->design->unlinkCommit = 'unlinkCommit';
$lang->resource->design->revision     = 'revision';

$lang->design->methodOrder[5]     = 'browse';
$lang->design->methodOrder[10]    = 'view';
$lang->design->methodOrder[15]    = 'create';
$lang->design->methodOrder[20]    = 'batchCreate';
$lang->design->methodOrder[25]    = 'edit';
$lang->design->methodOrder[30]    = 'assignTo';
$lang->design->methodOrder[35]    = 'delete';
$lang->design->methodOrder[40]    = 'linkCommit';
$lang->design->methodOrder[45]    = 'viewCommit';
$lang->design->methodOrder[50]    = 'unlinkCommit';
$lang->design->methodOrder[55]    = 'revision';

/* Program plan. */
$lang->resource->programplan = new stdclass();
$lang->resource->programplan->browse = 'browse';
$lang->resource->programplan->create = 'create';
$lang->resource->programplan->edit   = 'edit';

$lang->programplan->methodOrder[5]  = 'browse';
$lang->programplan->methodOrder[10] = 'create';
$lang->programplan->methodOrder[15] = 'edit';

/* Task. */
$lang->resource->task = new stdclass();
$lang->resource->task->create             = 'create';
$lang->resource->task->edit               = 'edit';
$lang->resource->task->assignTo           = 'assignAction';
$lang->resource->task->start              = 'startAction';
$lang->resource->task->pause              = 'pauseAction';
$lang->resource->task->restart            = 'restartAction';
$lang->resource->task->finish             = 'finishAction';
$lang->resource->task->cancel             = 'cancelAction';
$lang->resource->task->close              = 'closeAction';
$lang->resource->task->batchCreate        = 'batchCreate';
$lang->resource->task->batchEdit          = 'batchEdit';
$lang->resource->task->batchClose         = 'batchClose';
$lang->resource->task->batchCancel        = 'batchCancel';
$lang->resource->task->batchAssignTo      = 'batchAssignTo';
$lang->resource->task->batchChangeModule  = 'batchChangeModule';
$lang->resource->task->activate           = 'activateAction';
$lang->resource->task->delete             = 'deleteAction';
$lang->resource->task->view               = 'view';
$lang->resource->task->export             = 'exportAction';
$lang->resource->task->confirmStoryChange = 'confirmStoryChange';
$lang->resource->task->recordEstimate     = 'recordEstimate';
$lang->resource->task->editEstimate       = 'editEstimate';
$lang->resource->task->deleteEstimate     = 'deleteEstimate';
$lang->resource->task->report             = 'reportChart';

$lang->task->methodOrder[5]   = 'create';
$lang->task->methodOrder[10]  = 'batchCreate';
$lang->task->methodOrder[15]  = 'batchEdit';
$lang->task->methodOrder[20]  = 'edit';
$lang->task->methodOrder[25]  = 'assignTo';
$lang->task->methodOrder[30]  = 'batchAssignTo';
$lang->task->methodOrder[35]  = 'start';
$lang->task->methodOrder[40]  = 'pause';
$lang->task->methodOrder[45]  = 'restart';
$lang->task->methodOrder[50]  = 'finish';
$lang->task->methodOrder[55]  = 'cancel';
$lang->task->methodOrder[60]  = 'close';
$lang->task->methodOrder[65]  = 'batchClose';
$lang->task->methodOrder[70]  = 'activate';
$lang->task->methodOrder[75]  = 'delete';
$lang->task->methodOrder[80]  = 'view';
$lang->task->methodOrder[85]  = 'export';
$lang->task->methodOrder[90]  = 'confirmStoryChange';
$lang->task->methodOrder[95]  = 'recordEstimate';
$lang->task->methodOrder[100] = 'editEstimate';
$lang->task->methodOrder[105] = 'deleteEstimate';
$lang->task->methodOrder[110] = 'report';
$lang->task->methodOrder[115] = 'batchChangeModule';

/* Build. */
$lang->resource->build = new stdclass();
$lang->resource->build->create           = 'create';
$lang->resource->build->edit             = 'edit';
$lang->resource->build->delete           = 'delete';
$lang->resource->build->view             = 'view';
$lang->resource->build->linkStory        = 'linkStory';
$lang->resource->build->unlinkStory      = 'unlinkStory';
$lang->resource->build->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->build->linkBug          = 'linkBug';
$lang->resource->build->unlinkBug        = 'unlinkBug';
$lang->resource->build->batchUnlinkBug   = 'batchUnlinkBug';

$lang->build->methodOrder[5]  = 'create';
$lang->build->methodOrder[10] = 'edit';
$lang->build->methodOrder[15] = 'delete';
$lang->build->methodOrder[20] = 'view';
$lang->build->methodOrder[25] = 'linkStory';
$lang->build->methodOrder[30] = 'unlinkStory';
$lang->build->methodOrder[35] = 'batchUnlinkStory';
$lang->build->methodOrder[40] = 'linkBug';
$lang->build->methodOrder[45] = 'unlinkBug';
$lang->build->methodOrder[50] = 'batchUnlinkBug';

/* QA. */
$lang->resource->qa = new stdclass();
$lang->resource->qa->index = 'index';

$lang->qa->methodOrder[0] = 'index';

/* Bug. */
$lang->resource->bug = new stdclass();
$lang->resource->bug->index              = 'index';
$lang->resource->bug->browse             = 'browse';
$lang->resource->bug->create             = 'create';
$lang->resource->bug->batchCreate        = 'batchCreate';
$lang->resource->bug->confirmBug         = 'confirmAction';
$lang->resource->bug->batchConfirm       = 'batchConfirm';
$lang->resource->bug->view               = 'view';
$lang->resource->bug->edit               = 'edit';
$lang->resource->bug->linkBugs           = 'linkBugs';
$lang->resource->bug->batchEdit          = 'batchEdit';
$lang->resource->bug->batchClose         = 'batchClose';
$lang->resource->bug->assignTo           = 'assignAction';
$lang->resource->bug->batchAssignTo      = 'batchAssignTo';
$lang->resource->bug->resolve            = 'resolveAction';
$lang->resource->bug->batchResolve       = 'batchResolve';
$lang->resource->bug->activate           = 'activateAction';
$lang->resource->bug->batchActivate      = 'batchActivate';
$lang->resource->bug->close              = 'closeAction';
$lang->resource->bug->report             = 'reportAction';
$lang->resource->bug->export             = 'exportAction';
$lang->resource->bug->confirmStoryChange = 'confirmStoryChange';
$lang->resource->bug->delete             = 'deleteAction';
$lang->resource->bug->batchChangeModule  = 'batchChangeModule';
$lang->resource->bug->batchChangeBranch  = 'batchChangeBranch';

$lang->bug->methodOrder[0]   = 'index';
$lang->bug->methodOrder[5]   = 'browse';
$lang->bug->methodOrder[10]  = 'create';
$lang->bug->methodOrder[15]  = 'batchCreate';
$lang->bug->methodOrder[20]  = 'batchEdit';
$lang->bug->methodOrder[25]  = 'confirmBug';
$lang->bug->methodOrder[30]  = 'batchConfirm';
$lang->bug->methodOrder[35]  = 'view';
$lang->bug->methodOrder[40]  = 'edit';
$lang->bug->methodOrder[45]  = 'assignTo';
$lang->bug->methodOrder[50]  = 'batchAssignTo';
$lang->bug->methodOrder[55]  = 'resolve';
$lang->bug->methodOrder[60]  = 'batchResolve';
$lang->bug->methodOrder[65]  = 'batchClose';
$lang->bug->methodOrder[67]  = 'batchActivate';
$lang->bug->methodOrder[70]  = 'activate';
$lang->bug->methodOrder[75]  = 'close';
$lang->bug->methodOrder[80]  = 'report';
$lang->bug->methodOrder[85]  = 'export';
$lang->bug->methodOrder[90]  = 'confirmStoryChange';
$lang->bug->methodOrder[95]  = 'delete';
$lang->bug->methodOrder[100] = 'linkBugs';
$lang->bug->methodOrder[105] = 'batchChangeModule';
$lang->bug->methodOrder[110] = 'batchChangeBranch';

/* Test case. */
$lang->resource->testcase = new stdclass();
$lang->resource->testcase->index              = 'index';
$lang->resource->testcase->browse             = 'browse';
$lang->resource->testcase->groupCase          = 'groupCase';
$lang->resource->testcase->create             = 'create';
$lang->resource->testcase->batchCreate        = 'batchCreate';
$lang->resource->testcase->createBug          = 'createBug';
$lang->resource->testcase->view               = 'view';
$lang->resource->testcase->edit               = 'edit';
$lang->resource->testcase->linkCases          = 'linkCases';
$lang->resource->testcase->batchEdit          = 'batchEdit';
$lang->resource->testcase->delete             = 'deleteAction';
$lang->resource->testcase->batchDelete        = 'batchDelete';
$lang->resource->testcase->export             = 'exportAction';
$lang->resource->testcase->exportTemplet      = 'exportTemplet';
$lang->resource->testcase->import             = 'importAction';
$lang->resource->testcase->showImport         = 'showImport';
$lang->resource->testcase->confirmChange      = 'confirmChange';
$lang->resource->testcase->confirmStoryChange = 'confirmStoryChange';
$lang->resource->testcase->batchChangeModule  = 'batchChangeModule';
$lang->resource->testcase->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->testcase->bugs               = 'bugs';
$lang->resource->testcase->review             = 'review';
$lang->resource->testcase->batchReview        = 'batchReview';
$lang->resource->testcase->importFromLib      = 'importFromLib';
$lang->resource->testcase->batchCaseTypeChange = 'batchCaseTypeChange';
$lang->resource->testcase->confirmLibcaseChange    = 'confirmLibcaseChange';
$lang->resource->testcase->ignoreLibcaseChange     = 'ignoreLibcaseChange';
$lang->resource->testcase->batchConfirmStoryChange = 'batchConfirmStoryChange';

$lang->testcase->methodOrder[0]   = 'index';
$lang->testcase->methodOrder[5]   = 'browse';
$lang->testcase->methodOrder[10]  = 'groupCase';
$lang->testcase->methodOrder[15]  = 'create';
$lang->testcase->methodOrder[20]  = 'batchCreate';
$lang->testcase->methodOrder[25]  = 'createBug';
$lang->testcase->methodOrder[30]  = 'view';
$lang->testcase->methodOrder[35]  = 'edit';
$lang->testcase->methodOrder[40]  = 'delete';
$lang->testcase->methodOrder[45]  = 'export';
$lang->testcase->methodOrder[50]  = 'confirmChange';
$lang->testcase->methodOrder[55]  = 'confirmStoryChange';
$lang->testcase->methodOrder[60]  = 'batchEdit';
$lang->testcase->methodOrder[65]  = 'batchDelete';
$lang->testcase->methodOrder[70]  = 'batchChangeModule';
$lang->testcase->methodOrder[75]  = 'batchChangeBranch';
$lang->testcase->methodOrder[80]  = 'linkCases';
$lang->testcase->methodOrder[90]  = 'bugs';
$lang->testcase->methodOrder[95]  = 'review';
$lang->testcase->methodOrder[100] = 'batchReview';
$lang->testcase->methodOrder[105] = 'batchConfirmStoryChange';
$lang->testcase->methodOrder[110] = 'importFromLib';
$lang->testcase->methodOrder[115] = 'batchCaseTypeChange';

/* Test task. */
$lang->resource->testtask = new stdclass();
$lang->resource->testtask->index            = 'index';
$lang->resource->testtask->create           = 'create';
$lang->resource->testtask->browse           = 'browse';
$lang->resource->testtask->view             = 'view';
$lang->resource->testtask->cases            = 'cases';
$lang->resource->testtask->groupCase        = 'groupCase';
$lang->resource->testtask->edit             = 'edit';
$lang->resource->testtask->start            = 'startAction';
$lang->resource->testtask->close            = 'closeAction';
$lang->resource->testtask->delete           = 'delete';
$lang->resource->testtask->batchAssign      = 'batchAssign';
$lang->resource->testtask->linkcase         = 'linkCase';
$lang->resource->testtask->unlinkcase       = 'lblUnlinkCase';
$lang->resource->testtask->batchUnlinkCases = 'batchUnlinkCases';
$lang->resource->testtask->runcase          = 'lblRunCase';
$lang->resource->testtask->results          = 'resultsAction';
$lang->resource->testtask->batchRun         = 'batchRun';
$lang->resource->testtask->activate         = 'activateAction';
$lang->resource->testtask->block            = 'blockAction';
$lang->resource->testtask->report           = 'reportAction';
$lang->resource->testtask->browseUnits      = 'browseUnits';
$lang->resource->testtask->unitCases        = 'unitCases';
$lang->resource->testtask->importUnitResult = 'importUnitResult';

$lang->testtask->methodOrder[0]   = 'index';
$lang->testtask->methodOrder[5]   = 'create';
$lang->testtask->methodOrder[10]  = 'browse';
$lang->testtask->methodOrder[15]  = 'view';
$lang->testtask->methodOrder[20]  = 'cases';
$lang->testtask->methodOrder[25]  = 'groupCase';
$lang->testtask->methodOrder[30]  = 'edit';
$lang->testtask->methodOrder[35]  = 'start';
$lang->testtask->methodOrder[40]  = 'activate';
$lang->testtask->methodOrder[45]  = 'block';
$lang->testtask->methodOrder[50]  = 'close';
$lang->testtask->methodOrder[55]  = 'delete';
$lang->testtask->methodOrder[60]  = 'batchAssign';
$lang->testtask->methodOrder[65]  = 'linkcase';
$lang->testtask->methodOrder[70]  = 'unlinkcase';
$lang->testtask->methodOrder[75]  = 'runcase';
$lang->testtask->methodOrder[80]  = 'results';
$lang->testtask->methodOrder[85]  = 'batchUnlinkCases';
$lang->testtask->methodOrder[90]  = 'report';
$lang->testtask->methodOrder[95]  = 'browseUnits';
$lang->testtask->methodOrder[100] = 'unitCases';
$lang->testtask->methodOrder[105] = 'importUnitResult';

$lang->resource->testreport = new stdclass();
$lang->resource->testreport->browse     = 'browse';
$lang->resource->testreport->create     = 'create';
$lang->resource->testreport->view       = 'view';
$lang->resource->testreport->delete     = 'delete';
$lang->resource->testreport->edit       = 'edit';

$lang->testreport->methodOrder[0]  = 'browse';
$lang->testreport->methodOrder[5]  = 'create';
$lang->testreport->methodOrder[10] = 'view';
$lang->testreport->methodOrder[15] = 'delete';
$lang->testreport->methodOrder[20] = 'edit';

$lang->resource->testsuite = new stdclass();
$lang->resource->testsuite->index            = 'index';
$lang->resource->testsuite->browse           = 'browse';
$lang->resource->testsuite->create           = 'create';
$lang->resource->testsuite->view             = 'view';
$lang->resource->testsuite->edit             = 'edit';
$lang->resource->testsuite->delete           = 'delete';
$lang->resource->testsuite->linkCase         = 'linkCase';
$lang->resource->testsuite->unlinkCase       = 'unlinkCaseAction';
$lang->resource->testsuite->batchUnlinkCases = 'batchUnlinkCases';

$lang->testsuite->methodOrder[0]  = 'index';
$lang->testsuite->methodOrder[5]  = 'browse';
$lang->testsuite->methodOrder[10] = 'create';
$lang->testsuite->methodOrder[15] = 'view';
$lang->testsuite->methodOrder[20] = 'edit';
$lang->testsuite->methodOrder[25] = 'delete';
$lang->testsuite->methodOrder[30] = 'linkCase';
$lang->testsuite->methodOrder[35] = 'unlinkCase';
$lang->testsuite->methodOrder[40] = 'batchUnlinkCases';

$lang->resource->caselib = new stdclass();
$lang->resource->caselib->index            = 'index';
$lang->resource->caselib->browse           = 'browse';
$lang->resource->caselib->create           = 'create';
$lang->resource->caselib->edit             = 'edit';
$lang->resource->caselib->delete           = 'delete';
$lang->resource->caselib->view             = 'view';
$lang->resource->caselib->createCase       = 'createCase';
$lang->resource->caselib->batchCreateCase  = 'batchCreateCase';
$lang->resource->caselib->exportTemplet    = 'exportTemplet';
$lang->resource->caselib->import           = 'importAction';
$lang->resource->caselib->showImport       = 'showImport';

$lang->caselib->methodOrder[0]  = 'index';
$lang->caselib->methodOrder[5]  = 'browse';
$lang->caselib->methodOrder[10] = 'create';
$lang->caselib->methodOrder[15] = 'edit';
$lang->caselib->methodOrder[20] = 'delete';
$lang->caselib->methodOrder[25] = 'view';
$lang->caselib->methodOrder[30] = 'createCase';
$lang->caselib->methodOrder[35] = 'batchCreateCase';
$lang->caselib->methodOrder[40] = 'exportTemplet';
$lang->caselib->methodOrder[45] = 'import';
$lang->caselib->methodOrder[50] = 'showImport';

$lang->resource->repo                 = new stdclass();
$lang->resource->repo->browse         = 'browse';
$lang->resource->repo->view           = 'view';
$lang->resource->repo->log            = 'log';
$lang->resource->repo->revision       = 'revisionAction';
$lang->resource->repo->blame          = 'blameAction';
$lang->resource->repo->create         = 'createAction';
$lang->resource->repo->edit           = 'editAction';
$lang->resource->repo->delete         = 'delete';
$lang->resource->repo->showSyncCommit = 'showSyncCommit';
$lang->resource->repo->diff           = 'diffAction';
$lang->resource->repo->download       = 'download';
$lang->resource->repo->maintain       = 'maintain';
$lang->resource->repo->setRules       = 'setRules';

$lang->repo->methodOrder[5]  = 'create';
$lang->repo->methodOrder[10] = 'edit';
$lang->repo->methodOrder[15] = 'delete';
$lang->repo->methodOrder[20] = 'showSyncCommit';
$lang->repo->methodOrder[25] = 'maintain';
$lang->repo->methodOrder[30] = 'browse';
$lang->repo->methodOrder[35] = 'view';
$lang->repo->methodOrder[40] = 'diff';
$lang->repo->methodOrder[45] = 'log';
$lang->repo->methodOrder[50] = 'revision';
$lang->repo->methodOrder[55] = 'blame';
$lang->repo->methodOrder[60] = 'download';
$lang->repo->methodOrder[65] = 'setRules';

$lang->resource->ci = new stdclass();
$lang->resource->ci->commitResult = 'commitResult';

$lang->ci->methodOrder[5] = 'commitResult';

$lang->resource->compile = new stdclass();
$lang->resource->compile->browse = 'browse';
$lang->resource->compile->logs   = 'logs';

$lang->compile->methodOrder[5]  = 'browse';
$lang->compile->methodOrder[10] = 'logs';

$lang->resource->jenkins = new stdclass();
$lang->resource->jenkins->browse = 'browse';
$lang->resource->jenkins->create = 'create';
$lang->resource->jenkins->edit   = 'edit';
$lang->resource->jenkins->delete = 'delete';

$lang->jenkins->methodOrder[5]  = 'browse';
$lang->jenkins->methodOrder[10] = 'create';
$lang->jenkins->methodOrder[15] = 'edit';
$lang->jenkins->methodOrder[20] = 'delete';

$lang->resource->job = new stdclass(); 
$lang->resource->job->browse = 'browse'; 
$lang->resource->job->create = 'create';
$lang->resource->job->edit   = 'edit';
$lang->resource->job->delete = 'delete';
$lang->resource->job->exec   = 'exec';
$lang->resource->job->view   = 'view';

$lang->job->methodOrder[5]  = 'browse';
$lang->job->methodOrder[10] = 'create';
$lang->job->methodOrder[15] = 'edit';
$lang->job->methodOrder[20] = 'delete';
$lang->job->methodOrder[25] = 'exec';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->index      = 'index';
$lang->resource->doc->browse     = 'browse';
$lang->resource->doc->createLib  = 'createLib';
$lang->resource->doc->editLib    = 'editLib';
$lang->resource->doc->deleteLib  = 'deleteLib';
$lang->resource->doc->create     = 'create';
$lang->resource->doc->view       = 'view';
$lang->resource->doc->edit       = 'edit';
$lang->resource->doc->delete     = 'delete';
$lang->resource->doc->deleteFile = 'deleteFile';
$lang->resource->doc->allLibs    = 'allLibs';
$lang->resource->doc->objectLibs = 'objectLibs';
$lang->resource->doc->showFiles  = 'showFiles';
$lang->resource->doc->sort       = 'sort';
$lang->resource->doc->collect    = 'collect';
//$lang->resource->doc->diff       = 'diff';

$lang->doc->methodOrder[0]  = 'index';
$lang->doc->methodOrder[5]  = 'browse';
$lang->doc->methodOrder[10] = 'createLib';
$lang->doc->methodOrder[15] = 'editLib';
$lang->doc->methodOrder[20] = 'deleteLib';
$lang->doc->methodOrder[25] = 'create';
$lang->doc->methodOrder[30] = 'view';
$lang->doc->methodOrder[35] = 'edit';
$lang->doc->methodOrder[40] = 'delete';
$lang->doc->methodOrder[45] = 'deleteFile';
$lang->doc->methodOrder[50] = 'allLibs';
$lang->doc->methodOrder[55] = 'showFiles';
$lang->doc->methodOrder[60] = 'objectLibs';
$lang->doc->methodOrder[65] = 'sort';
$lang->doc->methodOrder[70] = 'collect';
//$lang->doc->methodOrder[55] = 'diff';

/* Mail. */
$lang->resource->mail = new stdclass();
$lang->resource->mail->index  = 'index';
$lang->resource->mail->detect = 'detectAction';
$lang->resource->mail->edit   = 'edit';
$lang->resource->mail->save   = 'saveAction';
$lang->resource->mail->test   = 'test';
$lang->resource->mail->reset  = 'resetAction';
$lang->resource->mail->browse = 'browse';
$lang->resource->mail->delete = 'delete';
$lang->resource->mail->resend = 'resendAction';
$lang->resource->mail->batchDelete   = 'batchDelete';
$lang->resource->mail->sendCloud     = 'sendCloud';
$lang->resource->mail->sendcloudUser = 'sendcloudUser';
$lang->resource->mail->ztCloud       = 'ztCloud';

$lang->mail->methodOrder[5]  = 'index';
$lang->mail->methodOrder[10] = 'detect';
$lang->mail->methodOrder[15] = 'edit';
$lang->mail->methodOrder[20] = 'save';
$lang->mail->methodOrder[25] = 'test';
$lang->mail->methodOrder[30] = 'reset';
$lang->mail->methodOrder[35] = 'browse';
$lang->mail->methodOrder[40] = 'delete';
$lang->mail->methodOrder[45] = 'batchDelete';
$lang->mail->methodOrder[50] = 'resend';
$lang->mail->methodOrder[55] = 'sendCloud';
$lang->mail->methodOrder[60] = 'sendcloudUser';
$lang->mail->methodOrder[65] = 'ztCloud';

/* Subject. */
$lang->resource->subject = new stdclass();
$lang->resource->subject->browse = 'browse';

$lang->subject->methodOrder[5]  = 'browse';

/* Holiday. */
$lang->resource->holiday = new stdclass();
$lang->resource->holiday->browse = 'browse';
$lang->resource->holiday->create = 'create';
$lang->resource->holiday->edit   = 'edit';
$lang->resource->holiday->delete = 'delete';

$lang->holiday->methodOrder[5]  = 'browse';
$lang->holiday->methodOrder[10] = 'create';
$lang->holiday->methodOrder[15] = 'edit';
$lang->holiday->methodOrder[20] = 'delete';

/* Custom. */
$lang->resource->custom = new stdclass();
$lang->resource->custom->index              = 'index';
$lang->resource->custom->set                = 'set';
$lang->resource->custom->project            = 'project';
$lang->resource->custom->product            = 'product';
$lang->resource->custom->restore            = 'restore';
$lang->resource->custom->flow               = 'flow';
$lang->resource->custom->working            = 'working';
$lang->resource->custom->setPublic          = 'setPublic';
$lang->resource->custom->timezone           = 'timezone';
$lang->resource->custom->estimate           = 'estimate';
$lang->resource->custom->setStoryConcept    = 'setStoryConcept';
$lang->resource->custom->editStoryConcept   = 'editStoryConcept';
$lang->resource->custom->browseStoryConcept = 'browseStoryConcept';
$lang->resource->custom->deleteStoryConcept = 'deleteStoryConcept';
$lang->resource->custom->configureScrum     = 'configureScrum';

$lang->custom->methodOrder[5]  = 'index';
$lang->custom->methodOrder[10] = 'set';
$lang->custom->methodOrder[15] = 'project';
$lang->custom->methodOrder[20] = 'product';
$lang->custom->methodOrder[25] = 'restore';
$lang->custom->methodOrder[30] = 'flow';
$lang->custom->methodOrder[35] = 'working';
$lang->custom->methodOrder[40] = 'setPublic';
$lang->custom->methodOrder[45] = 'timezone';
$lang->custom->methodOrder[50] = 'estimate';
$lang->custom->methodOrder[55] = 'setStoryContcept';
$lang->custom->methodOrder[60] = 'editStoryContcept';
$lang->custom->methodOrder[65] = 'browseStoryContcept';
$lang->custom->methodOrder[70] = 'deleteStoryContcept';
$lang->custom->methodOrder[75] = 'configureScrum';

$lang->resource->datatable = new stdclass();
$lang->resource->datatable->setGlobal = 'setGlobal';

$lang->datatable->methodOrder[5]  = 'setGlobal';

/* Subversion. */
$lang->resource->svn = new stdclass();
$lang->resource->svn->diff    = 'diff';
$lang->resource->svn->cat     = 'cat';
$lang->resource->svn->apiSync = 'apiSync';

$lang->svn->methodOrder[5]  = 'diff';
$lang->svn->methodOrder[10] = 'cat';
$lang->svn->methodOrder[15] = 'apiSync';

/* Git. */
$lang->resource->git = new stdclass();
$lang->resource->git->diff    = 'diff';
$lang->resource->git->cat     = 'cat';
$lang->resource->git->apiSync = 'apiSync';

$lang->git->methodOrder[5]  = 'diff';
$lang->git->methodOrder[10] = 'cat';
$lang->git->methodOrder[15] = 'apiSync';

/* Stage. */
$lang->resource->stage = new stdclass();
$lang->resource->stage->browse      = 'browse';
$lang->resource->stage->create      = 'create';
$lang->resource->stage->batchCreate = 'batchCreate';
$lang->resource->stage->edit        = 'edit';
$lang->resource->stage->setType     = 'setType';
$lang->resource->stage->delete      = 'delete';

$lang->stage->methodOrder[5]  = 'browse';
$lang->stage->methodOrder[10] = 'create';
$lang->stage->methodOrder[15] = 'batchCreate';
$lang->stage->methodOrder[20] = 'edit';
$lang->stage->methodOrder[25] = 'setType';
$lang->stage->methodOrder[30] = 'delete';

/* Company. */
$lang->resource->company = new stdclass();
$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->view   = 'view';
$lang->resource->company->dynamic= 'dynamic';

$lang->company->methodOrder[0]  = 'index';
$lang->company->methodOrder[5]  = 'browse';
$lang->company->methodOrder[15] = 'edit';
$lang->company->methodOrder[25] = 'dynamic';

/* Work estimation. */
$lang->resource->workestimation = new stdclass();
$lang->resource->workestimation->index  = 'index';

$lang->workestimation->methodOrder[0] = 'index';

/* Duration estimation. */
$lang->resource->durationestimation = new stdclass();
$lang->resource->durationestimation->index  = 'index';
$lang->resource->durationestimation->create = 'create';

$lang->durationestimation->methodOrder[0] = 'index';
$lang->durationestimation->methodOrder[5] = 'create';

/* Budget. */
$lang->resource->budget = new stdclass();
$lang->resource->budget->browse       = 'browse';
$lang->resource->budget->summary      = 'summary';
$lang->resource->budget->create       = 'create';
$lang->resource->budget->batchCreate  = 'batchCreate';
$lang->resource->budget->edit         = 'edit';
$lang->resource->budget->view         = 'view';
$lang->resource->budget->delete       = 'delete';

$lang->budget->methodOrder[5]  = 'browse';
$lang->budget->methodOrder[10] = 'summary';
$lang->budget->methodOrder[15] = 'create';
$lang->budget->methodOrder[20] = 'batchCreate';
$lang->budget->methodOrder[25] = 'edit';
$lang->budget->methodOrder[30] = 'view';
$lang->budget->methodOrder[35] = 'delete';

/* Department. */
$lang->resource->dept = new stdclass();
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->edit        = 'edit';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'edit';
$lang->dept->methodOrder[25] = 'delete';

/* Group. */
$lang->resource->group = new stdclass();
$lang->resource->group->browse         = 'browse';
$lang->resource->group->create         = 'create';
$lang->resource->group->edit           = 'edit';
$lang->resource->group->copy           = 'copy';
$lang->resource->group->delete         = 'delete';
$lang->resource->group->manageView     = 'manageView';
$lang->resource->group->managePriv     = 'managePriv';
$lang->resource->group->manageMember   = 'manageMember';
$lang->resource->group->managePRJAdmin = 'managePRJAdmin';

$lang->group->methodOrder[5]  = 'browse';
$lang->group->methodOrder[10] = 'create';
$lang->group->methodOrder[15] = 'edit';
$lang->group->methodOrder[20] = 'copy';
$lang->group->methodOrder[25] = 'delete';
$lang->group->methodOrder[30] = 'managePriv';
$lang->group->methodOrder[35] = 'manageMember';
$lang->group->methodOrder[40] = 'managePRJAdmin';

/* User. */
$lang->resource->user = new stdclass();
$lang->resource->user->create         = 'create';
$lang->resource->user->batchCreate    = 'batchCreate';
$lang->resource->user->view           = 'view';
$lang->resource->user->edit           = 'edit';
$lang->resource->user->unlock         = 'unlock';
$lang->resource->user->delete         = 'delete';
$lang->resource->user->todo           = 'todo';
$lang->resource->user->story          = 'story';
$lang->resource->user->task           = 'task';
$lang->resource->user->bug            = 'bug';
$lang->resource->user->testTask       = 'testTask';
$lang->resource->user->testCase       = 'testCase';
$lang->resource->user->execution      = 'execution';
$lang->resource->user->issue          = 'issue';
$lang->resource->user->risk           = 'risk';
$lang->resource->user->dynamic        = 'dynamic';
$lang->resource->user->cropAvatar     = 'cropAvatar';
$lang->resource->user->profile        = 'profile';
$lang->resource->user->batchEdit      = 'batchEdit';
$lang->resource->user->unbind         = 'unbind';
$lang->resource->user->setPublicTemplate = 'setPublicTemplate';

$lang->user->methodOrder[5]  = 'create';
$lang->user->methodOrder[7]  = 'batchCreate';
$lang->user->methodOrder[10] = 'view';
$lang->user->methodOrder[15] = 'edit';
$lang->user->methodOrder[20] = 'unlock';
$lang->user->methodOrder[25] = 'delete';
$lang->user->methodOrder[30] = 'todo';
$lang->user->methodOrder[35] = 'task';
$lang->user->methodOrder[40] = 'bug';
$lang->user->methodOrder[45] = 'project';
$lang->user->methodOrder[50] = 'dynamic';
$lang->user->methodOrder[55] = 'cropAvatar';
$lang->user->methodOrder[60] = 'profile';
$lang->user->methodOrder[65] = 'batchEdit';
$lang->user->methodOrder[70] = 'unbind';
$lang->user->methodOrder[75] = 'setPublicTemplate';

/* Tree. */
$lang->resource->tree = new stdclass();
$lang->resource->tree->browse      = 'browse';
$lang->resource->tree->browseTask  = 'browseTask';
$lang->resource->tree->updateOrder = 'updateOrder';
$lang->resource->tree->manageChild = 'manageChild';
$lang->resource->tree->edit        = 'edit';
$lang->resource->tree->fix         = 'fix';
$lang->resource->tree->delete      = 'delete';

$lang->tree->methodOrder[5]  = 'browse';
$lang->tree->methodOrder[10] = 'browseTask';
$lang->tree->methodOrder[15] = 'updateOrder';
$lang->tree->methodOrder[20] = 'manageChild';
$lang->tree->methodOrder[25] = 'edit';
$lang->tree->methodOrder[30] = 'delete';

/* Report. */
$lang->resource->report = new stdclass();
$lang->resource->report->index            = 'index';
$lang->resource->report->projectDeviation = 'projectDeviation';
$lang->resource->report->productSummary   = 'productSummary';
$lang->resource->report->bugCreate        = 'bugCreate';
$lang->resource->report->bugAssign        = 'bugAssign';
$lang->resource->report->workload         = 'workload';

$lang->report->methodOrder[0]  = 'index';
$lang->report->methodOrder[5]  = 'projectDeviation';
$lang->report->methodOrder[10] = 'productSummary';
$lang->report->methodOrder[15] = 'bugCreate';
$lang->report->methodOrder[20] = 'workload';

/* Search. */
$lang->resource->search = new stdclass();
$lang->resource->search->buildForm   = 'buildForm';
$lang->resource->search->buildQuery  = 'buildQuery';
$lang->resource->search->saveQuery   = 'saveQuery';
$lang->resource->search->deleteQuery = 'deleteQuery';
$lang->resource->search->select      = 'select';
$lang->resource->search->index       = 'index';
$lang->resource->search->buildIndex  = 'buildIndex';

$lang->search->methodOrder[5]  = 'buildForm';
$lang->search->methodOrder[10] = 'buildQuery';
$lang->search->methodOrder[15] = 'saveQuery';
$lang->search->methodOrder[20] = 'deleteQuery';
$lang->search->methodOrder[25] = 'select';
$lang->search->methodOrder[30] = 'index';
$lang->search->methodOrder[35] = 'buildIndex';

/* Admin. */
$lang->resource->admin = new stdclass();
$lang->resource->admin->index     = 'index';
$lang->resource->admin->checkDB   = 'checkDB';
$lang->resource->admin->safe      = 'safeIndex';
$lang->resource->admin->checkWeak = 'checkWeak';
$lang->resource->admin->sso       = 'ssoAction';
$lang->resource->admin->register  = 'register';
$lang->resource->admin->ztCompany = 'ztCompany';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[5]  = 'checkDB';
$lang->admin->methodOrder[10] = 'safeIndex';
$lang->admin->methodOrder[15] = 'checkWeak';
$lang->admin->methodOrder[20] = 'sso';
$lang->admin->methodOrder[25] = 'register';
$lang->admin->methodOrder[30] = 'ztCompany';

/* Extension. */
$lang->resource->extension = new stdclass();
$lang->resource->extension->browse     = 'browse';
$lang->resource->extension->obtain     = 'obtain';
$lang->resource->extension->structure  = 'structureAction';
$lang->resource->extension->install    = 'install';
$lang->resource->extension->uninstall  = 'uninstallAction';
$lang->resource->extension->activate   = 'activateAction';
$lang->resource->extension->deactivate = 'deactivateAction';
$lang->resource->extension->upload     = 'upload';
$lang->resource->extension->erase      = 'eraseAction';
$lang->resource->extension->upgrade    = 'upgrade';

$lang->extension->methodOrder[5]  = 'browse';
$lang->extension->methodOrder[10] = 'obtain';
$lang->extension->methodOrder[15] = 'structure';
$lang->extension->methodOrder[20] = 'install';
$lang->extension->methodOrder[25] = 'uninstall';
$lang->extension->methodOrder[30] = 'activate';
$lang->extension->methodOrder[35] = 'deactivate';
$lang->extension->methodOrder[40] = 'upload';
$lang->extension->methodOrder[45] = 'erase';
$lang->extension->methodOrder[50] = 'upgrade';

/* Webhook. */
$lang->resource->webhook = new stdclass();
$lang->resource->webhook->browse     = 'browse';
$lang->resource->webhook->create     = 'create';
$lang->resource->webhook->edit       = 'edit';
$lang->resource->webhook->delete     = 'delete';
$lang->resource->webhook->log        = 'log';
$lang->resource->webhook->bind       = 'bind';
$lang->resource->webhook->chooseDept = 'chooseDept';

$lang->webhook->methodOrder[5]  = 'browse';
$lang->webhook->methodOrder[10] = 'create';
$lang->webhook->methodOrder[15] = 'edit';
$lang->webhook->methodOrder[20] = 'delete';
$lang->webhook->methodOrder[25] = 'log';
$lang->webhook->methodOrder[30] = 'bind';
$lang->webhook->methodOrder[35] = 'chooseDept';

/* Others. */
$lang->resource->api = new stdclass();
$lang->resource->api->getModel    = 'getModel';
$lang->resource->api->debug       = 'debug';
$lang->resource->api->sql         = 'sql';

$lang->api->methodOrder[5]  = 'getModel';
$lang->api->methodOrder[10] = 'debug';
$lang->api->methodOrder[15] = 'sql';

$lang->resource->file = new stdclass();
$lang->resource->file->download     = 'download';
$lang->resource->file->edit         = 'edit';
$lang->resource->file->delete       = 'delete';
$lang->resource->file->uploadImages = 'uploadImages';
$lang->resource->file->setPublic     = 'setPublic';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'edit';
$lang->file->methodOrder[15] = 'delete';
$lang->file->methodOrder[20] = 'uploadImages';
$lang->file->methodOrder[25] = 'setPublic';

$lang->resource->misc = new stdclass();
$lang->resource->misc->ping = 'ping';

$lang->misc->methodOrder[5] = 'ping';

$lang->resource->message = new stdclass();
$lang->resource->message->index   = 'index';
$lang->resource->message->browser = 'browser';
$lang->resource->message->setting = 'setting';

$lang->message->methodOrder[5]  = 'index';
$lang->message->methodOrder[10] = 'browser';
$lang->message->methodOrder[15] = 'setting';

$lang->resource->action = new stdclass();
$lang->resource->action->trash    = 'trash';
$lang->resource->action->undelete = 'undelete';
$lang->resource->action->hideOne  = 'hideOne';
$lang->resource->action->hideAll  = 'hideAll';
$lang->resource->action->comment  = 'comment';
$lang->resource->action->editComment = 'editComment';

$lang->action->methodOrder[5]  = 'trash';
$lang->action->methodOrder[10] = 'undelete';
$lang->action->methodOrder[15] = 'hideOne';
$lang->action->methodOrder[20] = 'hideAll';
$lang->action->methodOrder[25] = 'comment';
$lang->action->methodOrder[30] = 'editComment';

$lang->resource->backup = new stdclass();
$lang->resource->backup->index       = 'index';
$lang->resource->backup->backup      = 'backup';
$lang->resource->backup->restore     = 'restore';
$lang->resource->backup->change      = 'change';
$lang->resource->backup->delete      = 'delete';
$lang->resource->backup->setting     = 'setting';
$lang->resource->backup->rmPHPHeader = 'rmPHPHeader';

$lang->backup->methodOrder[5]  = 'index';
$lang->backup->methodOrder[10] = 'backup';
$lang->backup->methodOrder[15] = 'restore';
$lang->backup->methodOrder[20] = 'delete';
$lang->backup->methodOrder[25] = 'setting';
$lang->backup->methodOrder[30] = 'rmPHPHeader';

$lang->resource->cron = new stdclass();
$lang->resource->cron->index   = 'index';
$lang->resource->cron->turnon  = 'turnon';
$lang->resource->cron->create  = 'createAction';
$lang->resource->cron->edit    = 'edit';
$lang->resource->cron->toggle  = 'toggle';
$lang->resource->cron->delete  = 'delete';
$lang->resource->cron->openProcess = 'restart';

$lang->cron->methodOrder[5]  = 'index';
$lang->cron->methodOrder[10] = 'turnon';
$lang->cron->methodOrder[15] = 'create';
$lang->cron->methodOrder[20] = 'edit';
$lang->cron->methodOrder[25] = 'toggle';
$lang->cron->methodOrder[30] = 'delete';
$lang->cron->methodOrder[35] = 'openProcess';

$lang->resource->dev = new stdclass();
$lang->resource->dev->api       = 'api';
$lang->resource->dev->db        = 'db';
$lang->resource->dev->editor    = 'editor';
$lang->resource->dev->translate = 'translate';

$lang->dev->methodOrder[5]  = 'api';
$lang->dev->methodOrder[10] = 'db';
$lang->dev->methodOrder[15] = 'editor';
$lang->dev->methodOrder[20] = 'translate';

/* Every version of new privilege. */
$lang->changelog['1.0.1'][] = 'project-computeBurn';

$lang->changelog['1.1'][]   = 'search-saveQuery';
$lang->changelog['1.1'][]   = 'search-deleteQuery';

$lang->changelog['1.2'][]   = 'product-doc';
$lang->changelog['1.2'][]   = 'project-doc';
$lang->changelog['1.2'][]   = 'bug-saveTemplate';
$lang->changelog['1.2'][]   = 'bug-deleteTemplate';
$lang->changelog['1.2'][]   = 'doc-index';
$lang->changelog['1.2'][]   = 'doc-browse';
$lang->changelog['1.2'][]   = 'doc-createLib';
$lang->changelog['1.2'][]   = 'doc-editLib';
$lang->changelog['1.2'][]   = 'doc-deleteLib';
$lang->changelog['1.2'][]   = 'doc-create';
$lang->changelog['1.2'][]   = 'doc-view';
$lang->changelog['1.2'][]   = 'doc-edit';
$lang->changelog['1.2'][]   = 'doc-delete';
$lang->changelog['1.2'][]   = 'doc-deleteFile';

$lang->changelog['1.3'][]   = 'task-start';
$lang->changelog['1.3'][]   = 'task-complete';
$lang->changelog['1.3'][]   = 'task-cancel';
$lang->changelog['1.3'][]   = 'file-delete';

$lang->changelog['1.4'][]   = 'my-testTask';
$lang->changelog['1.4'][]   = 'my-testCase';
$lang->changelog['1.4'][]   = 'task-finish';
$lang->changelog['1.4'][]   = 'task-close';
$lang->changelog['1.4'][]   = 'task-activate';
$lang->changelog['1.4'][]   = 'search-select';

$lang->changelog['1.5'][]   = 'task-batchClose';

$lang->changelog['2.0'][]   = 'my-dynamic';
$lang->changelog['2.0'][]   = 'bug-export';
$lang->changelog['2.0'][]   = 'story-export';
$lang->changelog['2.0'][]   = 'story-reportChart';
$lang->changelog['2.0'][]   = 'task-export';
$lang->changelog['2.0'][]   = 'task-reportChart';
$lang->changelog['2.0'][]   = 'taskcase-export';
$lang->changelog['2.0'][]   = 'company-dynamic';
$lang->changelog['2.0'][]   = 'user-dynamic';
$lang->changelog['2.0'][]   = 'extension-browse';
$lang->changelog['2.0'][]   = 'extension-obtain';
$lang->changelog['2.0'][]   = 'extension-install';
$lang->changelog['2.0'][]   = 'extension-uninstall';
$lang->changelog['2.0'][]   = 'extension-activate';
$lang->changelog['2.0'][]   = 'extension-deactivate';
$lang->changelog['2.0'][]   = 'extension-upload';
$lang->changelog['2.0'][]   = 'extension-erase';

$lang->changelog['2.1'][]   = 'extension-upgrade';

$lang->changelog['2.2'][]   = 'file-edit';

$lang->changelog['2.3'][]   = 'product-dynamic';
$lang->changelog['2.3'][]   = 'project-dynamic';
$lang->changelog['2.3'][]   = 'project-importBug';
$lang->changelog['2.3'][]   = 'story-batchCreate';
$lang->changelog['2.3'][]   = 'task-batchCreate';
$lang->changelog['2.3'][]   = 'testcase-batchCreate';
$lang->changelog['2.3'][]   = 'bug-confirmBug';
$lang->changelog['2.3'][]   = 'svn-diff';
$lang->changelog['2.3'][]   = 'svn-cat';
$lang->changelog['2.3'][]   = 'svn-apiSync';

$lang->changelog['2.4'][]   = 'task-assign';
$lang->changelog['2.4'][]   = 'project-testtask';
$lang->changelog['2.4'][]   = 'todo-export';
$lang->changelog['2.4'][]   = 'product-project';

$lang->changelog['3.0.beta2'][] = 'extension-structure';
$lang->changelog['3.0.beta2'][] = 'product-order';
$lang->changelog['3.0.beta2'][] = 'project-order';

$lang->changelog['3.1'][] = 'todo-batchCreate';

$lang->changelog['3.2'][] = 'my-changePassword';
$lang->changelog['3.2'][] = 'story-batchClose';
$lang->changelog['3.2'][] = 'task-batchEdit';
$lang->changelog['3.2'][] = 'release-export';
$lang->changelog['3.2'][] = 'report-index';
$lang->changelog['3.2'][] = 'report-projectDeviation';
$lang->changelog['3.2'][] = 'report-productSummary';
$lang->changelog['3.2'][] = 'report-bugCreate';
$lang->changelog['3.2'][] = 'report-workload';
$lang->changelog['3.2'][] = 'tree-fix';

$lang->changelog['3.3'][] = 'report-bugAssign';

$lang->changelog['4.0.beta1'][] = 'user-batchCreate';
$lang->changelog['4.0.beta1'][] = 'user-unlock';
$lang->changelog['4.0.beta1'][] = 'admin-checkDB';

$lang->changelog['4.0.beta2'][] = 'todo-batchEdit';
$lang->changelog['4.0.beta2'][] = 'story-batchEdit';
$lang->changelog['4.0.beta2'][] = 'bug-batchEdit';
$lang->changelog['4.0.beta2'][] = 'testcase-batchEdit';
$lang->changelog['4.0.beta2'][] = 'testtask-batchRun';
$lang->changelog['4.0.beta2'][] = 'user-batchEdit';
$lang->changelog['4.0.beta2'][] = 'user-manageContacts';
$lang->changelog['4.0.beta2'][] = 'user-deleteContacts';

$lang->changelog['4.0'][] = 'todo-finish';
$lang->changelog['4.0'][] = 'product-close';
$lang->changelog['4.0'][] = 'project-start';
$lang->changelog['4.0'][] = 'project-activate';
$lang->changelog['4.0'][] = 'project-putoff';
$lang->changelog['4.0'][] = 'project-suspend';
$lang->changelog['4.0'][] = 'project-close';
$lang->changelog['4.0'][] = 'task-record';
$lang->changelog['4.0'][] = 'testtask-start';
$lang->changelog['4.0'][] = 'testtask-close';
$lang->changelog['4.0'][] = 'action-hideOne';
$lang->changelog['4.0'][] = 'action-hideAll';
$lang->changelog['4.0'][] = 'task-editEstimate';
$lang->changelog['4.0'][] = 'task-deleteEstimate';

$lang->changelog['4.1'][] = 'todo-batchFinish';
$lang->changelog['4.1'][] = 'productplan-batchUnlinkStory';
$lang->changelog['4.1'][] = 'company-view';
$lang->changelog['4.1'][] = 'user-story';
$lang->changelog['4.1'][] = 'user-testTask';
$lang->changelog['4.1'][] = 'user-testCase';

$lang->changelog['4.2.beta'][] = 'tree-browseTask';

$lang->changelog['4.3.beta'][] = 'product-batchEdit';
$lang->changelog['4.3.beta'][] = 'project-batchEdit';
$lang->changelog['4.3.beta'][] = 'story-batchReview';
$lang->changelog['4.3.beta'][] = 'story-batchChangePlan';
$lang->changelog['4.3.beta'][] = 'story-batchChangeStage';
$lang->changelog['4.3.beta'][] = 'productplan-linkBug';
$lang->changelog['4.3.beta'][] = 'productplan-unlinkBug';
$lang->changelog['4.3.beta'][] = 'productplan-batchUnlinkBug';
$lang->changelog['4.3.beta'][] = 'bug-batchCreate';
$lang->changelog['4.3.beta'][] = 'testcase-exportTemplet';
$lang->changelog['4.3.beta'][] = 'testcase-import';
$lang->changelog['4.3.beta'][] = 'testcase-showImport';
$lang->changelog['4.3.beta'][] = 'testcase-confirmChange';
$lang->changelog['4.3.beta'][] = 'mail-reset';
$lang->changelog['4.3.beta'][] = 'api-debug';
$lang->changelog['4.3.beta'][] = 'action-editComment';

$lang->changelog['5.0.beta1'][] = 'bug-batchConfirm';
$lang->changelog['5.0.beta1'][] = 'bug-batchResolve';
$lang->changelog['5.0.beta1'][] = 'custom-index';
$lang->changelog['5.0.beta1'][] = 'custom-set';
$lang->changelog['5.0.beta1'][] = 'custom-restore';

$lang->changelog['5.0.beta2'][] = 'git-diff';
$lang->changelog['5.0.beta2'][] = 'git-cat';
$lang->changelog['5.0.beta2'][] = 'git-apiSync';

$lang->changelog['5.3'][] = 'bug-batchClose';

$lang->changelog['6.1'][] = 'story-zeroCase';
$lang->changelog['6.1'][] = 'testcase-groupCase';
$lang->changelog['6.1'][] = 'testtask-groupCase';

$lang->changelog['6.2'][] = 'task-pause';
$lang->changelog['6.2'][] = 'task-restart';
$lang->changelog['6.2'][] = 'testcase-createBug';

$lang->changelog['6.3'][] = 'bug-batchAssignTo';
$lang->changelog['6.3'][] = 'task-batchAssignTo';
$lang->changelog['6.3'][] = 'file-uploadImages';
$lang->changelog['6.3'][] = 'project-batchUnlinkStory';

$lang->changelog['6.4'][] = 'api-sql';
$lang->changelog['6.4'][] = 'backup-index';
$lang->changelog['6.4'][] = 'backup-backup';
$lang->changelog['6.4'][] = 'backup-restore';
$lang->changelog['6.4'][] = 'backup-delete';
$lang->changelog['6.4'][] = 'build-linkStory';
$lang->changelog['6.4'][] = 'build-unlinkStory';
$lang->changelog['6.4'][] = 'build-batchUnlinkStory';
$lang->changelog['6.4'][] = 'build-linkBug';
$lang->changelog['6.4'][] = 'build-unlinkBug';
$lang->changelog['6.4'][] = 'build-batchUnlinkBug';
$lang->changelog['6.4'][] = 'dept-edit';
$lang->changelog['6.4'][] = 'release-linkStory';
$lang->changelog['6.4'][] = 'release-unlinkStory';
$lang->changelog['6.4'][] = 'release-batchUnlinkStory';
$lang->changelog['6.4'][] = 'release-linkBug';
$lang->changelog['6.4'][] = 'release-unlinkBug';
$lang->changelog['6.4'][] = 'release-batchUnlinkBug';
$lang->changelog['6.4'][] = 'story-batchAssignTo';

$lang->changelog['7.1'][] = 'cron-index';
$lang->changelog['7.1'][] = 'cron-turnon';
$lang->changelog['7.1'][] = 'cron-create';
$lang->changelog['7.1'][] = 'cron-edit';
$lang->changelog['7.1'][] = 'cron-toggle';
$lang->changelog['7.1'][] = 'cron-delete';
$lang->changelog['7.1'][] = 'mail-browse';
$lang->changelog['7.1'][] = 'mail-delete';
$lang->changelog['7.1'][] = 'mail-batchDelete';
$lang->changelog['7.1'][] = 'dev-api';
$lang->changelog['7.1'][] = 'dev-db';

$lang->changelog['7.2'][] = 'admin-safeIndex';
$lang->changelog['7.2'][] = 'admin-checkWeak';
$lang->changelog['7.2'][] = 'backup-change';
$lang->changelog['7.2'][] = 'custom-flow';
$lang->changelog['7.2'][] = 'group-manageView';
$lang->changelog['7.2'][] = 'product-updateOrder';
$lang->changelog['7.2'][] = 'project-updateOrder';

$lang->changelog['7.3'][] = 'project-fixFirst';
$lang->changelog['7.3'][] = 'productplan-batchEdit';
$lang->changelog['7.3'][] = 'admin-sso';
$lang->changelog['7.3'][] = 'cron-openProcess';
$lang->changelog['7.3'][] = 'mail-sendCloud';
$lang->changelog['7.3'][] = 'mail-sendcloudUser';

$lang->changelog['7.4.beta'][] = 'release-changeStatus';
$lang->changelog['7.4.beta'][] = 'user-unbind';
$lang->changelog['7.4.beta'][] = 'branch-manage';
$lang->changelog['7.4.beta'][] = 'branch-delete';
$lang->changelog['7.4.beta'][] = 'my-unbind';

$lang->changelog['8.0'][] = 'story-batchChangeBranch';

$lang->changelog['8.0.1'][] = 'bug-linkBugs';
$lang->changelog['8.0.1'][] = 'story-linkStory';
$lang->changelog['8.0.1'][] = 'testcase-linkCases';

$lang->changelog['8.1.3'][] = 'story-batchChangeModule';
$lang->changelog['8.1.3'][] = 'task-batchChangeModule';
$lang->changelog['8.1.3'][] = 'bug-batchChangeModule';
$lang->changelog['8.1.3'][] = 'testcase-batchChangeModule';
$lang->changelog['8.1.3'][] = 'my-manageContacts';
$lang->changelog['8.1.3'][] = 'my-deleteContacts';

$lang->changelog['8.2.beta'][] = 'product-all';
$lang->changelog['8.2.beta'][] = 'project-tree';
$lang->changelog['8.2.beta'][] = 'project-all';
$lang->changelog['8.2.beta'][] = 'project-kanban';
$lang->changelog['8.2.beta'][] = 'project-tree';

$lang->changelog['8.3'][] = 'doc-allLibs';
$lang->changelog['8.3'][] = 'doc-objectLibs';
$lang->changelog['8.3'][] = 'doc-showFiles';

$lang->changelog['8.4'][] = 'branch-sort';
$lang->changelog['8.4'][] = 'story-bugs';
$lang->changelog['8.4'][] = 'story-cases';

$lang->changelog['9.0'][] = 'testcase-bugs';
$lang->changelog['9.0'][] = 'mail-resend';

$lang->changelog['9.1'][] = 'testcase-review';
$lang->changelog['9.1'][] = 'testcase-batchReview';
$lang->changelog['9.1'][] = 'testcase-importFromLib';
$lang->changelog['9.1'][] = 'testcase-batchCaseTypeChange';
$lang->changelog['9.1'][] = 'testcase-batchConfirmStoryChange';
$lang->changelog['9.1'][] = 'testreport-browse';
$lang->changelog['9.1'][] = 'testreport-create';
$lang->changelog['9.1'][] = 'testreport-view';
$lang->changelog['9.1'][] = 'testreport-delete';
$lang->changelog['9.1'][] = 'testreport-edit';
$lang->changelog['9.1'][] = 'testsuite-index';
$lang->changelog['9.1'][] = 'testsuite-browse';
$lang->changelog['9.1'][] = 'testsuite-create';
$lang->changelog['9.1'][] = 'testsuite-view';
$lang->changelog['9.1'][] = 'testsuite-edit';
$lang->changelog['9.1'][] = 'testsuite-delete';
$lang->changelog['9.1'][] = 'testsuite-linkCase';
$lang->changelog['9.1'][] = 'testsuite-unlinkCase';
$lang->changelog['9.1'][] = 'testsuite-batchUnlinkCases';
$lang->changelog['9.1'][] = 'testsuite-library';
$lang->changelog['9.1'][] = 'testsuite-createLib';
$lang->changelog['9.1'][] = 'testsuite-createCase';
$lang->changelog['9.1'][] = 'testsuite-libView';
$lang->changelog['9.1'][] = 'caselib-library';
$lang->changelog['9.1'][] = 'caselib-createLib';
$lang->changelog['9.1'][] = 'caselib-edit';
$lang->changelog['9.1'][] = 'caselib-createCase';
$lang->changelog['9.1'][] = 'caselib-libView';
$lang->changelog['9.1'][] = 'testtask-activate';
$lang->changelog['9.1'][] = 'testtask-block';
$lang->changelog['9.1'][] = 'testtask-report';

$lang->changelog['9.2'][] = 'custom-working';
$lang->changelog['9.2'][] = 'doc-sort';
$lang->changelog['9.2'][] = 'product-build';
$lang->changelog['9.2'][] = 'testsuite-batchCreateCase';
$lang->changelog['9.2'][] = 'testsuite-exportTemplet';
$lang->changelog['9.2'][] = 'testsuite-import';
$lang->changelog['9.2'][] = 'testsuite-showImport';
$lang->changelog['9.5'][] = 'bug-batchActivate';

$lang->changelog['9.6'][] = 'custom-setPublic';
$lang->changelog['9.6'][] = 'datatable-setGlobal';
$lang->changelog['9.6'][] = 'product-export';
$lang->changelog['9.6'][] = 'project-export';
$lang->changelog['9.6'][] = 'project-storyKanban';
$lang->changelog['9.6'][] = 'project-storySort';

$lang->changelog['9.8'][] = 'message-index';
$lang->changelog['9.8'][] = 'message-setting';
$lang->changelog['9.8'][] = 'todo-createCycle';
$lang->changelog['9.8'][] = 'project-importPlanStories';
$lang->changelog['9.8'][] = 'todo-assignTo';
$lang->changelog['9.8'][] = 'todo-activate';
$lang->changelog['9.8'][] = 'todo-close';

$lang->changelog['10.0.alpha'][] = 'my-calendar';
$lang->changelog['10.0.alpha'][] = 'doc-collect';

$lang->changelog['10.1'][] = 'todo-batchClose';
$lang->changelog['10.1'][] = 'project-treeTask';
$lang->changelog['10.1'][] = 'project-treeStory';

$lang->changelog['10.6'][] = 'backup-setting';
$lang->changelog['10.6'][] = 'backup-rmPHPHeader';

$lang->changelog['11.6.2'][] = 'message-browser';

$lang->changelog['12.3'][] = 'testtask-browseUnits';
$lang->changelog['12.3'][] = 'testtask-unitCases';
$lang->changelog['12.3'][] = 'testtask-importUnitResult';
$lang->changelog['12.3'][] = 'job-view';
$lang->changelog['12.3'][] = 'ci-commitResult';

$lang->changelog['12.5'][] = 'story-batchToTask';
$lang->changelog['12.5'][] = 'custom-product';
$lang->changelog['12.5'][] = 'custom-project';
