<?php
/**
 * The group module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: en.php 4719 2013-05-03 02:20:28Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->group->common             = 'Privilège';
$lang->group->browse             = 'Groupes de Privilèges';
$lang->group->create             = 'Créer un Groupe';
$lang->group->edit               = 'Editer Groupe';
$lang->group->copy               = 'Copier Groupe';
$lang->group->delete             = 'Supprimer Groupe';
$lang->group->manageView         = 'Gérer droits consultation';
$lang->group->managePriv         = 'Gérer Privilèges';
$lang->group->managePrivByGroup  = 'Gérer Privilèges par Groupe';
$lang->group->managePrivByModule = 'Gérer Privilèges par Module';
$lang->group->byModuleTips       = '<span class="tips">(Shift/Ctrl pour sélection multiple)</span>';
$lang->group->allTips            = 'After checking this option, the administrator can manage all objects in the system, including objects created later.';
$lang->group->manageMember       = 'Gérer Membres';
$lang->group->manageProjectAdmin = "Manage {$lang->projectCommon} Admins";
$lang->group->editManagePriv     = 'Permission Edit';
$lang->group->confirmDelete      = "Voulez - vous supprimer '%s'?";
$lang->group->confirmDeleteAB    = 'Do you want to delete this?';
$lang->group->successSaved       = 'Sauvé.';
$lang->group->errorNotSaved      = 'Echec. Veuillez sélectionner actions et groupes.';
$lang->group->viewList           = 'Accès Consultation';
$lang->group->object             = 'Manage Object';
$lang->group->manageProgram      = 'Manage Program';
$lang->group->manageProject      = 'Manage ' . $lang->projectCommon;
$lang->group->manageExecution    = 'Manage ' . $lang->execution->common;
$lang->group->manageProduct      = 'Manage ' . $lang->productCommon;
$lang->group->programList        = 'Accè Programs';
$lang->group->productList        = 'Accès ' . $lang->productCommon . 's';
$lang->group->projectList        = 'Accès ' . $lang->projectCommon . 's';
$lang->group->executionList      = "Access {$lang->execution->common}";
$lang->group->dynamic            = 'Accès Historique';
$lang->group->noticeVisit        = "Blanc signifie « pas de limitation d'accès ».";
$lang->group->noticeNoChecked    = 'Please checked privilege!';
$lang->group->noneProgram        = "No Program";
$lang->group->noneProduct        = "No {$lang->productCommon}";
$lang->group->noneExecution      = "No {$lang->execution->common}";
$lang->group->project            = $lang->projectCommon;
$lang->group->group              = 'Group';
$lang->group->more               = 'More';
$lang->group->allCheck           = 'All';
$lang->group->noGroup            = 'Aucun groupe';
$lang->group->repeat             = "『%s』『%s』exists.Please adjust it and try again.";
$lang->group->noneProject        = 'No ' . $lang->projectCommon;
$lang->group->createPriv         = 'Add Priv';
$lang->group->editPriv           = 'Edit Priv';
$lang->group->deletePriv         = 'Delete Priv';
$lang->group->privName           = 'Priv Name';
$lang->group->privDesc           = 'Priv Desc';
$lang->group->add                = 'Add';
$lang->group->privModuleName     = 'Module Name';
$lang->group->privMethodName     = 'Method Name';
$lang->group->privView           = 'View';
$lang->group->privModule         = 'Module';
$lang->group->repeatPriv         = 'The method name of the same module cannot be the same. Please modify the method name and try again.';

$lang->group->batchActions              = 'Batch Operation';
$lang->group->batchSetDependency        = 'Batch Set Dependency';
$lang->group->batchSetRecommendation    = 'Batch Set Recommendation';
$lang->group->batchDeleteDependency     = 'Batch Delete Dependency';
$lang->group->batchDeleteRecommendation = 'Batch Delete Recommendation';
$lang->group->managePrivPackage         = 'Manage Priv Package';
$lang->group->createPrivPackage         = 'Create Priv Package';
$lang->group->editPrivPackage           = 'Edit Priv Package';
$lang->group->deletePrivPackage         = 'Delete Priv Package';
$lang->group->sortPrivPackages          = 'Sort Priv Package';
$lang->group->addRecommendation         = 'Add Recommendation';
$lang->group->addDependent              = 'Add Dependent';
$lang->group->deleteRecommendation      = 'Delete Recommendation';
$lang->group->deleteDependent           = 'Delete Dependent';
$lang->group->selectedPrivs             = 'Selected Privilege: %s';
$lang->group->selectModule              = 'Select Module';
$lang->group->recommendPrivs            = 'Recommended Privs';
$lang->group->dependentPrivs            = 'Dependented Privs';
$lang->group->addRelation               = 'Add Relation';
$lang->group->deleteRelation            = 'Delete Relation';
$lang->group->batchDeleteRelation       = 'Batch Delete Relation';
$lang->group->batchChangePackage        = 'Batch Change Priv Package';

$lang->group->id         = 'ID';
$lang->group->name       = 'Groupe';
$lang->group->desc       = 'Description';
$lang->group->role       = 'Rôle';
$lang->group->acl        = 'Right';
$lang->group->users      = 'Membres Groupe';
$lang->group->module     = 'Module';
$lang->group->method     = 'Méthode';
$lang->group->priv       = 'Groupe de Privilèges';
$lang->group->option     = 'Option';
$lang->group->inside     = "Groupe d'utilisateurs";
$lang->group->outside    = 'Autres utilisateurs';
$lang->group->limited    = 'Limited Users';
$lang->group->other      = 'Autres';
$lang->group->all        = 'Tous les Privilèges';
$lang->group->config     = 'Config';
$lang->group->unassigned = 'Unassigned';
$lang->group->view       = 'View';

if(!isset($lang->privpackage)) $lang->privpackage = new stdclass();
$lang->privpackage->common = 'Priv Package';
$lang->privpackage->id     = 'ID';
$lang->privpackage->name   = 'Priv Package Name';
$lang->privpackage->module = 'Module';
$lang->privpackage->desc   = 'Priv Package Desc';
$lang->privpackage->belong = 'Priv Package';

$lang->group->copyOptions['copyPriv'] = 'Copier Privilèges';
$lang->group->copyOptions['copyUser'] = 'Copier Utilisateurs';

$lang->group->versions['']           = 'History';
$lang->group->versions['16_5_beta1'] = 'ZenTao16.5.beta1';
$lang->group->versions['16_4']       = 'ZenTao16.4';
$lang->group->versions['16_3']       = 'ZenTao16.3';
$lang->group->versions['16_2']       = 'ZenTao16.2';
$lang->group->versions['16_1']       = 'ZenTao16.1';
$lang->group->versions['16_0']       = 'ZenTao16.0';
$lang->group->versions['16_0_beta1'] = 'ZenTao16.0.beta1';
$lang->group->versions['15_8']       = 'ZenTao15.8';
$lang->group->versions['15_7']       = 'ZenTao15.7';
$lang->group->versions['15_0_rc1']   = 'ZenTao15.0.rc1';
$lang->group->versions['12_5']       = 'ZenTao12.5';
$lang->group->versions['12_3']       = 'ZenTao12.3';
$lang->group->versions['11_6_2']     = 'ZenTao11.6.2';
$lang->group->versions['10_6']       = 'ZenTao10.6';
$lang->group->versions['10_1']       = 'ZenTao10.1';
$lang->group->versions['10_0_alpha'] = 'ZenTao10.0.alpha';
$lang->group->versions['9_8']        = 'ZenTao9.8';
$lang->group->versions['9_6']        = 'ZenTao9.6';
$lang->group->versions['9_5']        = 'ZenTao9.5';
$lang->group->versions['9_2']        = 'ZenTao9.2';
$lang->group->versions['9_1']        = 'ZenTao9.1';
$lang->group->versions['9_0']        = 'ZenTao9.0';
$lang->group->versions['8_4']        = 'ZenTao8.4';
$lang->group->versions['8_3']        = 'ZenTao8.3';
$lang->group->versions['8_2_beta']   = 'ZenTao8.2.beta';
$lang->group->versions['8_0_1']      = 'ZenTao8.0.1';
$lang->group->versions['8_0']        = 'ZenTao8.0';
$lang->group->versions['7_4_beta']   = 'ZenTao7.4.beta';
$lang->group->versions['7_3']        = 'ZenTao7.3';
$lang->group->versions['7_2']        = 'ZenTao7.2';
$lang->group->versions['7_1']        = 'ZenTao7.1';
$lang->group->versions['6_4']        = 'ZenTao6.4';
$lang->group->versions['6_3']        = 'ZenTao6.3';
$lang->group->versions['6_2']        = 'ZenTao6.2';
$lang->group->versions['6_1']        = 'ZenTao6.1';
$lang->group->versions['5_3']        = 'ZenTao5.3';
$lang->group->versions['5_1']        = 'ZenTao5.1';
$lang->group->versions['5_0_beta2']  = 'ZenTao5.0.beta2';
$lang->group->versions['5_0_beta1']  = 'ZenTao5.0.beta1';
$lang->group->versions['4_3_beta']   = 'ZenTao4.3.beta';
$lang->group->versions['4_2_beta']   = 'ZenTao4.2.beta';
$lang->group->versions['4_1']        = 'ZenTao4.1';
$lang->group->versions['4_0_1']      = 'ZenTao4.0.1';
$lang->group->versions['4_0']        = 'ZenTao4.0';
$lang->group->versions['4_0_beta2']  = 'ZenTao4.0.beta2';
$lang->group->versions['4_0_beta1']  = 'ZenTao4.0.beta1';
$lang->group->versions['3_3']        = 'ZenTao3.3';
$lang->group->versions['3_2_1']      = 'ZenTao3.2.1';
$lang->group->versions['3_2']        = 'ZenTao3.2';
$lang->group->versions['3_1']        = 'ZenTao3.1';
$lang->group->versions['3_0_beta2']  = 'ZenTao3.0.beta2';
$lang->group->versions['3_0_beta1']  = 'ZenTao3.0.beta1';
$lang->group->versions['2_4']        = 'ZenTao2.4';
$lang->group->versions['2_3']        = 'ZenTao2.3';
$lang->group->versions['2_2']        = 'ZenTao2.2';
$lang->group->versions['2_1']        = 'ZenTao2.1';
$lang->group->versions['2_0']        = 'ZenTao2.0';
$lang->group->versions['1_5']        = 'ZenTao1.5';
$lang->group->versions['1_4']        = 'ZenTao1.4';
$lang->group->versions['1_3']        = 'ZenTao1.3';
$lang->group->versions['1_2']        = 'ZenTao1.2';
$lang->group->versions['1_1']        = 'ZenTao1.1';
$lang->group->versions['1_0_1']      = 'ZenTao1.0.1';

include (dirname(__FILE__) . '/resource.php');
