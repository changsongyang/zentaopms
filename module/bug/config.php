<?php
$config->bug = new stdClass();
$config->bug->batchCreate  = 10;
$config->bug->longlife     = 7;
$config->bug->removeFields = 'objectTypeList,productList,executionList,gitlabID,gitlabProjectID';

$config->bug->create  = new stdclass();
$config->bug->edit    = new stdclass();
$config->bug->resolve = new stdclass();
$config->bug->create->requiredFields  = 'title,openedBuild';
$config->bug->edit->requiredFields    = 'title';
$config->bug->resolve->requiredFields = 'resolution';

$config->bug->actions = new stdclass();
$config->bug->actions->browse = 'confirm,resolve,close,edit,copy';
$config->bug->actions->view   = 'confirm,assignTo,resolve,close,activate';

$config->bug->browseTypeList = array('all', 'bymodule', 'assigntome', 'openedbyme', 'resolvedbyme', 'assigntonull', 'unconfirmed', 'unresolved', 'unclosed', 'toclosed', 'longlifebugs', 'postponedbugs', 'overduebugs', 'assignedbyme', 'review', 'needconfirm', 'bysearch');

$config->bug->list = new stdclass();
$config->bug->list->allFields = 'id, module, execution, story, task,
    title, keywords, severity, pri, type, os, browser, hardware,
    found, steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, relatedBug,
    case,
    lastEditedBy,
    lastEditedDate';

$config->bug->list->defaultFields = 'id,title,severity,pri,openedBy,assignedTo,resolvedBy,resolution';

$config->bug->exportFields = 'id, product, branch, module, project, execution, story, task,
    title, keywords, severity, pri, type, os, browser,
    steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, relatedBug,
    case,
    lastEditedBy,
    lastEditedDate, files ,feedbackBy, notifyEmail';


$config->bug->list->customCreateFields      = 'execution,noticefeedbackBy,story,task,pri,severity,os,browser,deadline,mailto,keywords';
$config->bug->list->customBatchEditFields   = 'type,severity,pri,productplan,assignedTo,deadline,resolvedBy,resolution,os,browser,keywords';
$config->bug->list->customBatchCreateFields = 'project,execution,steps,type,pri,deadline,severity,os,browser,keywords';

$config->bug->custom = new stdclass();
$config->bug->custom->createFields      = $config->bug->list->customCreateFields;
$config->bug->custom->batchCreateFields = 'project,execution,deadline,steps,type,severity,os,browser,%s';
$config->bug->custom->batchEditFields   = 'type,severity,pri,assignedTo,deadline,status,resolvedBy,resolution';

$config->bug->excludeCheckFileds = ',severities,oses,browsers,lanes,regions,executions,projects,branches,';

$config->bug->editor = new stdclass();
$config->bug->editor->create   = array('id' => 'steps', 'tools' => 'bugTools');
$config->bug->editor->edit     = array('id' => 'steps,comment', 'tools' => 'bugTools');
$config->bug->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'bugTools');
$config->bug->editor->confirm  = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->assignto = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->resolve  = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->close    = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->activate = array('id' => 'comment', 'tools' => 'bugTools');

$config->bug->discardedTypes = array('interface', 'designchange', 'newfeature', 'trackthings');

$config->bug->colorList = new stdclass();
$config->bug->colorList->pri[0]      = '#c0c0c0';
$config->bug->colorList->pri[1]      = '#d50000';
$config->bug->colorList->pri[2]      = '#ff9800';
$config->bug->colorList->pri[3]      = '#2098ee';
$config->bug->colorList->pri[4]      = '#009688';
$config->bug->colorList->pri[5]      = '#919090';
$config->bug->colorList->pri[6]      = '#B6B4B4';
$config->bug->colorList->pri[7]      = '#BDBEBD';
$config->bug->colorList->severity[1] = '#c62828';
$config->bug->colorList->severity[2] = '#ff8f00';
$config->bug->colorList->severity[3] = '#fdd835';
$config->bug->colorList->severity[4] = '#cddc39';
$config->bug->colorList->severity[5] = '#8bc34a';
$config->bug->colorList->severity[6] = '#B6B4B4';
$config->bug->colorList->severity[7] = '#BDBEBD';

global $lang;
$config->bug->actionList = array();
$config->bug->actionList['confirm']['icon']        = 'ok';
$config->bug->actionList['confirm']['text']        = $lang->bug->abbr->confirmed;
$config->bug->actionList['confirm']['url']         = helper::createLink('bug', 'confirm',"bugID={id}");
$config->bug->actionList['confirm']['data-toggle'] = 'modal';

$config->bug->actionList['assignTo']['icon']        = 'hand-right';
$config->bug->actionList['assignTo']['text']        = $lang->bug->assignTo;
$config->bug->actionList['assignTo']['url']         = helper::createLink('bug', 'assignTo',"bugID={id}");
$config->bug->actionList['assignTo']['data-toggle'] = 'modal';

$config->bug->actionList['resolve']['icon']        = 'checked';
$config->bug->actionList['resolve']['text']        = $lang->bug->resolve;
$config->bug->actionList['resolve']['url']         = helper::createLink('bug', 'resolve',"bugID={id}");
$config->bug->actionList['resolve']['data-toggle'] = 'modal';

$config->bug->actionList['close']['icon']        = 'off';
$config->bug->actionList['close']['text']        = $lang->bug->close;
$config->bug->actionList['close']['url']         = helper::createLink('bug', 'close',"bugID={id}");
$config->bug->actionList['close']['data-toggle'] = 'modal';

$config->bug->actionList['activate']['icon']        = 'magic';
$config->bug->actionList['activate']['text']        = $lang->bug->activate;
$config->bug->actionList['activate']['url']         = helper::createLink('bug', 'activate',"bugID={id}");
$config->bug->actionList['activate']['data-toggle'] = 'modal';

$config->bug->actionList['edit']['icon'] = 'edit';
$config->bug->actionList['edit']['text'] = $lang->bug->edit;
$config->bug->actionList['edit']['url']  = helper::createLink('bug', 'edit',"bugID={id}");

$config->bug->actionList['copy']['icon'] = 'copy';
$config->bug->actionList['copy']['text'] = $lang->bug->copy;
$config->bug->actionList['copy']['url']  = helper::createLink('bug', 'create',"productID={product}&branch={branch}&extra=bugID={id}");

include 'config/form.php';
include 'config/table.php';
include 'config/search.php';
