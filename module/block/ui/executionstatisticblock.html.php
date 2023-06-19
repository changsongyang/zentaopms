<?php
declare(strict_types=1);
/**
* The execution statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 获取区块左侧的执行列表.
 * Get execution tabs on the left side.
 *
 * @param  array    $executions
 * @param  string   $blockNavCode
 * @access public
 * @return array
 */
function getExecutionTabs($executions, $blockNavCode): array
{
    $navTabs  = array();
    $selected = key($executions);
    foreach($executions as $execution)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item' . ($execution->id == $selected ? ' active' : '')),
            a
            (
                set('class', 'ellipsis'),
                set('data-toggle', 'tab'),
                set('href', "#tab3{$blockNavCode}Content{$execution->id}"),
                $execution->name

            ),
            a
            (
                set('class', 'link flex-1 text-right hidden'),
                set('href', helper::createLink('execution', 'task', "executionID={$execution->id}")),
                icon
                (
                    set('class', 'rotate-90 text-primary'),
                    'export'
                )
            )
        );
    }
    return $navTabs;
}

/**
 * 获取区块右侧显示的执行信息.
 * Get execution statistical information.
 *
 * @param  object   $executions
 * @param  string   $blockNavID
 * @access public
 * @return array
 */
function getExecutionInfo($executions, $blockNavID): array
{
    global $lang;

    $selected = key($executions);
    $tabItems = array();
    foreach($executions as $execution)
    {
        $tabItems[] = div
        (
            set('class', 'tab-pane' . ($execution->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$execution->id}"),
            div()
        );
    }
    return $tabItems;
}

$blockNavCode = 'nav-' . uniqid();
panel
(
    set('class', 'executionstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            span($block->title),
        )
    ),
    div
    (
        set('class', 'flex h-full of-hidden'),
        cell
        (
            set('width', '25%'),
            set('class', 'bg-secondary-pale of-y-auto of-x-hidden'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked'),
                getExecutionTabs($executions, $blockNavCode)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '75%'),
            getExecutionInfo($executions, $blockNavCode)
        )
    )
);

render('|fragment');
