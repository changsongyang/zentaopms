<style>
.block-guide .tab-pane .mode-switch .dataTitle {padding: 14px 20px;}
.block-guide .tab-pane .mode-switch .mode-block {background: #E6F0FF; margin-left: 10px; cursor: pointer;}
.block-guide .tab-pane .mode-switch .mode-block:nth-child(2) {margin-left: 8%;}
.block-guide .tab-pane .mode-switch .mode-block.active {border: 2px solid #2E7FFF;}
.block-guide .tab-pane .mode-switch .mode-desc {padding: 10px;}
</style>
<?php $usedMode = zget($this->config->global, 'mode', 'light');?>
<?php js::set('usedMode', $usedMode);?>
<?php js::set('hasProgram', !empty($programs));?>
<?php js::set('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$usedMode == 'light' ? 'ALM' : 'light']));?>
<div class='table-row mode-switch'>
  <div class="col-4">
    <div class="col dataTitle"><?php echo $lang->block->customModeTip->common;?></div>
    <div class='col pull-left col-md-12'>
      <?php foreach($lang->block->customModes as $mode => $modeName):?>
      <div class="pull-left col-md-5 mode-block<?php if($usedMode == $mode) echo ' active';?>" data-mode='<?php echo $mode;?>'>
        <div><?php echo html::image($config->webRoot . "theme/default/images/guide/{$mode}.png");?></div>
        <div class='mode-desc'>
          <h4><?php echo $modeName;?></h4>
          <?php echo $lang->block->customModeTip->$mode;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>

<div class='modal fade' id='selectProgramModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>× </span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
        <h4 class='modal-title'><?php echo $lang->custom->selectDefaultProgram;?></h4>
      </div>
      <div class='modal-body'>
        <div class='alert alert-primary'>
          <p class='text-info'><?php echo $lang->custom->selectProgramTips;?></p>
        </div>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->custom->defaultProgram;?></th>
            <td><?php echo html::select('program', $programs, $programID, "class='form-control chosen'");?></td>
          </tr>
        </table>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-primary btn-wide btn-save'><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>

<script>
$(function()
{
    /**
     * Switch system mode.
     *
     * @param  string mode
     * @access public
     * @return void
     */
    function switchMode(mode)
    {}

    var $nav = $('#<?php echo "tab3{$blockNavId}ContentsystemMode";?>');
    $nav.on('click', '.mode-block', function()
    {
        var mode = $(this).data('mode');
        console.log(mode, hasProgram)
        if(mode == usedMode) return;

        if(mode == 'light' && hasProgram)
        {
            $('#selectProgramModal').modal('show');
        }
        else
        {
            bootbox.confirm(changeModeTips, function(result)
            {
                if(result) $('#modeForm').submit();
            });
        }
    });
});
</script>
