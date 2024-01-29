<?php
declare(strict_types=1);
/**
 * The create view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$fields    = useFields('execution.create');
$typeField = $isStage ? 'attribute' : 'lifetime';
$fields->fullModeOrders('productsBox,PO,QD,PM,RD,team,teams,teamMembers[],desc');
if(!$isStage || empty($config->setPercent))
{
    $fields->remove('percent');
    $fields->field('days')->width('1/2');
}
if(empty($config->setCode))  $fields->remove('code');
if(!empty($config->setCode)) $fields->moveBefore($typeField, 'name');
if(!empty($project->model) && $project->model == 'kanban')
{
    $fields->remove($typeField);
    if(empty($config->setCode)) $fields->field('name')->wrapAfter();
}
if(!empty($project->model) && $project->model == 'agileplus')
{
    if(!empty($config->setCode))
    {
        $fields->field('code')->width('1/4');
        $fields->field($typeField)->width('1/4');
        $fields->moveAfter($typeField, 'code');
    }
    if($isKanban)
    {
        if(empty($config->setCode))  $fields->field('name')->width('full');
        if(!empty($config->setCode)) $fields->field('code')->width('1/2');
    }
}

jsVar('+projectID', $projectID);
jsVar('copyProjectID', $copyProjectID);
jsVar('weekend', $config->execution->weekend);
jsVar('isStage', $isStage);
jsVar('copyExecutionID', $copyExecutionID);
jsVar('executionID', isset($executionID) ? $executionID : 0);

$showExecutionExec = !empty($from) and ($from == 'execution' || $from == 'doc');

$handleBeginEndChange = jsCallback()
    ->const('weekend', $config->execution->weekend)
    ->do(<<<'JS'
        const $picker   = $this.closest('.date-range-picker');
        const begin     = $picker.find('[name=begin]').val();
        const end       = $picker.find('[name=end]').val();
        if(!zui.isValidDate(begin) || !zui.isValidDate(end)) return;
        const $days     = $element.find('[name=days]');
        const beginDate = zui.createDate(begin);
        const endDate   = zui.createDate(end);
        const totalDays = Math.floor((endDate.getTime() - beginDate.getTime()) / zui.TIME_DAY);
        if(totalDays <= 0) return $days.val(0);
        let workDays  = 0;
        for(i = 0; i < totalDays; i++)
        {
            const date = new Date(beginDate.getTime());
            date.setDate(date.getDate() + i);

            if((weekend == 2 && date.getDay() == 6) || date.getDay() == 0) continue;
            workDays++;
        }
        $days.val(workDays);
    JS);

formGridPanel
(
    set::title($showExecutionExec ? $lang->execution->createExec : $lang->execution->create),
    to::headingActions
    (
        btn
        (
            set::icon('copy'),
            setClass('primary-ghost size-md'),
            toggle::modal(array('target' => '#copyExecutionModal', 'destoryOnHide' => true)),
            $lang->execution->copy
        ),
        divider(setClass('h-4 mr-4 ml-2 self-center'))
    ),
    on::change('[name=project]', 'refreshPage'),
    on::change('[name=type]', 'setType'),
    on::change('[name=begin],[name=end]', $handleBeginEndChange),
    on::change('[name=teams]', 'loadMembers'),
    on::change('#copyTeam', 'toggleCopyTeam'),
    set::fields($fields)
);

modalTrigger
(
    modal
    (
        set::id('copyExecutionModal'),
        set::footerClass('justify-center'),
        to::header
        (
            span
            (
                h4
                (
                    set::className('copy-title'),
                    $lang->execution->copyTitle
                )
            ),
            picker
            (
                set::className('pickerProject'),
                set::name('project'),
                set::items($copyProjects),
                set::value($projectID),
                set::required(true),
                on::change('loadProjectExecutions')
            )
        ),
        to::footer
        (
            btn
            (
                setClass('primary btn-wide hidden confirmBtn'),
                set::text($lang->execution->copy),
                on::click('setCopyExecution')
            )
        ),
        div
        (
            set::id('copyExecutions'),
            setClass('flex items-center flex-wrap')
        )
    )
);
