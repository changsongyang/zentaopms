<?php
declare(strict_types=1);
/**
 * The exportxmid view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->testcase->exportXmind);

form
(
    set::target('_self'),
    on::submit('setDownloading'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->product),
        set::name('product'),
        set::value($productName),
        set::disabled(true)

    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->module),
        set::name('imodule'),
        set::control(array('type' => 'select', 'items' => $moduleOptionMenu)),
    ),
    formRow
    (
        set::class('border-b border-b-1'),
        span
        (
            set::class('bg-lighter font-black px-3 py-1'),
            $lang->testcase->xmindExportSetting
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingModule),
            set::name('module'),
            set::value($settings['module']),
            set::placeholder('M'),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingScene),
            set::name('scene'),
            set::value($settings['scene']),
            set::placeholder('S')
        ),
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->settingCase),
        set::name('case'),
        set::value($settings['case']),
        set::placeholder('C')
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingPri),
            set::name('pri'),
            set::value($settings['pri']),
            set::placeholder('P'),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingGroup),
            set::name('group'),
            set::value($settings['group']),
            set::placeholder('G')
        )
    ),
    set::actions(array('submit')),
    set::submitBtnText($lang->export),
);

js
(
    <<<JAVASCRIPT
    function setDownloading()
    {
        if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.
    
        $.cookie.set('downloading', 0);
    
        time = setInterval(function()
        {
            if($.cookie.get('downloading') == 1)
            {
                $('.modal').trigger('to-hide.modal.zui');
    
                $.cookie.set('downloading', null);
    
                clearInterval(time);
            }
        }, 300);
    
        return true;
    }
    JAVASCRIPT
);

render('modalDialog');
