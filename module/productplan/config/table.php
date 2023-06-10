<?php
global $lang;
$config->productplan->dtable = new stdclass();
$config->productplan->dtable->defaultField = array('id', 'title', 'branch', 'begin', 'end', 'stories', 'bugs', 'storyPoint', 'execution', 'desc', 'actions');

$config->productplan->dtable->fieldList['id']['name']  = 'id';
$config->productplan->dtable->fieldList['id']['title'] = $lang->idAB;
$config->productplan->dtable->fieldList['id']['type']  = 'checkID';
$config->productplan->dtable->fieldList['id']['align'] = 'left';
$config->productplan->dtable->fieldList['id']['fixed'] = 'left';
$config->productplan->dtable->fieldList['id']['group'] = '1';

$config->productplan->dtable->fieldList['title']['name']     = 'title';
$config->productplan->dtable->fieldList['title']['title']    = $lang->productplan->title;
$config->productplan->dtable->fieldList['title']['type']     = 'title';
$config->productplan->dtable->fieldList['title']['minWidth'] = '200';
$config->productplan->dtable->fieldList['title']['fixed']    = 'left';
$config->productplan->dtable->fieldList['title']['link']     = helper::createLink('productplan', 'view', "productID={id}");
$config->productplan->dtable->fieldList['title']['group']    = 'left';

$config->productplan->dtable->fieldList['branch']['name']  = 'branch';
$config->productplan->dtable->fieldList['branch']['title'] = $lang->productplan->branch;
$config->productplan->dtable->fieldList['branch']['type']  = 'branch';
$config->productplan->dtable->fieldList['branch']['group'] = '2';

$config->productplan->dtable->fieldList['begin']['name']  = 'begin';
$config->productplan->dtable->fieldList['begin']['title'] = $lang->productplan->begin;
$config->productplan->dtable->fieldList['begin']['type']  = 'date';
$config->productplan->dtable->fieldList['begin']['group'] = '3';

$config->productplan->dtable->fieldList['end']['name']  = 'end';
$config->productplan->dtable->fieldList['end']['title'] = $lang->productplan->end;
$config->productplan->dtable->fieldList['end']['type']  = 'date';
$config->productplan->dtable->fieldList['end']['group'] = '3';

$config->productplan->dtable->fieldList['stories']['name']  = 'stories';
$config->productplan->dtable->fieldList['stories']['title'] = $lang->productplan->stories;
$config->productplan->dtable->fieldList['stories']['type']  = 'number';
$config->productplan->dtable->fieldList['stories']['group'] = '4';

$config->productplan->dtable->fieldList['bugs']['name']  = 'bugs';
$config->productplan->dtable->fieldList['bugs']['title'] = $lang->productplan->bugs;
$config->productplan->dtable->fieldList['bugs']['type']  = 'number';
$config->productplan->dtable->fieldList['bugs']['group'] = '4';

$config->productplan->dtable->fieldList['storyPoint']['name']  = 'storyPoint';
$config->productplan->dtable->fieldList['storyPoint']['title'] = $lang->productplan->storyPoint;
$config->productplan->dtable->fieldList['storyPoint']['type']  = 'count';
$config->productplan->dtable->fieldList['storyPoint']['group'] = '4';

$config->productplan->dtable->fieldList['execution']['name']  = 'execution';
$config->productplan->dtable->fieldList['execution']['title'] = $lang->productplan->execution;
$config->productplan->dtable->fieldList['execution']['type']  = 'icon';
$config->productplan->dtable->fieldList['execution']['group'] = '5';

$config->productplan->dtable->fieldList['desc']['name']  = 'desc';
$config->productplan->dtable->fieldList['desc']['title'] = $lang->productplan->desc;
$config->productplan->dtable->fieldList['desc']['type']  = 'desc';
$config->productplan->dtable->fieldList['desc']['group'] = '6';

$config->productplan->dtable->fieldList['actions']['name']     = 'actions';
$config->productplan->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->dtable->fieldList['actions']['type']     = 'actions';
$config->productplan->dtable->fieldList['actions']['width']    = '140';
$config->productplan->dtable->fieldList['actions']['sortType'] = false;
$config->productplan->dtable->fieldList['actions']['fixed']    = 'right';
$config->productplan->dtable->fieldList['actions']['list']     = $config->productplan->actionList;
$config->productplan->dtable->fieldList['actions']['menu']     = array('start', 'createExecution', 'linkStory', 'linkBug', 'edit', 'more');
