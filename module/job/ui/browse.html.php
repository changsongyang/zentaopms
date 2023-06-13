<?php
declare(strict_types=1);
/**
 * The browse view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     job
 * @link        http://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('job', 'create');
$createLink = $this->createLink('job', 'create');
$createItem = array('text' => $lang->job->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($jobList, $config->job->dtable->fieldList, $this->job);

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

jsVar('confirmDelete',    $lang->job->confirmDelete);
jsVar('orderBy',          $orderBy);
jsVar('canBrowseProject', common::hasPriv('job', 'browseProject'));
jsVar('sortLink',         helper::createLink('job', 'browse', "orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

dtable
(
    set::cols($config->job->dtable->fieldList),
    set::data($tableData),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager()),
);

render();
