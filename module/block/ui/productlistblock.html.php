<?php
declare(strict_types=1);
/**
* The product list block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$cols = array
(
    array
    (
        'name'     => 'name',
        'title'    => '产品名称',
        'width'    => '200',
        'type'     => 'link',
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'POName',
        'title'    => '负责人',
        'width'    => 200,
        'type'     => 'avatarBtn',
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '未关闭反馈',
        'width'    => 100,
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '激活需求',
        'width'    => 100,
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '需求完成率',
        'width'    => 100,
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '计划',
        'width'    => 100,
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '激活BUG',
        'width'    => 100,
        'sortType' => 1,
    ),
    array
    (
        'name'     => 'TODO:zu1',
        'title'    => '发布',
        'width'    => 100,
        'sortType' => 1,
    ),
);

$data = array();

foreach($productStats as $product)
{
    if(!empty($product->PO))
    {
        $product->POName    = zget($users, $product->PO);
        $product->POAvatar  = $usersAvatar[$product->PO];
        $product->POAccount = $product->PO;
    }
}

$table = array();

panel
(
    dtable
    (
        setClass('shadow rounded'),
        set::cols($cols),
        set::data($productStats)
    )
);

render();
