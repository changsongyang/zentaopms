<?php
declare(strict_types=1);
global $lang;

$config->bug->form = new stdclass();

$config->bug->form->create = array();
$config->bug->form->create['title']       = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->bug->form->create['openedBuild'] = array('required' => true, 'type' => 'array',  'filter' => 'join');

$config->bug->form->create['product']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['branch']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['module']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['project']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['execution']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['assignedTo']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['deadline']    = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->create['feedbackBy']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['notifyEmail'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['type']        = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->create['os']       = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['browser']  = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['color']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['severity'] = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->create['pri']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->create['steps']    = array('required' => false, 'type' => 'string', 'default' => $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect);

$config->bug->form->create['story']       = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['task']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['oldTaskID']   = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['case']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['caseVersion'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['result']      = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['testtask']    = array('required' => false, 'type' => 'int', 'default' => 0);

$config->bug->form->create['mailto']   = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['keywords'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['status']   = array('required' => false, 'type' => 'string', 'default' => 'active');
$config->bug->form->create['issueKey'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['uid']      = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->edit = array();
$config->bug->form->edit['title']          = array('required' => true,  'type' => 'string', 'filter'  => 'trim');
$config->bug->form->edit['openedBuild']    = array('required' => true,  'type' => 'array',  'filter'  => 'join');
$config->bug->form->edit['product']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['branch']         = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['project']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['execution']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['plan']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['module']         = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['story']          = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['task']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['case']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['testtask']       = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['duplicateBug']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['severity']       = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->edit['pri']            = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->edit['type']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['status']         = array('required' => false, 'type' => 'string', 'default' => 'active');
$config->bug->form->edit['keywords']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['steps']          = array('required' => false, 'type' => 'string', 'default' => $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect);
$config->bug->form->edit['resolution']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['resolvedBuild']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['feedbackBy']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['resolvedBy']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['closedBy']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['notifyEmail']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['uid']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['os']             = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['browser']        = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['linkBug']        = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['mailto']         = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['deadline']       = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['resolvedDate']   = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['closedDate']     = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['lastEditedDate'] = array('required' => false, 'type' => 'date',   'default' => helper::now());

global $app;
$config->bug->form->close = array();
$config->bug->form->close['status']         = array('required' => false, 'type' => 'string', 'default' => 'closed');
$config->bug->form->close['confirmed']      = array('required' => false, 'type' => 'int',    'default' => 1);
$config->bug->form->close['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->close['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['closedBy']       = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->close['closedDate']     = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['comment']        = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->assignTo = array();
$config->bug->form->assignTo['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->assignTo['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->assignTo['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->assignTo['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->assignTo['mailto']         = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');

$config->bug->form->resolve = array();
$config->bug->form->resolve['status']         = array('required' => false, 'type' => 'string', 'default' => 'resolved');
$config->bug->form->resolve['resolvedBuild']  = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->resolve['resolution']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->resolve['resolvedBy']     = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->resolve['resolvedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->resolve['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->resolve['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->resolve['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->resolve['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->resolve['duplicateBug']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->resolve['buildName']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->resolve['createBuild']    = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->resolve['buildExecution'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->resolve['comment']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->resolve['uid']            = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->activate = array();
$config->bug->form->activate['assignedTo']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->activate['openedBuild'] = array('required' => false, 'type' => 'array',  'default' => array());
$config->bug->form->activate['comment']     = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->batchActivate = array();
$config->bug->form->batchActivate['bugIdList']       = array('type' => 'array', 'required' => true);
$config->bug->form->batchActivate['statusList']      = array('type' => 'array', 'required' => false, 'default' => array());
$config->bug->form->batchActivate['assignedToList']  = array('type' => 'array', 'required' => false, 'default' => array());
$config->bug->form->batchActivate['openedBuildList'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->bug->form->batchActivate['commentList']     = array('type' => 'array', 'required' => false, 'default' => array());

$config->bug->form->batchCreate = array();
$config->bug->form->batchCreate['modules']      = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['projects']     = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['executions']   = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['branches']     = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['lanes']        = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['openedBuilds'] = array('required' => true,  'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['title']        = array('required' => true,  'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['deadlines']    = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['stepses']      = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['types']        = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['pris']         = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['severities']   = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['oses']         = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['browsers']     = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchCreate['keywords']     = array('required' => false, 'type' => 'array', 'default' => array());

$config->bug->form->batchEdit = array();
$config->bug->form->batchEdit['types']         = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['severities']    = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['pris']          = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['titles']        = array('required' => true,  'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['branches']      = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['modules']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['plans']         = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['assignedTos']   = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['deadlines']     = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['os']            = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['browsers']      = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['keywords']      = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['resolvedBys']   = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['resolutions']   = array('required' => false, 'type' => 'array', 'default' => array());
$config->bug->form->batchEdit['duplicateBugs'] = array('required' => false, 'type' => 'array', 'default' => array());

$config->bug->form->confirm = array();
$config->bug->form->confirm['pri']            = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->confirm['type']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->confirm['status']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->confirm['mailto']         = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->confirm['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->confirm['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->confirm['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->confirm['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->confirm['comment']        = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
