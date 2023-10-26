<?php
declare(strict_types=1);
/**
* The projectdoc block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$active  = isset($params['active']) ? $params['active'] : key($projects);
$project = null;

$items = array();
foreach($projects as $projectItem)
{
    $params  = helper::safe64Encode("module={$block->module}&active={$projectItem->id}");
    $items[] = array
    (
        'id'        => $projectItem->id,
        'text'      => $projectItem->name,
        'url'       => createLink('project', 'index', "projectID={$projectItem->id}"),
        'activeUrl' => createLink('block', 'printBlock', "blockID={$block->id}&params={$params}")
    );
    if($projectItem->id == $active) $project = $projectItem;
}

statisticBlock
(
    to::titleSuffix
    (
        dropdown
        (
            a
            (
                setClass('text-gray ml-4'),
                $type == 'involved' ? $lang->project->involved : $lang->project->all,
                span(setClass('caret align-middle ml-1'))
            ),
            set::items(array(
                array('text' => $lang->project->involved, 'url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("type=involved")), 'data-load' => 'target', 'data-selector' => "#doc-projectdoc-{$block->id}", 'data-partial' => true),
                array('text' => $lang->project->all, 'url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("type=all")), 'data-load' => 'target', 'data-selector' => "#doc-projectdoc-{$block->id}", 'data-partial' => true))
            )
        ),
    ),
    set::block($block),
    set::active($active),
    set::moreLink(createLink('doc', 'projectspace')),
    set::items($items),
    set::className('projectdoc-block'),
    dtable
    (
        set::height(318),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::cols(array_values($config->block->doc->dtable->fieldList)),
        set::data(array_values($docGroup[$project->id])),
        set::userMap($users),
    )
);

render();
