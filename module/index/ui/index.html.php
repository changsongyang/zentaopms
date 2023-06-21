<?php
namespace zin;

if(trim($config->visions, ',') == 'lite')
{
    $version     = $config->liteVersion;
    $versionName = $lang->liteName . $config->liteVersion;
}
else
{
    $version     = $config->version;
    $versionName = $lang->pmsName . $config->version;
}

jsVar('vision',        $config->vision);
jsVar('navGroup',      $lang->navGroup);
jsVar('appsItems',     commonModel::getMainNavList($app->rawModule));
jsVar('defaultOpen',   (isset($open) and !empty($open)) ? $open : '');
jsVar('manualText',    $lang->manual);
jsVar('manualUrl',     ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme']);
jsVar('searchObjectList', array_keys($lang->searchObjects));
jsVar('lang',          array_merge(['search' => $lang->index->search, 'searchAB' => $lang->searchAB], (array)$lang->index->app));

set::zui(true);
set::bodyClass($this->cookie->hideMenu ? 'hide-menu' : 'show-menu');

/* The menu fixed on left */
div
(
    setID('menu'),
    div
    (
        setID('menuNav'),
        ul(set::class('nav'), setID('menuMainNav')),
        ul
        (
            set::class('nav'),
            setID('menuMoreNav'),
            li(set::class('divider')),
            li
            (
                a
                (
                    set::title($lang->more),
                    set::href('#menuMoreList'),
                    icon('more-circle'),
                    span(set::class('text'), $lang->more),
                    toggle('dropdown')
                ),
                ul(set::class('menu dropdown-menu menu-popup'), setID('menuMoreList'))
            )
        ),
    ),
    div
    (
        setID('menuFooter'),
        ul
        (
            set::class('nav'),
            li
            (
                setID('menuToggleMenu'),
                a
                (
                    set::class('menu-toggle justify-center cursor-pointer'),
                    toggle::tooltip(['placement' => 'right', 'collapse-text' => $lang->collapseMenu, 'unfold-text' => $lang->unfoldMenu]),
                    icon('menu-collapse icon-sm')
                )
            )
        )
    )
);

div
(
    setID('appsBar'),
    ul
    (
        setID('appTabs'),
        set::class('nav')
    ),
    toolbar
    (
        setID('appsToolbar'),
        item
        (
            set::class('ghost btn-zentao'),
            set::icon('zentao text-2xl'),
            set::url('$lang->website'),
            set::target('_blank'),
            set::hint($version),
            set::text($versionName)
        )
    )
);

div
(
    setID('apps'),
);

render();
