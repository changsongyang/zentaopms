<?php
$config->project->dtable = new stdclass();
$config->project->dtable->defaultField = array('id', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'progress', 'actions');

$config->project->dtable->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->fieldList['id']['name']     = 'id';
$config->project->dtable->fieldList['id']['type']     = 'checkID';
$config->project->dtable->fieldList['id']['checkbox'] = true;
$config->project->dtable->fieldList['id']['group']    = 1;

$config->project->dtable->fieldList['name']['title']      = $lang->project->name;
$config->project->dtable->fieldList['name']['name']       = 'name';
$config->project->dtable->fieldList['name']['type']       = 'title';
$config->project->dtable->fieldList['name']['link']       = helper::createLink('project', 'index', 'projectID={id}');
$config->project->dtable->fieldList['name']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.model == \'scrum\') return \'icon-sprint text-gray\'; if([\'waterfall\', \'kanban\', \'agileplus\', \'waterfallplus\'].indexOf(row.data.model) !== -1) return \'icon-\' + row.data.model + \' text-gray\'; return \'\';}>RAWJS';
$config->project->dtable->fieldList['name']['group']      = 1;

if(!empty($config->setCode))
{
    $config->project->dtable->fieldList['code']['title'] = $lang->project->code;
    $config->project->dtable->fieldList['code']['name']  = 'code';
    $config->project->dtable->fieldList['code']['type']  = 'text';
    $config->project->dtable->fieldList['code']['group'] = 1;
}

$config->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->dtable->fieldList['status']['name']      = 'status';
$config->project->dtable->fieldList['status']['type']      = 'status';
$config->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->project->dtable->fieldList['status']['group']     = 2;

$config->project->dtable->fieldList['hasProduct']['title'] = $lang->project->type;
$config->project->dtable->fieldList['hasProduct']['name']  = 'hasProduct';
$config->project->dtable->fieldList['hasProduct']['type']  = 'category';
$config->project->dtable->fieldList['hasProduct']['group'] = 2;

$config->project->dtable->fieldList['PM']['title'] = $lang->project->PM;
$config->project->dtable->fieldList['PM']['name']  = 'PM';
$config->project->dtable->fieldList['PM']['type']  = 'avatarBtn';
$config->project->dtable->fieldList['PM']['group'] = 3;

$config->project->dtable->fieldList['budget']['title'] = $lang->project->budget;
$config->project->dtable->fieldList['budget']['name']  = 'budget';
$config->project->dtable->fieldList['budget']['type']  = 'money';
$config->project->dtable->fieldList['budget']['group'] = 4;

$config->project->dtable->fieldList['teamCount']['title'] = $lang->project->teamCount;
$config->project->dtable->fieldList['teamCount']['name']  = 'teamCount';
$config->project->dtable->fieldList['teamCount']['type']  = 'number';
$config->project->dtable->fieldList['teamCount']['group'] = 4;

$config->project->dtable->fieldList['invested']['title'] = $lang->project->invested;
$config->project->dtable->fieldList['invested']['name']  = 'invested';
$config->project->dtable->fieldList['invested']['type']  = 'count';
$config->project->dtable->fieldList['invested']['group'] = 4;

$config->project->dtable->fieldList['begin']['title'] = $lang->project->begin;
$config->project->dtable->fieldList['begin']['name']  = 'begin';
$config->project->dtable->fieldList['begin']['type']  = 'date';
$config->project->dtable->fieldList['begin']['group'] = 5;

$config->project->dtable->fieldList['end']['title'] = $lang->project->end;
$config->project->dtable->fieldList['end']['name']  = 'end';
$config->project->dtable->fieldList['end']['type']  = 'date';
$config->project->dtable->fieldList['end']['group'] = 5;

$config->project->dtable->fieldList['estimate']['title'] = $lang->project->estimate;
$config->project->dtable->fieldList['estimate']['name']  = 'estimate';
$config->project->dtable->fieldList['estimate']['type']  = 'number';
$config->project->dtable->fieldList['estimate']['group'] = 6;

$config->project->dtable->fieldList['consume']['title'] = $lang->project->consume;
$config->project->dtable->fieldList['consume']['name']  = 'consume';
$config->project->dtable->fieldList['consume']['type']  = 'number';
$config->project->dtable->fieldList['consume']['group'] = 6;

$config->project->dtable->fieldList['progress']['title'] = $lang->project->progress;
$config->project->dtable->fieldList['progress']['name']  = 'progress';
$config->project->dtable->fieldList['progress']['type']  = 'progress';
$config->project->dtable->fieldList['progress']['group'] = 6;

$config->project->dtable->fieldList['actions']['title'] = $lang->actions;
$config->project->dtable->fieldList['actions']['name']  = 'actions';
$config->project->dtable->fieldList['actions']['type']  = 'actions';

$config->project->dtable->fieldList['actions']['actionsMap']['start']['icon'] = 'play';
$config->project->dtable->fieldList['actions']['actionsMap']['start']['hint'] = $lang->project->start;
$config->project->dtable->fieldList['actions']['actionsMap']['start']['url']  = helper::createLink('project', 'start', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['close']['icon'] = 'off';
$config->project->dtable->fieldList['actions']['actionsMap']['close']['hint'] = $lang->project->close;
$config->project->dtable->fieldList['actions']['actionsMap']['close']['url']  = helper::createLink('project', 'close', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['active']['icon'] = 'magic';
$config->project->dtable->fieldList['actions']['actionsMap']['active']['hint'] = $lang->project->activate;
$config->project->dtable->fieldList['actions']['actionsMap']['active']['url']  = helper::createLink('project', 'activate', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->project->edit;
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['url']  = helper::createLink('project', 'edit', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['pause']['icon'] = 'pause';
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['hint'] = $lang->project->suspend;
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['url']  = helper::createLink('project', 'suspend', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['group']['icon'] = 'group';
$config->project->dtable->fieldList['actions']['actionsMap']['group']['hint'] = $lang->project->team;
$config->project->dtable->fieldList['actions']['actionsMap']['group']['url']  = helper::createLink('project', 'team', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['perm']['icon'] = 'lock';
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['hint'] = $lang->project->group;
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['url']  = helper::createLink('project', 'group', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['link']['icon'] = 'link';
$config->project->dtable->fieldList['actions']['actionsMap']['link']['hint'] = $lang->project->manageProducts;
$config->project->dtable->fieldList['actions']['actionsMap']['link']['url']  = helper::createLink('project', 'manageProducts', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['icon'] = 'shield-check';
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['hint'] = $lang->project->whitelist;
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['url']  = helper::createLink('project', 'whitelist', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['delete']['icon'] = 'trash';
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['hint'] = $lang->project->delete;
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['url']  = helper::createLink('project', 'delete', 'projectID={id}');

global $app;
$app->loadLang('execution');
$app->loadConfig('execution');

$config->projectExecution = new stdclass();
$config->projectExecution->dtable = new stdclass();

$config->projectExecution->dtable->fieldList['rawID']['title']    = $lang->idAB;
$config->projectExecution->dtable->fieldList['rawID']['name']     = 'rawID';
$config->projectExecution->dtable->fieldList['rawID']['type']     = 'checkID';
$config->projectExecution->dtable->fieldList['rawID']['sortType'] = 'desc';
$config->projectExecution->dtable->fieldList['rawID']['checkbox'] = true;
$config->projectExecution->dtable->fieldList['rawID']['width']    = '80';

$config->projectExecution->dtable->fieldList['name']['title']          = $lang->execution->name;
$config->projectExecution->dtable->fieldList['name']['name']           = 'name';
$config->projectExecution->dtable->fieldList['name']['fixed']          = 'left';
$config->projectExecution->dtable->fieldList['name']['flex']           = 1;
$config->projectExecution->dtable->fieldList['name']['type']           = 'html';
$config->projectExecution->dtable->fieldList['name']['nestedToggle']   = true;
$config->projectExecution->dtable->fieldList['name']['sortType']       = true;

$config->projectExecution->dtable->fieldList['productName']['title']    = $lang->execution->product;
$config->projectExecution->dtable->fieldList['productName']['name']     = 'productName';
$config->projectExecution->dtable->fieldList['productName']['type']     = 'desc';
$config->projectExecution->dtable->fieldList['productName']['sortType'] = true;
$config->projectExecution->dtable->fieldList['productName']['minWidth'] = '160';
$config->projectExecution->dtable->fieldList['productName']['group']    = '1';

$config->projectExecution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->projectExecution->dtable->fieldList['status']['name']      = 'status';
$config->projectExecution->dtable->fieldList['status']['type']      = 'status';
$config->projectExecution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList + $lang->task->statusList;
$config->projectExecution->dtable->fieldList['status']['sortType']  = true;
$config->projectExecution->dtable->fieldList['status']['width']     = '80';
$config->projectExecution->dtable->fieldList['status']['group']     = '1';

$config->projectExecution->dtable->fieldList['PM']['title']    = $lang->execution->PM;
$config->projectExecution->dtable->fieldList['PM']['name']     = 'PM';
$config->projectExecution->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->projectExecution->dtable->fieldList['PM']['sortType'] = true;
$config->projectExecution->dtable->fieldList['PM']['width']    = '100';
$config->projectExecution->dtable->fieldList['PM']['group']    = '2';

$config->projectExecution->dtable->fieldList['begin']['title']    = $lang->execution->begin;
$config->projectExecution->dtable->fieldList['begin']['name']     = 'begin';
$config->projectExecution->dtable->fieldList['begin']['type']     = 'date';
$config->projectExecution->dtable->fieldList['begin']['sortType'] = true;
$config->projectExecution->dtable->fieldList['begin']['width']    = '96';
$config->projectExecution->dtable->fieldList['begin']['group']    = '3';

$config->projectExecution->dtable->fieldList['end']['title']    = $lang->execution->end;
$config->projectExecution->dtable->fieldList['end']['name']     = 'end';
$config->projectExecution->dtable->fieldList['end']['type']     = 'date';
$config->projectExecution->dtable->fieldList['end']['sortType'] = true;
$config->projectExecution->dtable->fieldList['end']['width']    = '96';
$config->projectExecution->dtable->fieldList['end']['group']    = '3';

$config->projectExecution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->projectExecution->dtable->fieldList['totalEstimate']['name']     = 'totalEstimate';
$config->projectExecution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalEstimate']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalEstimate']['group']    = '4';

$config->projectExecution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->projectExecution->dtable->fieldList['totalConsumed']['name']     = 'totalConsumed';
$config->projectExecution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalConsumed']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalConsumed']['group']    = '4';

$config->projectExecution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->projectExecution->dtable->fieldList['totalLeft']['name']     = 'totalLeft';
$config->projectExecution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalLeft']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalLeft']['group']    = '4';

$config->projectExecution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->projectExecution->dtable->fieldList['progress']['name']     = 'progress';
$config->projectExecution->dtable->fieldList['progress']['type']     = 'progress';
$config->projectExecution->dtable->fieldList['progress']['sortType'] = false;
$config->projectExecution->dtable->fieldList['progress']['width']    = '64';
$config->projectExecution->dtable->fieldList['progress']['group']    = '4';

$config->projectExecution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->projectExecution->dtable->fieldList['burn']['name']     = 'burn';
$config->projectExecution->dtable->fieldList['burn']['type']     = 'burn';
$config->projectExecution->dtable->fieldList['burn']['sortType'] = false;
$config->projectExecution->dtable->fieldList['burn']['width']    = '88';
$config->projectExecution->dtable->fieldList['burn']['group']    = '4';

$config->projectExecution->dtable->fieldList['actions']['name']       = 'actions';
$config->projectExecution->dtable->fieldList['actions']['title']      = $lang->actions;
$config->projectExecution->dtable->fieldList['actions']['type']       = 'actions';
$config->projectExecution->dtable->fieldList['actions']['width']      = '160';
$config->projectExecution->dtable->fieldList['actions']['sortType']   = false;
$config->projectExecution->dtable->fieldList['actions']['fixed']      = 'right';
$config->projectExecution->dtable->fieldList['actions']['list']       = $config->execution->actionList;
$config->projectExecution->dtable->fieldList['actions']['scrum']      = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['waterfall']  = array('start', 'createTask', 'createChildStage', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['task']       = array('startTask', 'finishTask', 'closeTask', 'recordWorkhour', 'editTask', 'batchCreate');

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['icon']        = 'play';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['hint']        = $lang->task->start;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['url']         = helper::createLink('task', 'start', 'taskID={rawID}', '', true);
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['icon']        = 'checked';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['hint']        = $lang->task->finish;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['url']         = helper::createLink('task', 'finish', 'taskID={rawID}', '', true);
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['icon']        = 'off';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['hint']        = $lang->task->close;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['url']         = helper::createLink('task', 'close', 'taskID={rawID}', '', true);
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['icon'] = 'time';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['hint'] = $lang->task->record;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['url']  = helper::createLink('task', 'recordWorkhour', 'taskID={rawID}', '', true);
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['icon']  = 'edit';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['hint']  = $lang->task->edit;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['url']   = helper::createLink('task', 'edit', 'taskID={rawID}');

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->task->batchCreate;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['url']  = helper::createLink('task', 'batchCreate', 'execution={execution}&storyID={story}&moduleID={module}&taskID={rawID}');
