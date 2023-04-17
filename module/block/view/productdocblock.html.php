<style>
.block-productdoc .nav-stacked {overflow:auto; height:220px; max-height:220px; }
.block-productdoc .panel-heading {border-bottom:1px solid #ddd;}
.block-productdoc .panel-body {padding-top: 0; height:240px; padding-right:0px; overflow-x:hidden !important;}
.block-productdoc .tab-content {padding-right:0px;}
.block-productdoc .tab-pane {max-height:220px; overflow:auto;}
.block-productdoc table.tablesorter th{border-bottom:0px !important;}
.block-productdoc .tile {margin-bottom: 30px;}
.block-productdoc .tile-title {font-size: 18px; color: #A6AAB8;}
.block-productdoc .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-productdoc .col-nav {border-right: 1px solid #EBF2FB; width: 210px; padding: 0;}
.block-productdoc .nav-secondary > li {position: relative;}
.block-productdoc .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-productdoc .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-productdoc .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-productdoc .nav-secondary > li.active > a:first-child:hover,
.block-productdoc .nav-secondary > li.active > a:first-child:focus,
.block-productdoc .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-productdoc .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-productdoc .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-productdoc .nav-secondary > li:hover > a.btn-view {opacity: 1;}
.block-productdoc .nav-secondary > li.active > a.btn-view {box-shadow: none;}
.block-productdoc .nav-secondary > li.switch-icon {display: none;}
.block-productdoc.block-sm .nav-stacked {height:auto;}
.block-productdoc.block-sm .panel-body {padding-bottom: 10px; position: relative; padding-top: 45px; border-radius: 3px; height:275px;}
.block-productdoc.block-sm .panel-body > .table-row,
.block-productdoc.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-productdoc.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-productdoc.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-productdoc.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-productdoc.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5;}
.block-productdoc.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-productdoc.block-sm .nav-secondary > li {display: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon,
.block-productdoc.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-productdoc.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0, 0, 0, 0.07);}
.block-productdoc.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-productdoc.block-sm .nav-secondary > li > a:before {display: none;}
.block-productdoc.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-productdoc.block-sm .nav-secondary > li.active > a:first-child:before {display: none}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-productdoc.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.1);}

.block-productdoc .data {width: 40%; text-align: left; padding: 10px 0px; font-size: 14px; font-weight: 700;}
.block-productdoc .dataTitle {width: 60%; text-align: right; padding: 10px 0px; font-size: 14px;}
.block-productdoc .executionName {padding: 2px 10px; font-size: 14px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;}
.block-productdoc .lastIteration {padding-top: 6px;}

.forty-percent {width: 40%;}

.block-productdoc #productType {position: absolute;top: 6px;left: 120px;}
.block-productdoc #productType .btn {border:0px;}
</style>
<script>
<?php $blockNavId = 'nav-' . uniqid(); ?>
$(function()
{
    <?php if(!$longBlock):?>
    $(document).on('click', '.col-nav .switch-icon', function(e)
    {
        var $nav = $(this).closest('.nav');
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a[data-toggle="tab"]').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
        e.preventDefault();
    });
    <?php endif;?>

    if($('.block-productdoc #productType').length > 1);
    {
        count = $('.block-productdoc #productType').length;
        $('.block-productdoc #productType').each(function()
        {
            if(count == 1) return;
            $(this).remove();
            count --;
        })
    }

    var $productList = $('#activeProduct');
    if($productList.length)
    {
        var productList = $productList[0];
        $(".col ul.nav").animate({scrollTop: productList.offsetTop}, "slow");
    }
});

function changeProductType(type)
{
    $('.nav.products').toggleClass('hidden', type != 'all');
    $('.nav.involveds').toggleClass('hidden', type != 'involved');
    $('#productType .btn').html($('#productType [data-type=' + type + ']').html() + " <span class='caret'></span>");
    var name = type == 'all' ? '.products' : '.involveds';
    var $obj = $(name + ' li.active').length > 0 ? $(name + ' .active:first').find('a') : $(name + ' li:not(.switch-icon):first').find('a');
    $(name + ' li').removeClass('active');
    $obj.closest('li').addClass('active');
    $('.block-productdoc .tab-pane').removeClass('active').removeClass('in');
    $('.block-productdoc .tab-pane' + $obj.data('target')).addClass('active').addClass('in');
}

</script>
<div class="dropdown" id='productType'>
  <button class="btn" type="button" data-toggle="dropdown"><?php echo $lang->product->all;?> <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="javascript:changeProductType('all')" data-type='all'><?php echo $lang->product->all;?></a></li>
    <li><a href="javascript:changeProductType('involved')" data-type='involved'><?php echo $lang->product->involved;?></a></li>
  </ul>
</div>
<div class="panel-body">
  <div class="table-row">
    <?php if(empty($products) and empty($involveds)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
    </div>
    <?php else:?>
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover products">
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = key($products);?>
        <?php foreach($products as $product):?>
        <li <?php if($product->id == $selected) echo "class='active' id='activeProduct'";?> productID='<?php echo $product->id;?>'>
          <a href="###" title="<?php echo $product->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$product->id}";?>' data-toggle="tab"><?php echo $product->name;?></a>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
      <ul class="nav nav-stacked nav-secondary scrollbar-hover involveds hidden">
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php foreach($involveds as $product):?>
        <li productID='<?php echo $product->id;?>'>
          <a href="###" title="<?php echo $product->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$product->id}";?>' data-toggle="tab"><?php echo $product->name;?></a>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content">
      <?php foreach($products as $product):?>
      <div class="tab-pane fade<?php if($product->id == $selected) echo ' active in';?>" id='<?php echo "tab3{$blockNavId}Content{$product->id}";?>'>
        <?php if(isset($docGroup[$product->id])):?>
        <div class="table-row">
          <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter'>
            <thead>
              <tr>
                <th class='c-name'><?php echo $lang->doc->title?></th>
                <th class='c-user'><?php echo $lang->doc->addedBy?></th>
                <th class='c-date'><?php echo $lang->doc->addedDate?></th>
                <th class='c-date'><?php echo $lang->doc->editedDate?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($docGroup[$product->id] as $doc):?>
              <tr>
                <td class='c-name'>
                  <?php
                  $docType = zget($config->doc->iconList, $doc->type);
                  $icon    = html::image("static/svg/{$docType}.svg", "class='file-icon'");
                  if(common::hasPriv('doc', 'view'))
                  {
                      echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $icon . $doc->title, '', "title='{$doc->title}' class='doc-title' data-app='{$this->app->tab}'");
                  }
                  else
                  {
                      echo "<span class='doc-title'>$icon {$doc->title}</span>";
                  }
                  ?>
                </td>
                <td class='c-user'><?php echo zget($users, $doc->addedBy);?></td>
                <td class='c-date'><?php echo substr($doc->addedDate, 0, 10);?></td>
                <td class='c-date'><?php echo substr($doc->editedDate, 0, 10);?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?php else:?>
        <div class="table-empty-tip">
          <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
        </div>
        <?php endif;?>
      </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
