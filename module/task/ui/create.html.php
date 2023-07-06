<?php
declare(strict_types=1);
/**
 * The create view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tian Shujie<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

/* ====== Preparing and processing page data ====== */

/* zin: Set variables to define picker options for form. */
jsVar('toTaskList', !empty($task->id));
jsVar('blockID', $blockID);
jsVar('executionID', $execution->id);
jsVar('taskID', $taskID);
jsVar('ditto', $lang->task->ditto);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('vision', $config->vision);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);
jsVar('hasProduct', $execution->hasProduct);
jsVar('hideStory', $hideStory);

$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
    if($field && strpos($showFields, $field) === false) $showFields .= ',' . $field;
}
jsVar('showFields', $showFields);

$executionBox = '';
/* Cannot show execution field in kanban. */
if($execution->type != 'kanban' or $this->config->vision == 'lite')
{
    $executionBox = formGroup
    (
        set::width('1/2'),
        set::name('execution'),
        set::label($lang->task->execution),
        set::value($execution->id),
        set::items($executions),
        on::change('loadAll')
    );
}

$kanbanRow = '';
/* The region and lane fields are only showed in kanban. */
if($execution->type == 'kanban')
{
    $kanbanRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::name('region'),
            set::label($lang->kanbancard->region),
            set::value($regionID),
            set::items($regionPairs),
            on::change('loadLanes')
        ),
        formGroup
        (
            set::width('1/2'),
            set::name('lane'),
            set::label($lang->kanbancard->lane),
            set::value($laneID),
            set::items($lanePairs),
        ),
    );
}

/* Set the tip when there is no story. */
if(!empty($execution->hasProduct))
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span
        (
            $lang->task->noticeLinkStory,
        ),
        a
        (
            set::href($this->createLink('execution', 'linkStory', "executionID=$execution->id")),
            setClass('text-primary'),
            $lang->execution->linkStory
        ),
    );
}
else
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span
        (
            $lang->task->noticeLinkStory,
        ),
    );
}
$storyPreviewBtn = span
(
    setClass('input-group-btn flex hidden'),
    set::id('preview'),
    modalTrigger
    (
        to::trigger(
            btn(
                setClass('text-gray'),
                set::icon('eye'),
            ),
        ),
        set::url('')
    ),
);

$teamForm = array();
for($i = 1; $i <= 3; $i ++)
{
    $teamForm[] = h::tr
    (
        h::td
        (
            setClass('team-index'),
            span
            (
                setClass("team-number"),
                $i
            ),
            icon('angle-down')
        ),
        h::td
        (
            set::width('240px'),
            select
            (
                set::name("team[]"),
                set::items($members),
            ),
        ),
        h::td
        (
            set::width('135px'),
            inputControl
            (
                input
                (
                    set::name("teamEstimate[]"),
                    set::placeholder($lang->task->estimateAB),
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20),
            ),
        ),
        h::td
        (
            set::width('100px'),
            setClass('center'),
            btnGroup
            (
                set::items(array(
                    array('icon' => 'plus',  'class' => 'btn btn-link btn-add'),
                    array('icon' => 'trash', 'class' => 'btn btn-link btn-delete'),
                ))
            )
        )
    );
}

$selectStoryRow = '';
if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review')))
{
    $selectStoryRow = formRow(
        set::id('testStoryBox'),
        setClass('hidden'),
    );
}

$afterCreateRow = '';
/* Ct redirect within pop-ups. */
if(!isAjaxRequest('modal'))
{
    $afterRow = formGroup
    (
        set::width('3/4'),
        set::label($lang->task->afterSubmit),
        radioList
        (
            set::name('after'),
            set::value(!empty($task->id) ? 'toTaskList' : 'continueAdding'),
            set::items($config->task->afterOptions),
            set::inline(true)
        ),
    );
}

/* ====== Define the page structure with zin widgets ====== */

formPanel
(
    set::id('taskCreateForm'),
    set::title($lang->task->create),
    formRow
    (
        $executionBox,
        formGroup
        (
            set::width('1/2'),
            set::name('module'),
            set::label($lang->task->module),
            set::value($task->module),
            set::items($modulePairs)
        )
    ),
    $kanbanRow,
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('type'),
            set::label($lang->task->type),
            set::control('select'),
            set::items($lang->task->typeList),
            set::value($task->type),
            on::change('typeChange')
        ),
        formGroup
        (
            set::width('1/4'),
            set::id('selectTestStoryBox'),
            setClass('hidden items-center'),
            checkbox(
                set::id('selectTestStory'),
                set::name('selectTestStory'),
                set::value(1),
                set::text($lang->task->selectTestStory),
                set::rootClass('ml-4'),
                on::change('toggleSelectTestStory'),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->task->assignTo),
            setClass('assignedToBox'),
            select
            (
                set::id('assignedTo'),
                set::name('assignedTo[]'),
                set::value($task->assignedTo),
                set::items($members),
            ),
            btn
            (
                set
                (
                    array
                    (
                        'class' => 'btn primary-pale hidden add-team mr-3',
                        'data-toggle' => 'modal',
                        'url' => '#modalTeam',
                        'icon' => 'plus',
                    ),
                ),
                $lang->task->addMember,
            ),
            div
            (
                setClass('assignedToList'),
            ),
        ),
        formGroup
        (
            set::width('1/10'),
            set::id('multipleBox'),
            setClass('items-center'),
            checkbox(
                set::name('multiple'),
                set::text($lang->task->multiple),
                set::rootClass('ml-4'),
                on::change('toggleTeam'),
            )
        ),
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::control('hidden'),
            set::name('teamMember'),
        )
    ),
    formRow
    (
        setClass($hideStory ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->task->story),
            setClass(empty($stories) ? 'hidden' : ''),
            inputGroup
            (
                select
                (
                    set::id('story'),
                    set::name('story'),
                    set::value($task->story),
                    set::items(array_filter($stories)),
                    on::change('setStoryRelated'),
                ),
                $storyPreviewBtn,
            )
        ),
        formGroup
        (
            set::label($lang->task->story),
            setClass(!empty($stories) ? 'hidden' : ''),
            div
            (
                setClass('empty-story-tip input-control has-prefix has-suffix'),
                $storyEmptyPreTip,
                input(
                    set::name(''),
                    set('readonly'),
                    set('onfocus', 'this.blur()'),
                ),
                span
                (
                    setClass('input-control-suffix'),
                    btn(
                        setClass('text-gray'),
                        set::id('refreshStories'),
                        set::icon('refresh'),
                        on::click('loadExecutionStories'),
                    )
                ),
            ),
        ),
    ),
    $selectStoryRow,
    formRow
    (
        formGroup
        (
            set::width('3/4'),
            set::label($lang->task->name),
            set::name('name'),
            set::value($task->name),
            set::strong(true),
        ),
        formGroup
        (
            set::width('1/4'),
            setClass('no-background'),
            inputGroup
            (
                $lang->task->pri,
                select
                (
                    set::name('pri'),
                    set::items(array_filter($lang->task->priList)),
                ),
                $lang->task->estimate,
                inputControl
                (
                    input(set::name('estimate')),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20),
                ),
            ),
        ),
    ),
    formGroup
    (
        set::label($lang->task->desc),
        editor
        (
            set::name('desc'),
            set::rows('5'),
        )
    ),
    formGroup
    (
        set::label($lang->story->files),
        upload()
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->task->datePlan),
        inputGroup
        (
            input
            (
                set::control('date'),
                set::name('estStarted'),
                set::value($task->estStarted),
                set::placeholder($lang->task->estStarted),
            ),
            $lang->task->to,
            input
            (
                set::control('date'),
                set::name('deadline'),
                set::value($task->deadline),
                set::placeholder($lang->task->deadline),
            ),
        )
    ),
    formGroup
    (
        set::label($lang->product->mailto),
        set::name('mailto[]'),
        set::items($users),
    ),
    $afterRow,
    modalTrigger
    (
        modal
        (
            set::id('modalTeam'),
            set::title($lang->task->teamMember),
            h::table
            (
                set::id('teamTable'),
                h::tr
                (
                    h::td
                    (
                        width('90px'),
                        $lang->task->mode
                    ),
                    h::td
                    (
                        select
                        (
                            set::name("mode"),
                            set::value("linear"),
                            set::items($lang->task->modeList),
                            set::required(true),
                        ),
                    )
                ),
                setClass('table table-form'),
                $teamForm,
                h::tr
                (
                    h::td
                    (
                        setClass('team-saveBtn'),
                        set(array('colspan' => 4)),
                        btn
                        (
                            setClass('toolbar-item btn primary'),
                            $lang->save
                        )
                    )
                )
            )
        )
    )
);


/* ====== Render page ====== */
render();
