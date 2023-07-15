<?php
declare(strict_types=1);
/**
 * The edit view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

import('/js/md5.js', 'js');

jsVar('passwordStrengthList', $lang->user->passwordStrengthList);

$contacts = array();
if(!empty($config->user->contactField))
{
    foreach(explode(',', $config->user->contactField) as $i => $field)
    {
        if($i % 2 == 0) $contactGroup = array();

        $contactGroup[] = formGroup
        (
            set::width('1/2'),
            set::label($lang->user->{$field}),
            set::name($field),
            set::value($user->{$field}),
        );

        if($i % 2 == 1) $contacts[] = formRow($contactGroup);
    }
}

formPanel
(
    to::heading
    (
        div
        (
            setClass('flex items-center gap-2'),
            $lang->user->edit,
            entityLabel
            (
                set::level(1),
                set::text($user->realname),
            ),
        ),
    ),
    set::formClass('border-0'),
    on::click('button[type="submit"]', 'computePassword'),
    on::change('input[name^=visions]', 'changeVision'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->realname),
            set::name('realname'),
            set::value($user->realname)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->role),
            set::control('picker'),
            set::name('role'),
            set::items($lang->user->roleList),
            set::value($user->role),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->dept),
            set::control('picker'),
            set::name('dept'),
            set::items($depts),
            set::value($user->dept)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->join),
            set::control('date'),
            set::name('join'),
            set::value($user->join)
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->group),
            picker
            (
                set::name('group[]'),
                set::items($groups),
                set::value($userGroups),
                set::multiple(true),
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->company),
            inputGroup
            (
                picker
                (
                    set::name('company'),
                    set::items($companies),
                    set::value($user->company)
                ),
                input
                (
                    set::name('newCompany'),
                    set::value(''),
                    setClass('hidden'),
                ),
                checkbox
                (
                    on::change('toggleNew'),
                    set::id('new'),
                    set::name('new'),
                    set::value(0),
                    set::text($lang->company->create),
                    set::rootClass('btn'),
                    width('96px'),
                ),
            ),
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->type),
            radioList
            (
                on::change('changeType'),
                set::inline(true),
                set::name('type'),
                set::items($lang->user->typeList),
                set::value($user->type)
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->gender),
            radioList
            (
                set::inline(true),
                set::name('gender'),
                set::items($lang->user->genderList),
                set::value($user->gender)
            ),
        ),
    ),
    formRow
    (
        setClass('border-b border-b-1'),
        div
        (
            setClass('bg-lighter font-black px-3 py-1'),
            $lang->user->accountInfo
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->account),
            set::name('account'),
            set::value($user->account)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->email),
            set::name('email'),
            set::value($user->email),
        )
    ),
    formRow
    (
        formGroup
        (
            on::change('password1Change'),
            set::width('1/2'),
            set::label($lang->user->password),
            inputGroup
            (
                input
                (
                    on::keyup('checkPassword'),
                    set::id('password1'),
                    set::name('password1'),
                    set::value(''),
                    set::placeholder(zget($lang->user->placeholder->passwordStrength, $config->safe->mode, '')),
                ),
                span
                (
                    setClass('input-group-addon hidden'),
                    set::id('passwordStrength'),
                ),
            ),
        ),
        formGroup
        (
            on::change('password2Change'),
            set::width('1/2'),
            set::label($lang->user->password2),
            set::name('password2'),
            set::value(''),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->commiter),
            set::name('commiter'),
            set::value($user->commiter),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->visions),
            checkList
            (
                set::name('visions[]'),
                set::items($visionList),
                set::value($user->visions),
                set::inline(true),
            ),
        )
    ),
    formRow
    (
        setClass('border-b border-b-1'),
        div
        (
            setClass('bg-lighter font-black px-3 py-1'),
            $lang->user->contactInfo
        ),
    ),
    $contacts,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->address),
            set::name('address'),
            set::value($user->address),
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->zipcode),
            set::name('zipcode'),
            set::value($user->zipcode),
        )
    ),
    formRow
    (
        setClass('border-b border-b-1'),
        div
        (
            setClass('bg-lighter font-black px-3 py-1'),
            $lang->user->verify
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->verifyPassword),
            set::control('password'),
            set::name('verifyPassword'),
            set::value(''),
            set::placeholder($lang->user->placeholder->verify),
        ),
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::name('passwordLength'),
            set::value(0),
        ),
        formGroup
        (
            set::name('verifyRand'),
            set::value($rand),
        ),
    ),
);

render();

