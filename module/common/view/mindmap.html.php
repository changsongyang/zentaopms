<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($jsRoot . 'mindmap/css/zui.mindmap.css');
js::import($jsRoot . 'mindmap/js/hotkey.min.js');
js::import($jsRoot . 'mindmap/js/zui.mindmap.js?v=2');
?>
