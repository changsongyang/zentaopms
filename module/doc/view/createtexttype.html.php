<?php
/**
 * The create text view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id: createtext.html.php 975 2022-07-14 13:49:25Z $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/markdown.html.php';?>
<?php js::import($jsRoot . 'uploader/min.js');?>
<?php css::import($jsRoot . 'uploader/min.css');?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<style>
#main {padding: 0;}
.container {padding: 0 !important;}
#mainContent {padding: 0 !important;}
.doc-title input {border: unset; font-size: 18px; font-weight: bold; color: #3c4353; padding-left: 16px;}
.doc-title .form-control:focus {border: unset; box-shadow: unset;}
.doc-title input::-webkit-input-placeholder {color: #D8DBDE;}
.doc-title.required:after {top: 4px; right: 0; left: 12px; display: inline-table;}
#submit {margin-right: 16px;}
#headerBox {border-bottom: 1px solid #e3e3e3;}
#headerBox td:last-child {padding-right: 24px;}
#editorContent {padding: 0;}

#contentBox {padding: 0; width: 100%;}
.ke-container {overflow: visible;}
.ke-container, .contenthtml {border: unset; background: #efefef;}
.ke-container.focus {box-shadow: unset; border-color: unset;}
.ke-toolbar {padding-left: 20px; width: 100%; height: 30px;}
.ke-edit {border-top: 1px solid rgb(220, 220, 220)}
.ke-edit, .CodeMirror {margin: 8px 200px 0 200px; background: #fff;}
.kindeditor-ph {padding-left: 20px !important;}
.editor-toolbar {background: #fff; padding-left: 20px; border-right: unset; border-top: unset; height: 30px;}
.hide-sidebar .ke-edit {padding-right: 20px;}
.hide-sidebar .CodeMirror {padding-right: 50px;}
.CodeMirror.CodeMirror-wrap {border-left: 0; border-right: 0; border-bottom: 0;}
.ke-statusbar {display: none;}
.contentmarkdown {background: #efefef;}

.article-content {padding: 8px 20px;}

#noticeAcl {margin-left: 10px; vertical-align: middle;}

#moreList {display: inline-block; padding: 7px;}
#moreList .icon-more-circle {font-size: 20px;}
#moreList ul.dropdown-menu {left: -77px;}

#backBtn {border: unset;}
#backBtn i {font-size: 20px;}

.modal-title {font-size: 14px !important; font-weight: 700 !important;}
</style>
<?php if($objectType == 'custom' and empty($libs)):?>
<?php echo html::a(helper::createLink('doc', 'createLib', "type=custom&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="iframe hidden createCustomLib"');?>
<?php endif;?>
<?php $backLink = $this->createLink('doc', 'tableContents', "type=$objectType&objectID=$objectID&libID=$libID");?>
<div id="mainContent" class="main-content">
  <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr id='headerBox'>
          <td width='50px'><?php echo html::linkButton("<i class='icon icon-back-circle'></i>", $backLink, 'self', "id='backBtn'");?></td>
          <td class="doc-title" colspan='3'><?php echo html::input('title', '', "placeholder='{$lang->doc->titlePlaceholder}' class='form-control' required");?></td>
          <td class="text-right">
            <?php echo html::submitButton('', "data-placement='bottom'", 'btn btn-primary');?>
            <div id="moreList" class="dropdown dropdown-hover">
              <?php echo html::a('#', "<i class='icon icon-more-circle'></i>");?>
              <ul class="dropdown-menu">
                <li><?php echo html::a('#modalBasicInfo', $lang->doc->basicInfo, '', "data-toggle='modal' id='basicInfoLink'");?><li>
              </ul>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='5' id="editorContent">
            <div class="main-row fade in">
              <div id='contentBox' class="main-col">
                <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;'");?></div>
                <div class='contentmarkdown hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;'");?></div>
                <?php echo html::hidden('contentType', 'html');?>
                <?php echo html::hidden('type', 'text');?>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class='modal fade modal-basic' id='modalBasicInfo' data-scroll-inside='false'>
      <div class='modal-dialog'>
        <div class='modal-content with-padding'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal'>
              <i class="icon icon-close"></i>
            </button>
            <h2 class='modal-title'><?php echo $lang->doc->basicInfo;?></h2>
          </div>
          <div class='modal-body'>
            <table class='table table-form' id="basicInfoBox">
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->doc->lib;?></th>
                  <td colspan="2"><?php echo html::select('lib', $libs, $libID, "class='form-control chosen' onchange=loadDocModule(this.value)");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->module;?></th>
                  <td colspan="2">
                    <span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></span>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->keywords;?></th>
                  <td colspan='2'><?php echo html::input('keywords', '', "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
                </tr>
                <tr id='fileBox'>
                  <th><?php echo $lang->doc->files;?></th>
                  <td colspan='2'>
                    <div id='uploader' class="uploader" data-ride="uploader" data-url="<?php echo $this->createLink('file', 'ajaxUpload', "uid=" . uniqid());?>">
                      <div class="uploader-message text-center">
                        <div class="content"></div>
                        <button type="button" class="close">×</button>
                      </div>
                      <div class="uploader-files file-list file-list-lg" data-drag-placeholder="请拖拽文件到此处"></div>
                      <div class="uploader-actions">
                        <div class="uploader-status pull-right text-muted"></div>
                        <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> 选择文件</button>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->mailto;?></th>
                  <td colspan="2">
                    <div class="input-group">
                      <?php
                      echo html::select('mailto[]', $users, '', "multiple class='form-control picker-select' data-drop-direction='top'");
                      echo $this->fetch('my', 'buildContactLists');
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="th-control"><?php echo $lang->doclib->control;?></th>
                  <td colspan='2'>
                    <?php $acl = $lib->acl == 'default' ? 'open' : $lib->acl;?>
                    <?php $acl = ($lib->type == 'project' and $acl == 'private') ? 'open' : $acl;?>
                    <?php echo html::radio('acl', $lang->doc->aclList, $acl, "onchange='toggleAcl(this.value, \"doc\")'");?>
                    <span class='text-info' id='noticeAcl'><?php echo $lang->doc->noticeAcl['doc'][$acl];?></span>
                  </td>
                </tr>
                <tr id='whiteListBox' class='hidden'>
                  <th><?php echo $lang->doc->whiteList;?></th>
                  <td colspan='2'>
                    <div class='input-group'>
                      <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                      <?php echo html::select('groups[]', $groups, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
                    </div>
                    <div class='input-group'>
                      <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                      <?php echo html::select('users[]', $users, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan='3' class='text-center'><?php echo html::a('javascript:void(0)', $lang->save, '', "class='btn btn-primary btn-wide'");?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
$(function()
{
    var contentHeight = $(document).height() - 120;
    setTimeout(function(){$('.ke-edit-iframe, .ke-edit').height(contentHeight);}, 100);
    setTimeout(function(){$('.CodeMirror').height(contentHeight);}, 100);

    initialLibID     = 0;
    initialModuleID  = 0;
    initialKeywords  = '';
    initialAcl       = '';
    initialFilesName = [];
    initialFilesPath = [];
    initialMailto    = [];
    initialGroups    = [];
    initialUsers     = [];

    $('#basicInfoLink').click(function()
    {
        initialLibID    = $('#modalBasicInfo #lib').val();
        initialModuleID = $('#modalBasicInfo #module').val();
        initialKeywords = $('#modalBasicInfo #keywords').val();
        initialAcl      = $('#modalBasicInfo input[name=acl]:checked').val();
        initialMailto   = $('#modalBasicInfo #mailto').data('zui.picker').getValue();
        initialGroups   = $('#modalBasicInfo #groups').data('zui.picker').getValue();
        initialUsers    = $('#modalBasicInfo #users').data('zui.picker').getValue();

        $("input[name^='file']").each(function(){initialFilesPath.push($(this).val());});
        $("input[name^='label']").each(function(){initialFilesName.push($(this).val());});
    });

    $('#modalBasicInfo .modal-header .close').click(function()
    {
        $('#modalBasicInfo #lib').val(initialLibID).trigger('chosen:updated');
        $('#modalBasicInfo #lib').trigger('change');
        $('#modalBasicInfo #module').val(initialModuleID).trigger('chosen:updated');
        $('#modalBasicInfo #keywords').val(initialKeywords);
        $('#modalBasicInfo input:radio[value='+ initialAcl +']').attr('checked', 'checked');
        toggleAcl($('input[name="acl"]:checked').val(), 'doc');
        $('#modalBasicInfo #mailto').data('zui.picker').setValue(initialMailto);
        setTimeout(function(){$('#modalBasicInfo #groups').data('zui.picker').setValue(initialGroups)}, 1000);
        setTimeout(function(){$('#modalBasicInfo #users').data('zui.picker').setValue(initialUsers)}, 1000);
    });

    $(document).on('click', '#modalBasicInfo tfoot .btn', function() {$('#modalBasicInfo').modal('hide');});
})
</script>
<?php js::set('docType', $docType);?>
<?php js::set('fromGlobal', $fromGlobal);?>
<?php js::set('uid', uniqid());?>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['doc']);?>
<?php include '../../common/view/footer.html.php';?>
