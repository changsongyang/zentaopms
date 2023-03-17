<?php
namespace zin;

$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;

/* More menus. */
$featureBarMore = array();
if(!\commonModel::isTutorialMode())
{
    foreach($lang->product->moreSelects as $key => $value)
    {
        $active = $key == $storyBrowseType ? 'btn-active-text' : '';
        $featureBarMore[] = array(
            'text' => $value,
            'url' => createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$key&param=0&storyType=$storyType"),
            'class' => $active
        );
    }
}

/* Create Button of toolbar. */
$createStoryLink = createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
$batchCreateLink = createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&storyType=$storyType");

$buttonLink  = '';
$buttonTitle = '';
$buttonType  = $from == 'project' ? 'btn-secondary' : 'btn-primary';
if(\common::hasPriv($storyType, 'batchCreate'))
{
    $buttonLink  = empty($productID) ? '' : $batchCreateLink;
    $buttonTitle = $lang->story->batchCreate;
}
if(\common::hasPriv($storyType, 'create'))
{
    $buttonLink  = $createStoryLink;
    $buttonTitle = $lang->story->create;
}

/* DataTable. */
$setting = $this->datatable->getSetting('product');
$cols    = array_values($setting);
foreach($cols as $key => $col)
{
    $col->name  = $col->id;
    $col->width = 80;
    $col->fixed = false;
    if($col->id == 'title')
    {
        $col->flex         = 1;
        $col->type         = 'link';
        $col->sortType     = true;
        $col->nestedToggle = true;
        $col->width        = 300;
        $col->checkbox     = true;
    }
    $cols[$key] = $col;
}

$data = array();
foreach($stories as $story)
{
    $story->taskCount = $storyTasks[$story->id];
    $data[] = $story;
    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child)
    {
        $child->taskCount = $storyTasks[$child->id];
        $data[] = $child;
    }
}

featureBar
(
    li(dropdown(
        $lang->more,
        set::items($featureBarMore)
    )),
    li(searchToggle())
);

toolbar
(
    item(set(array
    (
        'text' => $lang->project->report,
        'icon' => 'bar-chart',
        'class' => 'secondary'
    ))),
    item(set(array
    (
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'secondary',
        'url'   => createLink('product', 'export', $browseType, "status=$browseType&orderBy=$orderBy"),
    ))),
    item(set(array
    (
        'text'  => $lang->import,
        'icon'  => 'import',
        'class' => 'secondary',
        'url'   => createLink('product', 'manageLine', $browseType),
    ))),
    item(set(array
    (
        'text'  => $buttonTitle,
        'icon'  => 'plus',
        'class' => 'primary',
        'url'   => $buttonLink
    )))
);

js
(<<<JS
    window.footerGenerator = function() {
        return [{children: '{$summary}', className: "text-dark"}, "flex", "pager"];
    }
JS
);

dtable
(
    set::className('shadow rounded'),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::nested(true),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
