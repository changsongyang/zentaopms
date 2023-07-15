<?php
declare(strict_types=1);
/**
 * The manageLine view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

$lineMenuList = null;
$formRowList  = null;
$formRowList[] = formRow
(
    cell
    (
        setClass('flex flex-1'),
        formGroup
        (
            set::width($config->systemMode == 'ALM' ? '1/2' : 'full'),
            set::label($lang->product->lineName),
            set::labelClass('font-bold'),
        ),
        $config->systemMode == 'ALM' ? formGroup
        (
            set::width('1/2'),
            set::class('ml-4'),
            set::label($lang->product->program),
            set::labelClass('font-bold required'),
        ) : null,
    ),
    cell
    (
        set::width('100px'),
        formGroup
        (
            span(setClass('form-label font-bold'), set::style(array('justify-content' =>'flex-start')), $lang->actions),
        ),
    )
);
foreach($lines as $line)
{
    $lineMenuList[] = div
    (
        set::class('ml-4 line-item flex items-center'),
        span($line->name),
        btn
        (
            icon('trash'),
            set::size('sm'),
            setClass('ghost text-gray ajax-submit'),
            set::url(createLink('product', 'ajaxDeleteLine', "lineID={$line->id}")),
            set('data-confirm', $lang->product->confirmDeleteLine),
        )
    );

    $formRowList[] = formRow
    (
        cell
        (
            setClass('flex flex-1'),
            formGroup
            (
                set::width($config->systemMode == 'ALM' ? '1/2' : 'full'),
                set::name("modules[id$line->id]"),
                set::value($line->name),
            ),
            $config->systemMode == 'ALM' ? formGroup
            (
                setID("programs_id{$line->id}"),
                set::width('1/2'),
                set::class('ml-4'),
                set::name("programs[id$line->id]"),
                set::items($programs),
                set::value($line->root),
            ) : null,
        ),
        cell
        (
            set::width('100px'),
            formGroup
            (
                setClass('ml-2 pl-2 flex self-center'),
            )
        )
    );
}

for($i = 0; $i <= 5; $i ++)
{
    $formRowList[] = formRow
    (
        set::class('line-row-add'),
        cell
        (
            setClass('flex flex-1'),
            formGroup
            (
                set::width($config->systemMode == 'ALM' ? '1/2' : 'full'),
                set::name("modules[$i]"),
            ),
            $config->systemMode == 'ALM' ? formGroup
            (
                set::width('1/2'),
                setID("programs_{$i}"),
                set::class('ml-4'),
                set::name("programs[$i]"),
                set::items($programs),
            ) : null,
        ),
        cell
        (
            set::width('100px'),
            formGroup
            (
                setClass('ml-2 pl-2 flex self-center'),
                btn
                (
                    setClass('btn btn-link text-gray addLine'),
                    icon('plus'),
                    on::click('addNewLine')
                ),
                btn
                (
                    setClass('btn btn-link text-gray removeLine'),
                    icon('trash'),
                    on::click('removeLine'),
                ),
            )
        )
    );
}

jsVar('+index', $i);

modalHeader(set::title($lang->product->manageLine), set::titleClass('text-lg font-bold'));
div
(
    set::class('flex'),
    cell
    (
        set::width('1/3'),
        set::class('lineTree mr-1'),
        h2(setClass('article-h2'), $lang->product->line),
        div
        (
            setClass('mt-4 mr-4 pl-5 pt-2 pt-2 pb-2'),
            set::style(array('background' => 'var(--color-gray-100)')),
            $lineMenuList,
        )
    ),
    cell
    (
        set::width('2/3'),
        form
        (
            set::submitBtnText($lang->save),
            set::actionsClass('justify-start'),
            set::class('border-b-0'),
            $formRowList
        )
    )
);

/* ====== Render page ====== */
render();
