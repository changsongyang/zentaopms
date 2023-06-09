<?php
declare(strict_types=1);
/**
 * The bug view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featurebar
(
    set::current($type),
    set::linkParam("type=$type"),
    li(searchToggle()),
);

$bugs = initTableData($bugs, $config->my->bug->dtable->fieldList, $this->bug);
$cols = array_values($config->my->bug->dtable->fieldList);
$bugs = array_values($bugs);

$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&productID={$product->id}&type=product"));
}

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn ' . (common::hasPriv('bug', 'batchEdit') ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchEdit')),
    array('text' => $lang->confirm, 'className' => 'batch-btn ajax-btn ' . (common::hasPriv('bug', 'batchConfirm') && $type != 'closedBy' ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchConfirm')),
    array('text' => $lang->close, 'className' => 'batch-btn ajax-btn ' . (common::hasPriv('bug', 'batchClose')   && $type != 'closedBy' ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchClose')),
    array('text' => $lang->bug->assignedTo, 'caret' => 'up', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

dtable
(
    set::cols($cols),
    set::data($bugs),
    set::userMap($users),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
