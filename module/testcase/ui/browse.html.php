<?php
declare(strict_types=1);
/**
 * The browse view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$isProjectApp  = $this->app->tab == 'project';
$currentModule = $isProjectApp ? 'project'  : 'testcase';
$currentMethod = $isProjectApp ? 'testcase' : 'browse';
$projectParam  = $isProjectApp ? "projectID={$this->session->project}&" : '';
$initModule    = isset($moduleID) ? (int)$moduleID : 0;

$canModify                  = common::canModify('product', $product);
$canBrowseUnits             = hasPriv('testtask', 'browseunits');
$canBrowseZeroCase          = hasPriv('testcase', 'zerocase');
$canBrowseGroupCase         = hasPriv('testcase', 'groupcase');
$canAutomation              = hasPriv('testcase', 'automation');
$canExport                  = hasPriv('testcase', 'export');
$canExportTemplate          = hasPriv('testcase', 'exportTemplate');
$canExportXmind             = hasPriv('testcase', 'exportXmind');
$canImport                  = hasPriv('testcase', 'import');
$canImportFromLib           = hasPriv('testcase', 'importFromLib');
$canImportXmind             = hasPriv('testcase', 'importXmind');
$canBatchRun                = hasPriv('testtask', 'batchRun');
$canBatchEdit               = hasPriv('testcase', 'batchEdit');
$canBatchReview             = hasPriv('testcase', 'batchReview') && ($config->testcase->needReview || !empty($config->testcase->forceReview));
$canBatchDelete             = hasPriv('testcase', 'batchDelete');
$canBatchCaseTypeChange     = hasPriv('testcase', 'batchCaseTypeChange');
$canBatchConfirmStoryChange = hasPriv('testcase', 'batchConfirmStoryChange');
$canBatchChangeBranch       = hasPriv('testcase', 'batchChangeBranch') && $this->session->currentProductType && $this->session->currentProductType != 'normal';
$canBatchChangeModule       = hasPriv('testcase', 'batchChangeModule') && !empty($productID) && ($product->type == 'normal' || $branch !== 'all');
$canBatchChangeScene        = hasPriv('testcase', 'batchChangeScene');
$canImportToLib             = hasPriv('testcase', 'importToLib');
$canBatchAction             = ($canBatchRun || $canBatchEdit || $canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange || $canBatchChangeBranch || $canBatchChangeModule || $canBatchChangeScene || $canImportToLib);

$lang->testcase->typeList[''] = $lang->testcase->allType;
if(!isset($param)) $param = 0;

/* Process variables of case type menu. */
$currentCaseType = zget($lang->testcase->typeList, $caseType, '');
$currentTypeName = empty($currentCaseType) ? $lang->testcase->allType : $currentCaseType;
$caseTypeItems   = array();
foreach($lang->testcase->typeList as $type => $typeName)
{
    if($canBrowseUnits and $type == 'unit')
    {
        $url  = $this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
        $text = $lang->testcase->browseUnits;
    }
    elseif(isset($groupBy))
    {
        $url  = $this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$type");
        $text = $typeName;
    }
    else
    {
        $url  = $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type");
        $text = $typeName;
    }

    $caseTypeItems[] = array('text' => $text, 'url' => $url, 'active' => $type == $caseType);
}

/* Process variables of sutie menu. */
$currentSuiteID   = isset($suiteID) ? (int)$suiteID : 0;
$currentSuite     = zget($suiteList, $currentSuiteID, '');
$currentSuiteName = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;
$suiteItems       = array();
if(empty($suiteList))
{
    if(empty($productID) or common::canModify('product', $product))
    {
        $suiteItems[] = array('text' => $lang->testsuite->create, 'url' => $this->createLink('testsuite', 'create', "productID=$productID"));
    }
}
else
{
    foreach($suiteList as $suiteID => $suite)
    {
        $suiteItems[] = array('text' => $suite->name, 'url' => $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySuite&param=$suiteID"), 'active' => $suiteID == (int)$currentSuiteID);
    }
}

/* Process variables of other menu. */
$currentOtherName = $this->cookie->onlyScene ? $lang->testcase->onlyScene : $lang->other;
$otherItems   = array();
$otherItems[] = array('text' => $lang->testcase->onlyScene);

featureBar
(
    to::before
    (
        productMenu
        (
            set::title($currentTypeName),
            set::items($caseTypeItems)
        )
    ),
    set::linkParams($projectParam . "productID=$productID&branch=$branch&browseType={key}&param=0&caseType=$caseType"),
    $canBrowseZeroCase ? li
    (
        set::class('nav-item'),
        a
        (
            set::href($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=" . ($isProjectApp ? $this->session->project : 0))),
            set('data-app', $app->tab),
            set('data-id', 'zerocaseTab'),
            $lang->testcase->zeroCase
        )
    ) : null,
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $currentSuiteName
        ),
        set::items($suiteItems)
    ),
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $currentOtherName
        ),
        set::items($otherItems)
    ),
    li(searchToggle(set::open($browseType == 'bysearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

$viewItems   = array();
$exportItems = array();
$importItems = array();
if(!empty($productID))
{
    if($canBrowseGroupCase)
    {
        $link = inlink('groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID");;
        $viewItems[] = array('text' => $lang->testcase->groupView, 'url' => $link, 'data-app' => $app->tab);
    }

    if($canExport)
    {
        $link = $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=0&browseType=$browseType");
        $exportItems[] = array('text' => $lang->testcase->export, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
    }
    if($canExportTemplate)
    {
        $link = $this->createLink('testcase', 'exportTemplate', "productID=$productID");
        $exportItems[] = array('text' => $lang->testcase->exportTemplate, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab, 'data-width' => '65%');
    }
    if($canExportXmind)
    {
        $link = $this->createLink('testcase', 'exportXmind', "productID=$productID&moduleID=$moduleID&branch=$branch");
        $exportItems[] = array('text' => $lang->testcase->xmindExport, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
    }

    if($canModify)
    {
        if($canImport)
        {
            $link = $this->createlink('testcase', 'import', "productID=$productID&branch=$branch");
            $importItems[] = array('text' => $lang->testcase->fileImport, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
        }

        if($canImportFromLib)
        {
            $link  = $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch&libID=0&orderBy=id_desc&browseType=&queryID=10&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
            $importItems[] = array('url' => $link, 'text' => $lang->testcase->importFromLib, 'data-toggle' => 'modal', 'data-app' => $app->tab);
        }

        if($canImportXmind)
        {
            $link = $this->createLink('testcase', 'importXmind', "productID=$productID&branch=$branch");
            $importItems[] = array('url' => $link, 'text' => $lang->testcase->xmindImport, 'data-toggle' => 'modal', 'data-app' => $app->tab);
        }
    }
}

toolbar
(
    $viewItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end'),
    ) : null,
    $canAutomation ? btn
    (
        set
        (
            array('icon' => 'wrench', 'hint' => $lang->testcase->automation, 'url' => inlink('automation', "productID=$productID"), 'class' => 'btn btn-link ghost square', 'data-toggle' => 'modal', 'data-width' => '50%')
        )
    ) : null,
    $exportItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('export')
        ),
        set::arrow(false),
        set::items($exportItems),
        set::placement('bottom-end'),
    ) : null,
    $importItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('import')
        ),
        set::arrow(false),
        set::items($importItems),
        set::placement('bottom-end'),
    ) : null,
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
            $lang->testcase->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array
                (
                    array('text' => $lang->testcase->create,      'url' => helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->newScene,    'url' => helper::createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule"))
                )
            ),
            set::placement('bottom-end'),
        )
    )
);

$closeLink = $browseType == 'bymodule' ? createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

$caseProductIds = array();
foreach($cases as $case) $caseProductIds[$case->product] = $case->product;
$caseProductID = count($caseProductIds) > 1 ? 0 : $productID;

$footToolbar = $canBatchAction ? array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        $canBatchRun ? array('text' => $lang->testtask->runCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy")) : null,
        $canBatchEdit ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('testcase', 'batchEdit', "productID=$caseProductID&branch=$branch")) : null,
        ($canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange) ? array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    )),
    $canBatchChangeModule ? array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'btnType' => 'primary', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    $canBatchChangeScene ? array('caret' => 'up', 'text' => $lang->testcase->scene, 'btnType' => 'primary', 'url' => '#navScene','data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    $canImportToLib ? array('text' => $lang->testcase->importToLib, 'btnType' => 'primary', 'data-toggle' => 'modal', 'data-url' => '#importToLib') : null,
)) : null;

if($canBatchReview)
{
    $reviewItems = array();
    foreach($lang->testcase->reviewResultList as $key => $result)
    {
        if($key == '') continue;
        $reviewItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => $this->createLink('testcase', 'batchReview', "result=$key"));
    }
}

if($canBatchCaseTypeChange)
{
    $typeItems = array();
    foreach($lang->testcase->typeList as $key => $result)
    {
        $typeItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchCaseTypeChange', "result=$key"));
    }
}

if($canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange)
{
    zui::menu
    (
        set::id('navActions'),
        set::class('menu dropdown-menu'),
        set::items(array
        (
            $canBatchReview ? array('text' => $lang->testcase->review, 'class' => 'not-hide-menu', 'items' => $reviewItems) : null,
            $canBatchDelete ? array('text' => $lang->delete, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchDelete', "productID=$productID")) : null,
            $canBatchCaseTypeChange ? array('text' => $lang->testcase->type, 'class' => 'not-hide-menu', 'items' => $typeItems) : null,
            $canBatchConfirmStoryChange ? array('text' => $lang->testcase->confirmStoryChange, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchConfirmStoryChange', "productID=$productID")) : null,
        ))
    );
}

if($canBatchChangeModule)
{
    $moduleItems = array();
    foreach($modules as $moduleId => $module) $moduleItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleId"));

    menu
    (
        set::id('navModule'),
        set::class('dropdown-menu'),
        set::items($moduleItems)
    );
}

if($canBatchChangeScene)
{
    $sceneItems = array();
    foreach($iscenes as $sceneID => $scene) $sceneItems[] = array('text' => $scene, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeScene', "sceneId=$sceneID"));

    menu
    (
        set::id('navScene'),
        set::class('dropdown-menu'),
        set::items($sceneItems)
    );
}

$config->testcase->dtable->fieldList['story']['map'] = $stories;

$this->testcase->buildOperateMenu(null, 'browse');

foreach($scenes as $scene)
{
    if($scene->isCase != 1) continue;

    $stages = array_filter(explode(',', $scene->stage));
    foreach($stages as $key => $stage) $stages[$key] = zget($lang->testcase->stageList, $stage);
    $scene->stage = implode($lang->comma, $stages);

    $actions = array();
    foreach($this->config->testcase->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->testcase->isClickable($scene, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $scene->actions = $actions;
}

dtable
(
    set::userMap($users),
    set::cols(array_values($config->testcase->dtable->fieldList)),
    set::data(array_values($scenes)),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render();
