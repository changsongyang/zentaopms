#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=编辑研发需求测试
timeout=0
cid=80

- 成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editstory.ui.class.php';

$tester = new editStoryTester();
$tester->login();

$storyFrom = '客户';
