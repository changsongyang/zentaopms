<?php
/**
 * The allLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
#docListForm table tr {border: none;}
#docListForm th.c-id {width: 72px;}
#docListForm th.c-user {width: 80px;}
#docListForm th.c-actions {width: 84px; padding-left: 15px;}
</style>
<?php if(common::checkNotCN()):?>
<style>
#docListForm th.c-date {width: 108px;}
#docListForm th.c-user {width: 102px !important;}
</style>
<?php endif;?>
<?php if(empty($docs)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
    <?php
    if($browseType != 'bySearch' and $libID and (common::hasPriv('doc', 'create') or (common::hasPriv('api', 'create') and !$apiLibID)))
    {
        echo $this->doc->printCreateBtn($lib, $type, $objectID, $moduleID, $apiLibID, 'list');
    }
    ?>
  </p>
</div>
<?php else:?>
<?php $vars = "type=$type&objectID=$objectID&$libID=$libID&moduleID=$moduleID&browseType=$browseType&orderBy=%s&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
<div>
  <form class='main-table' method='post' id='docListForm'>
    <table class="table table-borderless table-hover table-files table-fixed no-margin  has-sort-head table-fixed">
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canExport):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB, '', 'tableContents');?>
          </th>
          <th class="c-name"><?php common::printOrderLink('title', $orderBy, $vars, $lang->doc->title, '', 'tableContents');?></th>
          <th class="c-user"><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->doc->addedByAB, '', 'tableContents');?></th>
          <th class="c-date"><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate, '', 'tableContents');?></th>
          <th class="c-user"><?php common::printOrderLink('editedBy', $orderBy, $vars, $lang->doc->editedBy, '', 'tableContents');?></th>
          <th class="c-date"><?php common::printOrderLink('editedDate', $orderBy, $vars, $lang->doc->editedDate, '', 'tableContents');?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($docs as $doc):?>
        <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
        <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
        <tr>
        <?php $objectID = isset($doc->{$type}) ? $doc->{$type} : 0;?>
          <td class="c-id" title='<?php echo $doc->id?>'>
            <?php if($canExport):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='docIDList[]' value='<?php echo $doc->id;?>'/>
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $doc->id);?>
          </td>
          </td>
          <td class="c-name" title='<?php echo $doc->title;?>'>
          <?php
          if(common::hasPriv('doc', 'objectLibs'))
          {
              echo html::a($this->createLink('doc', 'objectLibs', "type=$type&objectID=$objectID&libID=$doc->lib&docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title='{$doc->title}' data-app='{$this->app->tab}'");
          }
          else
          {
              echo "<i class='icon icon-file-text text-muted'></i> {$doc->title}";
          }
          ?>
          <?php if(common::canBeChanged('doc', $doc) and common::hasPriv('doc', 'collect') and $libType and $libType != 'api'):?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
          <?php endif;?>
          </td>
          <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
          <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'Y-m-d');?></td>
          <td class="c-user"><?php echo zget($users, $doc->editedBy);?></td>
          <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'Y-m-d');?></td>
          <td class="c-actions">
            <?php if(common::canBeChanged('doc', $doc)):?>
            <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'", true, false)?>
            <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if(!empty($docs)):?>
    <div class='table-footer'>
      <?php if($canExport):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-statistic"><?php echo sprintf($lang->doc->docSummary, count($docs));?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  <form>
</div>
<?php endif;?>
<script>
$(function()
{
    var pageSummary    = '<?php echo sprintf($lang->doc->docSummary, count($docs));?>';
    var checkedSummary = '<?php echo $lang->doc->docCheckedSummary?>';
    $('#docListForm').table(
    {
        replaceId: 'docIDList',
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find('tbody>tr.checked');
            var checkedTotal = $checkedRows.length;
            return checkedTotal ? checkedSummary.replace('%total%', checkedTotal) : pageSummary;
        }
    });
});
</script>
