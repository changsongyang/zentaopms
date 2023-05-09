#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('update')->gen(5);
}

/**

title=测试 todoModel->update();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('todo');

initData();

$t_upname = array('name' => '我的待办11');
$t_uptype = array('type' => 'bug', 'objectID' => '1');
$t_unname = array('name' => '我的待办2');

$todo = new todoTest();
r($todo->updateTest(1, $t_upname)) && p('0:field,old,new') && e('name,我的待办1,我的待办11');
r($todo->updateTest(1, $t_uptype)) && p('0:field,old,new') && e('type,custom,bug');
r($todo->updateTest(2, $t_unname)) && p()                  && e('没有数据更新');
