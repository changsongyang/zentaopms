<?php
declare(strict_types=1);
/**
 * The model file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 * @property  cneModel $cne
 */
class systemModel extends model
{
    /**
     * Construct function: load setting model.
     *
     * @access public
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
        $this->loadModel('cne');
    }

    /**
     * 获取自定义的域名设置。
     * Get customized domain settings.
     *
     * @access public
     * @return object
     */
    public function getDomainSettings()
    {
        $settings = new stdclass;
        $settings->customDomain = $this->setting->getItem('owner=system&module=common&section=domain&key=customDomain');
        $settings->https        = $this->setting->getItem('owner=system&module=common&section=domain&key=https');
        $settings->certPem      = '';
        $settings->certKey      = '';

        return $settings;
    }

    /**
     * 保存自定义的域名设置。
     * Save customized domain settings.
     *
     * @param  object $setting
     * @access public
     * @return void
     */
    public function saveDomainSettings(object $settings)
    {
        $this->dao->from('system')->data($settings)
            ->check('customDomain', 'notempty')
            ->checkIf($settings->https == 'true', 'certPem', 'notempty')
            ->checkIf($settings->https == 'true', 'certKey', 'notempty');
        if(dao::isError()) return;

        if(!validater::checkREG($settings->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            dao::$errors[] = $this->lang->system->errors->invalidDomain;
            return;
        }

        /* Upload Certificate to CNE. */
        if($settings->https == 'true')
        {
            $cert = new stdclass;
            $cert->name            = 'tls-' . str_replace('.', '-', $settings->customDomain);
            $cert->certificate_pem = $settings->certPem;
            $cert->private_key_pem = $settings->certKey;
            $certResult = $this->loadModel('cne')->uploadCert($cert);
            if($certResult->code != 200)
            {
                dao::$errors[] = $certResult->message;
                return;
            }
        }

        $oldSettings = $this->getDomainSettings();
        if($settings->customDomain == $oldSettings->customDomain)  dao::$errors[] = $this->lang->system->errors->newDomainIsSameWithOld;
        if(stripos($settings->customDomain, 'haogs.cn') !== false) dao::$errors[] = $this->lang->system->errors->forbiddenOriginalDomain;
        if(dao::isError()) return false;

        $expiredDomain   = $this->setting->getItem('owner=system&module=common&section=domain&key=expiredDomain');
        $expiredDomain   = empty($expiredDomain ) ? array(getenv('APP_DOMAIN')) : json_decode($expiredDomain, true);
        $expiredDomain[] = zget($settings, 'customDomain', '');
        $this->setting->setItem('system.common.domain.expiredDomain', json_encode($expiredDomain));
        $this->setting->setItem('system.common.domain.customDomain', zget($settings, 'customDomain', ''));
        $this->setting->setItem('system.common.domain.https', zget($settings, 'https', 'false'));

        $this->loadModel('instance')->updateInstancesDomain();

        $this->updateMinioDomain();
    }

    /**
     * 更新域名。
     * Update minio domain.
     *
     * @access public
     * @return void
     */
    public function updateMinioDomain()
    {
        $this->loadModel('cne');
        $sysDomain = $this->cne->sysDomain();

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->chart     = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->minio = new stdclass;
        $settings->settings_map->minio->ingress = new stdclass;
        $settings->settings_map->minio->ingress->enabled = true;
        $settings->settings_map->minio->ingress->host    = 's3.' . $sysDomain;

        $this->cne->updateConfig($minioInstance, $settings);
    }

    /**
     * 创建备份。
     * Backup the instance.
     *
     * @param  object $instance
     * @param  string $mode     |manual|system|upgrade|downgrade
     * @return array
     */
    public function backup(object $instance, string $mode = ''): array
    {
        $rawResult = $this->cne->backup($instance, $this->app->user->account, $mode);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 获取备份状态。
     * Get backup status.
     *
     * @param  object $instance
     * @param  object $backup
     * @return array
     */
    public function getBackupStatus(object $instance, object $backup): array
    {
        $rawResult = $this->cne->getBackupStatus($instance, $backup);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 获取备份列表。
     * Get backup list.
     *
     * @param  object $instance
     * @return array
     */
    public function getBackupList(object $instance): array
    {
        $rawResult = $this->cne->getBackupList($instance);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 恢复一个备份。
     * Restore the backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @param  string $account
     * @return array
     */
    public function restore(object $instance, string $backupName, string $account = ''): array
    {
        $rawResult = $this->cne->restore($instance, $backupName, $account);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 删除一个备份。
     * Delete the backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @return array
     */
    public function deleteBackup(object $instance, string $backupName): array
    {
        $rawResult = $this->cne->deleteBackup($instance, $backupName);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }
}
