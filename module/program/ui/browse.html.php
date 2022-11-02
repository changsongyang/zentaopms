<?php
common::sortFeatureMenu();

/* Set toolbar. */
$toolbar = toolbar();
foreach($this->lang->program->featureBar['browse'] as $key => $label)
{
    $tab = tab($label)->link(inlink('browse', "status=$key&orderBy=$orderBy"))->active($key == $status);
    $toolbar->append($tab);
}

if(common::hasPriv('project', 'batchEdit') and $programType != 'bygrid' and $hasProject === true)
{
    $toolbar->append(html::checkbox('editProject', array('1' => $lang->project->edit), '', $this->cookie->editProject ? 'checked=checked' : ''));
}

$toolbar->append('<a class="querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $lang->user->search . '</a>');

/* Set actionbar. */
$actionbar = actionbar();
if(common::hasPriv('project', 'create'))
{
    $button = button('<i class="icon icon-plus"></i> ' . $this->lang->project->create)->link($this->createLink('project', 'createGuide', "programID=0&from=PGM"))->misc('class="btn btn-secondary" data-toggle="modal" data-target="#guideDialog"');
    $actionbar->append($button);
}

if(common::hasPriv('program', 'create'))
{
    $button = html::a($this->createLink('program', 'create'), "<i class='icon icon-plus'></i> " . $this->lang->program->create, '', "class='btn btn-primary create-program-btn'");
    $actionbar->append($button);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
$menu->actionbar = $actionbar;

/* Table. */
$table = dtable();
$table->buildCols($this->config->program->dtable->fieldList);

$rows = array();
$this->loadModel('project');
foreach($programs as $program)
{
    $row = new stdclass();
    foreach($this->config->program->dtable->fieldList as $field)
    {
        $fieldName = $field['name'];
        $row->$fieldName = $this->program->buildCell($field, $program, $users, $usersAvatar, $userIdPairs, $PMList, $progressList);
    }

    $row->id     = $program->id;
    $row->parent = $program->parent;
    $rows[] = $row;
}

$table->search($status, $moduleName);
$table->form('programForm', 'main-table', "data-preserve-nested='true'");
$table->data($rows);

$content = block();
$content->table = $table;

/* Layout. */
$page = page('list');
$page->top->menu      = $menu;
$page->right->content = $content;

$hide = $status == 'bySearch' ? 'hide' : '';
$table->footer($status != 'bySearch' ? $pager : '', $hide, $summary, 'programSummary');

$page->x();

js::set('status', $status);
js::set('orderBy', $orderBy);
js::set('edit', $lang->edit);
js::set('selectAll', $lang->selectAll);
js::set('hasProject', $hasProject);
js::set('checkedProjects', $lang->program->checkedProjects);
js::set('cilentLang', $this->app->getClientLang());
