<?php
declare(strict_types=1);
/**
 * The login view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

if(empty($config->notMd5Pwd)) h::import($config->webRoot . 'js/md5.js', 'js');

$resetLink     = (isset($this->config->resetPWDByMail) and $this->config->resetPWDByMail) ? inlink('forgetPassword') : inlink('reset');
$zentaoDirName = basename($this->app->getBasePath());
$clientLang    = $app->getClientLang();
$langItems     = array();
foreach($config->langs as $key => $value) $langItems[] = array('text' => $value, 'data-on' => 'click', 'data-call' => 'switchLang', 'data-params' => $key);

$pluginTips      = '';
$expiredPlugins  = implode('、', $plugins['expired']);
$expiringPlugins = implode('、', $plugins['expiring']);
$expiredTips     = sprintf($lang->misc->expiredPluginTips, $expiredPlugins);
$expiringTips    = sprintf($lang->misc->expiringPluginTips, $expiringPlugins);
if($expiredPlugins)  $pluginTips = $expiredTips;
if($expiringPlugins) $pluginTips = $expiringTips;
if($expiredPlugins and $expiringPlugins) $pluginTips = $expiredTips . $pluginTips;
$pluginTotal = count($plugins['expired']) + count($plugins['expiring']);
$expiredCountTips = sprintf($lang->misc->expiredCountTips, $pluginTips, $pluginTotal);

$demoUserItems = array();
if(!empty($this->config->global->showDemoUsers))
{
    $demoPassword = '123456';
    $md5Password  = md5('123456');
    $demoUsers    = 'productManager,projectManager,dev1,dev2,dev3,tester1,tester2,tester3,testManager';
    $demoUsers    = $this->dao->select('account,password,realname')->from(TABLE_USER)->where('account')->in($demoUsers)->andWhere('deleted')->eq(0)->andWhere('password')->eq($md5Password)->fetchAll('account');

    $link  = inlink('login');
    $link .= strpos($link, '?') !== false ? '&' : '?';
    foreach($demoUsers as $demoAccount => $demoUser)
    {
        if($demoUser->password != $md5Password) continue;
        $demoUserItems[] = a(set::href($link . "account={$demoAccount}&password=" . md5($md5Password . $this->session->rand)), $demoUser->realname);
    }
}

if($unsafeSites and !empty($unsafeSites[$zentaoDirName]))
{
    $paths     = array();
    $databases = array();
    $isXampp   = false;
    foreach($unsafeSites as $webRoot => $site)
    {
        $path = $site['path'];
        if(strpos($path, 'xampp') !== false) $isXampp = true;

        $paths[]     = $site['path'];
        $databases[] = $site['database'];
    }

    $process4Safe = $isXampp ? $lang->user->process4DB : $lang->user->process4DIR;
    $process4Safe = sprintf($process4Safe, join(' ', $isXampp ? $databases : $paths));
    jsVar('process4Safe', $process4Safe);
}
jsVar('loginTimeoutTip', $lang->user->error->loginTimeoutTip);

set::zui(true);
div
(
    setID('main'),
    setClass('no-padding'),
    div
    (
        setID('login'),
        div
        (
            setID('loginPanel'),
            div
            (
                setClass('header'),
                h2(setClass('font-bold'), sprintf($lang->welcome, $app->company->name)),
                dropdown
                (
                    setClass('actions btn'),
                    to('trigger', btn($config->langs[$clientLang])),
                    to('title', 'Change Language/更换语言/更換語言'),
                    set::items($langItems),
                    set::menuClass('langsDropMenu'),
                    set::staticMenu(true),
                    set::trigger('hover'),
                )
            ),
            div
            (
                setClass('flex items-start loginBody'),
                cell
                (
                    set::width('1/3'),
                    setID('logo-box'),
                    h::img(set::src($config->webRoot . 'theme/default/images/main/' . $this->lang->logoImg)),
                ),
                cell
                (
                    set::width('2/3'),
                    $loginExpired ? p(setClass('text-danger loginExpired'), $lang->user->loginExpired) : null,
                    form
                    (
                        on::click('#submit', 'safeSubmit'),
                        formGroup
                        (
                            set::label($lang->user->account),
                            set::strong(true),
                            set::control(array('type' => 'text', 'name' => 'account')),
                        ),
                        formGroup
                        (
                            set::label($lang->user->password),
                            set::strong(true),
                            set::control(array('type' => 'password', 'name' => 'password')),
                        ),
                        !empty($this->config->safe->loginCaptcha) ? formGroup
                        (
                            set::label($lang->user->captcha),
                            div
                            (
                                setClass('captchaBox'),
                                inputGroup
                                (
                                    input(set::name('captcha')),
                                    span(setClass('input-group-addon'), h::img(set::src($this->createLink('misc', 'captcha', "sessionVar=captcha"), on::click('refreshCaptcha(e.target)')))),
                                )
                            )
                        ) : null,
                        formGroup
                        (
                            set::label(''),
                            set::control(array('type' => 'checkList', 'items' => $lang->user->keepLogin, 'name' => 'keepLogin', 'value' => $keepLogin)),
                        ),
                        formHidden('referer', $referer),
                        set::actions(array
                        (
                            array('text' => $lang->login, 'id' => 'submit', 'class' => 'primary'),
                            array('text' => $lang->user->resetPassword, 'class' => 'resetPassword', 'url' => $resetLink),
                        )),
                    ),
                ),
            ),
            (count($plugins['expired']) > 0 || count($plugins['expiring']) > 0) ? div
            (
                setClass('table-row-extension'),
                div
                (
                    setID('notice'),
                    setClass('alert secondary'),
                    div(setClass('content'), icon(setClass('text-secondary'), 'exclamation-sign'), $expiredCountTips),
                )
            ) : null,
            empty($demoUsers) ? null : div
            (
                span($lang->user->loginWithDemoUser),
                $demoUserItems,
            ),
        ),
        div
        (
            setID('info'),
            div
            (
                setID('poweredby'),
                ($unsafeSites && !empty($unsafeSites[$zentaoDirName])) ? div(a(setClass('showNotice'), set::href('###'), on::click('showNotice'), $lang->user->notice4Safe)) : null,
            ),
        )
    )
);

render('pagebase');
