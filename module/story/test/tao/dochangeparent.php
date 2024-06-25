#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doUpdateSpec();
cid=0

- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()属性parent @0
- 执行$storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch()属性childStories @4,5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->id->range('1-100');
$story->title->range('teststory');
$story->product->range('1');
$story->parent->range('0,0,0,1,1');
$story->version->range('1');
$story->gen(5);

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->product = 1;
$story->parent  = 0;

$storyModel->doChangeParent(3, $story, 2);
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch()) && p('parent') && e('0');
$story->parent  = 1;
$storyModel->doChangeParent(3, $story, 0);
r($storyModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch()) && p('isParent') && e('1');
