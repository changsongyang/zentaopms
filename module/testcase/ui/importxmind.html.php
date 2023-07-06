<?php
declare(strict_types=1);
/**
 * The importxmind view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('xmindSettingTip', $lang->testcase->xmindSettingTip);

set::title($lang->testcase->importXmind);

form
(
    formGroup
    (
        set::label($lang->testcase->importFile),
        upload()
    ),
    formRow
    (
        set::class('border-b border-b-1'),
        span
        (
            set::class('bg-lighter font-black px-3 py-1'),
            $lang->testcase->xmindImportSetting,
            icon
            (
                'help',
                set::class('text-gray pl-1'),
                set('data-toggle', 'tooltip'),
                set::id('xmindSettingTip'),
            )
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
    set::submitBtnText($lang->import)
);

render('modalDialog');

