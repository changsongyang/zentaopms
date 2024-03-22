<?php
declare(strict_types=1);

namespace zin;

formPanel
(
    set::title($lang->ai->assistants->edit),
    set::id('assistant-form'),
    set::actions(
        array(
            array('text' => $lang->save, 'class' => 'btn primary', 'id' => 'save-assistant-button', 'btnType' => 'submit'),
            'cancel'
        )
    ),
    formGroup
    (
        set::label($lang->ai->assistants->name),
        set::width('1/2'),
        set::required(true),
        input
        (
            set::name('name'),
            set('maxlength', 20),
            set::value($assistant->name),
        )
    ),
    formGroup
    (
        set::label($lang->ai->models->common),
        set::width('1/2'),
        set::required(true),
        select
        (
            set::name('modelId'),
            set::items($models),
            set::value($assistant->modelId),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->ai->assistants->desc),
        textarea
        (
            set::name('desc'),
            set::rows(3),
            set::placeholder($lang->ai->assistants->descPlaceholder),
            set::value($assistant->desc)
        )
    ),
    formGroup
    (
        set::label($lang->ai->assistants->systemMessage),
        textarea
        (
            set::name('systemMessage'),
            set::rows(3),
            set::placeholder($lang->ai->assistants->systemMessagePlaceholder),
            set::value($assistant->systemMessage)
        )
    ),
    formGroup
    (
        set::label($lang->ai->assistants->greetings),
        set::required(true),
        textarea
        (
            set::name('greetings'),
            set::rows(3),
            set::placeholder($lang->ai->assistants->greetingsPlaceholder),
            set::value($assistant->greetings)
        )
    )
);
