<?php
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: edit.html.php 4259 2013-01-24 05:49:40Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/kindeditor.html.php';
js::set('page'                   , 'edit');
js::set('changeProductConfirmed' , false);
js::set('changeExecutionConfirmed' , false);
js::set('confirmChangeProduct'   , $lang->bug->confirmChangeProduct);
js::set('planID'                 , $bug->plan);
js::set('oldProjectID'           , $bug->project);
js::set('oldStoryID'             , $bug->story);
js::set('oldTaskID'              , $bug->task);
js::set('oldOpenedBuild'         , $bug->openedBuild);
js::set('oldResolvedBuild'       , $bug->resolvedBuild);
js::set('systemMode'             , $config->systemMode);
js::set('confirmUnlinkBuild'     , sprintf($lang->bug->confirmUnlinkBuild, zget($resolvedBuilds, $bug->resolvedBuild)));
js::set('tab'                    , $this->app->tab);
if($this->app->tab == 'execution') js::set('objectID', $bug->execution);
if($this->app->tab == 'project')   js::set('objectID', $bug->project);
?>

<div class='main-content' id='mainContent'>
  <form method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $bug->id;?></span>
        <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '', "class='bug-title' title='$bug->title'");?>
        <small><?php echo $lang->arrow . ' ' . $lang->bug->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='form-group'>
            <div class="input-control has-icon-right">
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                  <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                </ul>
                <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $bug->color;?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".bug-title"  data-provide="colorpicker">
              </div>
              <?php echo html::input('title', $bug->title, "class='form-control bug-title'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->bug->legendSteps;?></div>
            <div class='detail-content'>
              <?php echo html::textarea('steps', htmlSpecialString($bug->steps), "rows='12' class='form-control kindeditor' hidefocus='true'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->bug->legendComment;?></div>
            <div class='detail-content article-content'>
              <?php echo html::textarea('comment', '', "rows='5' class='form-control kindeditor' hidefocus='true'");?>
            </div>
          </div>
          <?php $this->printExtendFields($bug, 'div', 'position=left');?>
          <div class="detail">
            <div class="detail-title"><?php echo $lang->files;?></div>
            <div class='detail-content'><?php echo $this->fetch('file', 'buildform');?></div>
          </div>

          <div class='actions form-actions text-center'>
            <?php
            echo html::hidden('lastEditedDate', $bug->lastEditedDate);
            echo html::submitButton();
            echo html::backButton();
            ?>
          </div>
          <hr class='small' />
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendBasicInfo;?></div>
            <table class='table table-form'>
              <tbody>
                <tr>
                  <th class='w-80px'><?php echo $lang->bug->product;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value)' class='form-control chosen'");?>
                      <?php if($product->type != 'normal') echo html::select('branch', $branches, $bug->branch, "onchange='loadBranch();' class='form-control'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->module;?></th>
                  <td>
                    <div class='input-group' id='moduleIdBox'>
                    <?php
                    echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control chosen'");
                    if(count($moduleOptionMenu) == 1)
                    {
                        echo "<span class='input-group-addon'>";
                        echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$bug->branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                        echo '&nbsp; ';
                        echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
                        echo '</span>';
                    }
                    ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->productplan;?></th>
                  <td>
                    <span id="planIdBox"><?php echo html::select('plan', $plans, $bug->plan, "class='form-control chosen'");?></span>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->type;?></th>
                  <td><?php echo html::select('type', $lang->bug->typeList, $bug->type, "class='form-control chosen'"); ?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->severity;?></th>
                  <td><?php echo html::select('severity', $lang->bug->severityList, $bug->severity, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->pri;?></th>
                  <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->status;?></th>
                  <td><?php echo html::select('status', $lang->bug->statusList, $bug->status, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->confirmed;?></th>
                  <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->assignedTo;?></th>
                  <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->deadline;?></th>
                  <td><?php echo html::input('deadline', $bug->deadline, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->os;?></th>
                  <td><?php echo html::select('os', $lang->bug->osList, $bug->os, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->browser;?></th>
                  <td><?php echo html::select('browser', $lang->bug->browserList, $bug->browser, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->keywords;?></th>
                  <td><?php echo html::input('keywords', $bug->keywords, 'class="form-control"');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->mailto;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('mailto[]', $users, str_replace(' ', '', $bug->mailto), 'class="form-control chosen" multiple');?>
                      <?php echo $this->fetch('my', 'buildContactLists');?>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $config->systemMode == 'class' ? $lang->bug->legendExecStoryTask : $lang->bug->legendPRJExecStoryTask;?></div>
            <table class='table table-form'>
              <tbody>
                <?php if($config->systemMode == 'new'):?>
                <tr>
                  <th class='w-85px'><?php echo $lang->bug->project;?></th>
                  <td><span id='projectBox'><?php echo html::select('project', $projects, $bug->project, "class='form-control chosen' onchange='loadProductExecutions($bug->product, this.value)'");?></span></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='w-85px'><?php echo $lang->bug->execution;?></th>
                  <td><span id='executionIdBox'><?php echo html::select('execution', $executions, $bug->execution, "class='form-control chosen' onchange='loadExecutionRelated(this.value)'");?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->story;?></th>
                  <td><div id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, "class='form-control chosen'");?></div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->task;?></th>
                  <td><div id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, "class='form-control chosen'");?></div></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->bug->legendLife;?></div>
            <table class='table table-form'>
              <tbody>
                <tr>
                  <th class='thWidth'><?php echo $lang->bug->openedBy;?></th>
                  <td><?php echo zget($users, $bug->openedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->openedBuild;?></th>
                  <td>
                    <div id='openedBuildBox' class='input-group'>
                      <?php echo html::select('openedBuild[]', $openedBuilds, $bug->openedBuild, 'size=4 multiple=multiple class="chosen form-control"');?>
                      <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn' onclick='loadAllBuilds(this)'")?></span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolvedBy;?></th>
                  <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolvedDate;?></th>
                  <td><?php echo html::input('resolvedDate', $bug->resolvedDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolvedBuild;?></th>
                  <td>
                    <div id='resolvedBuildBox' class='input-group'>
                      <?php echo html::select('resolvedBuild', $resolvedBuilds, $bug->resolvedBuild, "class='form-control chosen'");?>
                      <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn' onclick='loadAllBuilds(this)'")?></span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolution;?></th>
                  <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=form-control onchange=setDuplicate(this.value)');?></td>
                </tr>
                <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
                  <th><?php echo $lang->bug->duplicateBug;?></th>
                  <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=form-control');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->closedBy;?></th>
                  <td><?php echo html::select('closedBy', $users, $bug->closedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->closedDate;?></th>
                  <td><?php echo html::input('closedDate', $bug->closedDate, "class='form-control form-datetime'");?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->bug->legendMisc;?></div>
            <table class='table table-form'>
              <tbody>
                <tr class='text-top'>
                  <th class='thWidth'><?php echo $lang->bug->linkBug;?></th>
                  <td><?php echo html::a($this->createLink('bug', 'linkBugs', "bugID=$bug->id", '', true), $lang->bug->linkBugs, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");?></td>
                </tr>
                <tr>
                  <th></th>
                  <td>
                    <ul class='list-unstyled'>
                      <?php
                      if(isset($bug->linkBugTitles))
                      {
                          foreach($bug->linkBugTitles as $linkBugID => $linkBugTitle)
                          {
                              echo "<li><div class='checkbox-primary'>";
                              echo "<input type='checkbox' checked='checked' name='linkBug[]' value=$linkBugID />";
                              echo "<label>#{$linkBugID} {$linkBugTitle}</label>";
                              echo '</div></li>';
                          }
                      }
                      ?>
                      <span id='linkBugsBox'></span>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->testtask;?></th>
                  <td id='testtaskBox'><?php echo html::select('testtask', $testtasks, $bug->testtask, 'class="form-control chosen"');?></td>
                </tr>
                <?php if($bug->case):?>
                <tr>
                  <th><?php echo $lang->bug->fromCase;?></th>
                  <td><?php echo html::input('case', $bug->case, 'class="form-control"');?></td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
          <?php $this->printExtendFields($bug, 'div', 'position=right');?>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
