<?php
$config->company = new stdclass();
$config->company->edit = new stdclass();
$config->company->edit->requiredFields = 'name';

global $lang, $app;
$app->loadLang('action');
$app->loadLang('user');
$config->company->dynamic = new stdclass();
$config->company->dynamic->search['module']               = 'action';

if($config->vision == 'rnd') $config->company->dynamic->search['fields']['product']    = $lang->action->product;

$config->company->dynamic->search['fields']['project']    = '项目';
$config->company->dynamic->search['fields']['execution']  = $lang->action->execution;
$config->company->dynamic->search['fields']['actor']      = $lang->action->actor;
$config->company->dynamic->search['fields']['objectID']   = $lang->action->objectID;
$config->company->dynamic->search['fields']['objectType'] = $lang->action->objectType;
$config->company->dynamic->search['fields']['date']       = $lang->action->date;
$config->company->dynamic->search['fields']['action']     = $lang->action->action;

if($config->vision == 'rnd') $config->company->dynamic->search['params']['product']    = array('operator' => '=',  'control' => 'select', 'values' => '');

$config->company->dynamic->search['params']['project']    = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['execution']  = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['actor']      = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['objectID']   = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->company->dynamic->search['params']['objectType'] = array('operator' => '=',  'control' => 'select', 'values' => $lang->action->search->objectTypeList);
$config->company->dynamic->search['params']['date']       = array('operator' => '=',  'control' => 'input',  'values' => '', 'class' => 'date');
$config->company->dynamic->search['params']['action']     = array('operator' => '=',  'control' => 'select', 'values' => '');

$config->company->browse = new stdClass();
$config->company->browse->search['module']             = 'user';
$config->company->browse->search['fields']['realname'] = $lang->user->realname;
$config->company->browse->search['fields']['email']    = $lang->user->email;
$config->company->browse->search['fields']['dept']     = $lang->user->dept;
$config->company->browse->search['fields']['account']  = $lang->user->account;
$config->company->browse->search['fields']['role']     = $lang->user->role;
$config->company->browse->search['fields']['phone']    = $lang->user->phone;
$config->company->browse->search['fields']['join']     = $lang->user->join;
$config->company->browse->search['fields']['id']       = $lang->user->id;
$config->company->browse->search['fields']['commiter'] = $lang->user->commiter;
$config->company->browse->search['fields']['gender']   = $lang->user->gender;
$config->company->browse->search['fields']['qq']       = $lang->user->qq;
$config->company->browse->search['fields']['skype']    = $lang->user->skype;
$config->company->browse->search['fields']['dingding'] = $lang->user->dingding;
$config->company->browse->search['fields']['weixin']   = $lang->user->weixin;
$config->company->browse->search['fields']['slack']    = $lang->user->slack;
$config->company->browse->search['fields']['whatsapp'] = $lang->user->whatsapp;
$config->company->browse->search['fields']['address']  = $lang->user->address;
$config->company->browse->search['fields']['zipcode']  = $lang->user->zipcode;

$config->company->browse->search['params']['realname'] = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->company->browse->search['params']['email']    = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->company->browse->search['params']['dept']     = array('operator' => 'belong',   'control' => 'select', 'values' => '');
$config->company->browse->search['params']['account']  = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->company->browse->search['params']['role']     = array('operator' => '=',        'control' => 'select', 'values' => $lang->user->roleList);
$config->company->browse->search['params']['phone']    = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->company->browse->search['params']['join']     = array('operator' => '=',        'control' => 'input',  'values' => '', 'class' => 'date');
$config->company->browse->search['params']['id']       = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['commiter'] = array('operator' => 'include',  'control' => 'select', 'values' => '');
$config->company->browse->search['params']['gender']   = array('operator' => '=',        'control' => 'select', 'values' => $lang->user->genderList);
$config->company->browse->search['params']['qq']       = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['skype']    = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['dingding'] = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['weixin']   = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['slack']    = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['whatsapp'] = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->company->browse->search['params']['address']  = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->company->browse->search['params']['zipcode']  = array('operator' => '=',        'control' => 'input',  'values' => '');
