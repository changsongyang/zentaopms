<?php
declare(strict_types=1);
/**
 * The todo view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('changeDateLabel', $lang->todo->changeDate);

featureBar
(
    set::current($type),
    set::linkParams('date={key}'),
    inputGroup
    (
        set::class('ml-4'),
        input
        (
            set::name('date'),
            set::type('date'),
            set::value($date),
        )
    )
);

$todos = initTableData($todos, $config->my->todo->dtable->fieldList, $this->todo);

$cols = array_values($config->my->todo->dtable->fieldList);
$data = array_values($todos);
toolbar
(
    item
    (
        set(array(
            'text'  => $lang->todo->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('todo', 'export', "userID={$user->id}&orderBy=$orderBy"),
            'data-toggle' => 'modal'
        ))
    ),
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('todo', 'create')),
            $lang->todo->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array
            (
                array('text' => $lang->todo->create, 'url' => helper::createLink('todo', 'create'), 'data-toggle' => 'modal'),
                array('text' => $lang->todo->batchCreate, 'url' => helper::createLink('todo', 'batchCreate'), 'data-toggle' => 'modal')
            )),
            set::placement('bottom-end')
        )
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('todo', 'batchEdit', "from=myTodo&type=$type&userID={$user->id}&status=$status")),
    array('text' => $lang->todo->finish, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('todo', 'batchFinish')),
    array('text' => $lang->todo->close, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('todo', 'batchClose'))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

$defaultSummary = sprintf($lang->todo->summary, count($todos), $waitCount, $doingCount);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable(true),
    set::defaultSummary(array('html' => $defaultSummary)),
    set::checkedSummary($lang->todo->checkedSummary),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::footer(array('checkbox', 'toolbar', jsRaw('window.generateHtml'), 'checkedInfo', 'flex', 'pager')),
);

render();
