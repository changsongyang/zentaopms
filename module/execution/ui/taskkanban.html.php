<?php
declare(strict_types=1);
/**
 * The taskkanban view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wangy <yidong@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$laneCount = 0;
$lanePairs  = array();
$colPairs  = array();
$groupCols = array(); // 卡片可以移动到的同一group下的列。
foreach($kanbanGroup as $index => $group)
{
    $groupID = $group['id'];

    $group['getLane']     = jsRaw('window.getLane');
    $group['getCol']      = jsRaw('window.getCol');
    $group['getItem']     = jsRaw('window.getItem');
    $group['canDrop']     = jsRaw('window.canDrop');
    $group['onDrop']      = jsRaw('window.onDrop');
    $group['minColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth;
    $group['maxColWidth'] = $execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth;
    $group['colProps']    = array('actions' => jsRaw('window.getColActions'));
    $group['itemProps']   = array('actions' => jsRaw('window.getItemActions'));

    foreach($group['data']['lanes'] as $lane) $lanePairs[$lane['id']] = $lane['type'];
    foreach($group['data']['cols'] as $colIndex => $col)
    {
        $colPairs[$col['id']] = $col['type'];
        if(isset($col['parent']) && $col['parent'] != '-1') $groupCols[$groupID][$col['id']] = $col['title'];
    }

    $kanbanGroup[$index] = $group;
}
$laneCount += count($kanbanGroup);

$checkObject = new stdclass();
$checkObject->execution = $executionID;
$canCreateTask       = common::canModify('execution', $execution) && common::hasPriv('task', 'create', $checkObject);
$canBatchCreateTask  = common::canModify('execution', $execution) && common::hasPriv('task', 'batchCreate', $checkObject);
$canCreateBug        = (common::canModify('execution', $execution) && $productID && common::hasPriv('bug', 'create'));
$canBatchCreateBug   = (common::canModify('execution', $execution) && $productID && common::hasPriv('bug', 'batchCreate'));
$canImportBug        = (common::canModify('execution', $execution) && $productID && common::hasPriv('execution', 'importBug'));
$canCreateStory      = (common::canModify('execution', $execution) && $productID && common::hasPriv('story', 'create'));
$canBatchCreateStory = (common::canModify('execution', $execution) && $productID && common::hasPriv('story', 'batchCreate'));
$canLinkStory        = (common::canModify('execution', $execution) && $productID && common::hasPriv('execution', 'linkStory') && !empty($execution->hasProduct));
$canLinkStoryByPlan  = (common::canModify('execution', $execution) && $productID && common::hasPriv('execution', 'importplanstories') && !$hiddenPlan && !empty($execution->hasProduct));
$hasStoryButton      = ($canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlan);
$hasTaskButton       = ($canCreateTask or $canBatchCreateTask or $canImportBug);
$hasBugButton        = ($canCreateBug or $canBatchCreateBug);

jsVar('laneCount',  $laneCount);
jsVar('cardLang', $lang->kanbancard);
jsVar('groupCols', $groupCols);
jsVar('lanePairs', $lanePairs);
jsVar('colPairs', $colPairs);
jsVar('vision', $config->vision);
jsVar('colorList', $config->kanban->cardColorList);
jsVar('executionID', $executionID);
jsVar('productID', $productID);
jsVar('kanbanGroup', $kanbanGroup);
jsVar('kanbanList', array_keys($kanbanGroup));
jsVar('browseType', $browseType);
jsVar('groupBy', $groupBy);
jsVar('productNum', $productNum);
jsVar('priv', array(
        'canEditName'         => common::hasPriv('kanban', 'setColumn'),
        'canSetWIP'           => common::hasPriv('kanban', 'setWIP'),
        'canSetLane'          => common::hasPriv('kanban', 'setLane'),
        'canSortCards'        => common::hasPriv('kanban', 'cardsSort'),
        'canCreateTask'       => $canCreateTask,
        'canBatchCreateTask'  => $canBatchCreateTask,
        'canImportBug'        => $canImportBug,
        'canCreateBug'        => $canCreateBug,
        'canBatchCreateBug'   => $canBatchCreateBug,
        'canCreateStory'      => $canCreateStory,
        'canBatchCreateStory' => $canBatchCreateStory,
        'canLinkStory'        => $canLinkStory,
        'canLinkStoryByPlan'  => $canLinkStoryByPlan,
        'canAssignTask'       => common::hasPriv('task', 'assignto'),
        'canAssignStory'      => common::hasPriv('story', 'assignto'),
        'canFinishTask'       => common::hasPriv('task', 'finish'),
        'canPauseTask'        => common::hasPriv('task', 'pause'),
        'canCancelTask'       => common::hasPriv('task', 'cancel'),
        'canCloseTask'        => common::hasPriv('task', 'close'),
        'canActivateTask'     => common::hasPriv('task', 'activate'),
        'canStartTask'        => common::hasPriv('task', 'start'),
        'canAssignBug'        => common::hasPriv('bug', 'assignto'),
        'canConfirmBug'       => common::hasPriv('bug', 'confirmBug'),
        'canActivateBug'      => common::hasPriv('bug', 'activate'),
        'canResolveBug'       => common::hasPriv('bug', 'resolve'),
        'canCloseBug'         => common::hasPriv('bug', 'close'),
        'canCloseStory'       => common::hasPriv('story', 'close')
    )
);
jsVar('executionLang', $lang->execution);
jsVar('storyLang', $lang->story);
jsVar('taskLang', $lang->task);
jsVar('bugLang', $lang->bug);
jsVar('editName', $lang->execution->editName);
jsVar('setWIP', $lang->execution->setWIP);
jsVar('sortColumn', $lang->execution->sortColumn);
jsVar('kanbanLang', $lang->kanban);
jsVar('deadlineLang', $lang->task->deadlineAB);
jsVar('estStartedLang', $lang->task->estStarted);
jsVar('noAssigned', $lang->task->noAssigned);
jsVar('userList', $userList);
jsVar('entertime', time());
jsVar('fluidBoard', $execution->fluidBoard);
jsVar('displayCards', $execution->displayCards);
jsVar('needLinkProducts', $lang->execution->needLinkProducts);
jsVar('hourUnit', $config->hourUnit);
jsVar('orderBy', $storyOrder);
jsVar('minColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth);
jsVar('maxColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth);
jsVar('teamWords', $lang->execution->teamWords);
jsVar('canImportBug', $features['qa']);

$canCreateObject = ($canCreateTask or $canBatchCreateTask or $canImportBug or $canCreateBug or $canBatchCreateBug or $canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlan);
row
(
    setClass('items-center justify-between mb-3'),
    cell
    (
        setClass('flex'),
        $features['qa'] ? inputControl
        (
            setClass('c-type'),
            picker
            (
                set::width('200'),
                set::name('type'),
                set::items($lang->kanban->type),
                set::value($browseType),
                set::required(true),
                set::onchange('changeBrowseType()')
            )
        ) : null,
        $browseType != 'all' ? inputControl
        (
            setClass('c-group ml-5'),
            picker
            (
                set::width('200'),
                set::name('group'),
                set::items($lang->kanban->group->$browseType),
                set::value($groupBy),
                set::required(true),
                set::onchange('changeGroupBy()')
            )
        ) : null
    ),
    cell
    (
        setClass('flex toolbar'),
        inputGroup
        (
            set::style(array('display' => 'none')),
            setID('taskKanbanSearch'),
            inputControl
            (
                setID('searchBox'),
                setClass('search-box'),
                input
                (
                    setID('taskKanbanSearchInput'),
                    set::name('taskKanbanSearchInput'),
                    set::placeholder($lang->execution->pleaseInput)
                )
            )
        ),
        btn(setClass('querybox-toggle'), set::type('link'), set::onclick('toggleSearchBox()'), set::icon('search'), $lang->searchAB),
        common::hasPriv('task', 'export') ? btn(set::type('link'), set::url(createLink('task', 'export', "execution=$executionID&orderBy=$orderBy&type=unclosed")), set::icon('export'), set('data-toggle', 'modal'), $lang->export) : null,
        $canBeChanged ? dropdown
        (
            setID('importAction'),
            set::arrow(true),
            set::caret(false),
            btn(set::type('link'), set::icon('import'), $lang->import),
            set::items(array
            (
                common::hasPriv('execution', 'importTask') && $execution->multiple ? array('text' => $lang->execution->importTask, 'url' => createLink('execution', 'importTask', "execution=$execution->id")) : null,
                ($features['qa'] && common::hasPriv('execution', 'importBug')) ? array('text' => $lang->execution->importBug, 'url' => createLink('execution', 'importBug', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null
            ))
        ) : null,
        dropdown
        (
            setClass('kanbanSetting mr-2'),
            btn(set::type('link'), icon('ellipsis-v')),
            set::caret(false),
            set::items(array
            (
                common::hasPriv('execution', 'setKanban') ? array('text' => $lang->execution->setKanban, 'url' => createLink('execution', 'setKanban', "executionID=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'sm') : null,
                common::hasPriv('execution', 'printKanban') ? array('text' => $lang->execution->printKanban, 'url' => createLink('execution', 'printKanban', "executionID=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'sm', 'id' => 'printKanban') : null,
                array('text' => $lang->execution->fullScreen, 'url' => "javascript:$(\"#kanbanList\").fullscreen();")
            ))
        ),
        $canCreateObject ? dropdown
        (
            setID('createDropdown'),
            btn(setClass('primary'),set::icon('plus'), $lang->create),
            set::items(array
            (
                $features['story'] && common::hasPriv('story', 'create') ? ($hasStoryButton && $canCreateStory && !empty($productID) ? array('text' => $lang->execution->createStory, 'url' => createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : array('text' => $lang->story->create, 'data-on' => 'click', 'data-do' => "zui.Modal.alert('" . $lang->execution->needLinkProducts . "')")) : null,
                ($features['story'] && $hasStoryButton && $canBatchCreateStory) ? array('text' => $lang->execution->batchCreateStory, 'url' => createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null,
                ($features['story'] && $hasStoryButton && $canLinkStory) ? array('text' => $lang->execution->linkStory, 'url' => createLink('execution', 'linkStory', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null,
                ($features['story'] && $hasStoryButton && $canLinkStoryByPlan) ? array('text' => $lang->execution->linkStoryByPlan, 'url' => '#linkStoryByPlan', 'data-toggle' => 'modal', 'data-size' => 'sm') : null,
                ($features['story'] && $hasStoryButton && $features['qa']) ? array('class' => 'divider menu-divider') : null,
                $features['qa'] && common::hasPriv('bug', 'create') ? ($canCreateBug && !empty($productID) ? array('text' => $lang->bug->create, 'url' => createLink('bug', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : array('text' => $lang->bug->create, 'data-on' => 'click', 'data-do' => "zui.Modal.alert('" . $lang->execution->needLinkProducts . "')" )) : null,
                ($features['qa'] && $canBatchCreateBug) ? array('text' => $lang->bug->batchCreate, 'url' => ($productNum > 1 ? '#batchCreateBug' : createLink('bug', 'batchCreate', "productID=$productID&branch=0&executionID=$execution->id")), 'data-toggle' => 'modal', 'data-size' => $productNum > 1 ? null : 'lg') : null,
                ($features['qa'] && $canImportBug) ? array('text' => $lang->execution->importBug, 'url' => createLink('execution', 'importBug', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null,
                ($features['story'] && $hasStoryButton && $features['qa']) ? array('text' => '', 'class' => 'divider menu-divider') : null,
                ($canCreateTask) ? array('text' => $lang->task->create, 'url' => createLink('task', 'create', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null,
                ($canBatchCreateTask) ? array('text' => $lang->execution->batchCreateTask, 'url' => createLink('task', 'batchCreate', "execution=$execution->id"), 'data-toggle' => 'modal', 'data-size' => 'lg') : null
            ))
        ) : null
    )
);

div
(
    set::id('kanbanList'),
    setClass('bg-white'),
    zui::kanbanList
    (
        set::key('kanban'),
        set::items($kanbanGroup),
        set::height('calc(100vh - 120px)')
    )
);

$linkStoryByPlanTips = $lang->execution->linkNormalStoryByPlanTips;
$linkStoryByPlanTips = $execution->multiple ? $linkStoryByPlanTips : str_replace($lang->execution->common, $lang->projectCommon, $linkStoryByPlanTips);

modal
(
    setID('linkStoryByPlan'),
    setData('size', '500px'),
    set::modalProps(array('title' => $lang->execution->linkStoryByPlan)),
    div
    (
        setClass('flex-auto'),
        icon('info-sign', setClass('warning-pale rounded-full mr-1')),
        $linkStoryByPlanTips
    ),
    form
    (
        setClass('text-center', 'py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->execution->linkStory),
        formGroup
        (
            set::label($lang->execution->selectStoryPlan),
            set::required(true),
            setClass('text-left'),
            picker
            (
                set::name('plan'),
                set::required(true),
                set::items($allPlans)
            )
        )
    )
);

modal
(
    setID('batchCreateBug'),
    set::title($lang->bug->product),
    setData('size', '500px'),
    inputGroup
    (
        setClass('mt-3'),
        picker
        (
            set::width(300),
            setID('product'),
            set::name('productName'),
            set::items($productNames),
            set::required(true),
            set::onchange('changeBugProduct()')
        ),
        span
        (
            setClass('input-group-btn ml-2'),
            btn
            (
                setClass('primary'),
                setID('batchCreateBugButton'),
                set::url(createLink('bug', 'batchCreate', 'productID=' . key($productNames) . '&branch=&executionID=' . $executionID)),
                set('data-toggle', 'modal'),
                set('data-dismiss', 'modal'),
                set('data-size', 'lg'),
                $lang->bug->batchCreate
            )
        )
    )
);

render();
