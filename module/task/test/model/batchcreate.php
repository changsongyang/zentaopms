#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen('5');
zdTable('task')->config('task')->gen(8);
zdTable('taskspec')->gen(0);
zdTable('kanbanlane')->config('kanbanlane')->gen(10);
zdTable('kanbancolumn')->config('kanbancolumn')->gen(18);
zdTable('kanbancell')->config('kanbancell')->gen(18);

/**

title=测试taskModel->batchCreate();
timeout=0
cid=1

*/

$testTasks  = array(array('type' => 'test', 'name' => '测试任务1'), array('type' => 'test', 'name' => '测试任务2'));
$storyTasks = array(array('type' => 'story', 'story' => 1, 'name' => '需求任务1'), array('type' => 'story', 'story' => 1, 'name' => '需求任务2'));
$childTasks = array(array('type' => 'devel', 'parent' => 1, 'name' => '任务1子任务1'), array('type' => 'devel', 'parent' => 1, 'name' => '任务2子任务1'));

$taskTester = new taskTest();

$output[] = array('laneID' => 1, 'columnID' => 1);
$output[] = array('laneID' => 2, 'columnID' => 7);
$output[] = array('laneID' => 3, 'columnID' => 13);
$testTaskList[]   = $taskTester->batchCreateObject($testTasks);
$storyTaskList[]  = $taskTester->batchCreateObject($storyTasks);
$childTaskList[]  = $taskTester->batchCreateObject($childTasks);
$kanbanTaskList[] = $taskTester->batchCreateObject($testTasks, 'sprint', $output[0]);

$testTaskList[]   = $taskTester->batchCreateObject($testTasks, 'stage');
$storyTaskList[]  = $taskTester->batchCreateObject($storyTasks, 'stage');
$childTaskList[]  = $taskTester->batchCreateObject($childTasks, 'stage');
$kanbanTaskList[] = $taskTester->batchCreateObject($testTasks, 'stage', $output[1]);

$testTaskList[]   = $taskTester->batchCreateObject($testTasks, 'kanban');
$storyTaskList[]  = $taskTester->batchCreateObject($storyTasks, 'kanban');
$childTaskList[]  = $taskTester->batchCreateObject($childTasks, 'kanban');
$kanbanTaskList[] = $taskTester->batchCreateObject($testTasks, 'kanban', $output[2]);

r(current($testTaskList[0]))   && p() && e('9');  // 测试批量创建迭代下测试任务
r(count($testTaskList[0]))     && p() && e('2');  // 测试批量创建迭代下测试任务
r(current($storyTaskList[0]))  && p() && e('11'); // 测试批量创建迭代下需求任务
r(count($storyTaskList[0]))    && p() && e('2');  // 测试批量创建迭代下需求任务
r(current($childTaskList[0]))  && p() && e('13'); // 测试批量创建迭代下任务1子任务
r(count($childTaskList[0]))    && p() && e('2');  // 测试批量创建迭代下任务1子任务
r(current($kanbanTaskList[0])) && p() && e('15');  // 测试批量创建迭代下测试任务并更新看板
r(count($kanbanTaskList[0]))   && p() && e('2'); // 测试批量创建迭代下测试任务并更新看板

r(current($testTaskList[1]))   && p() && e('17'); // 测试批量创建阶段下测试任务
r(count($testTaskList[1]))     && p() && e('2');  // 测试批量创建阶段下测试任务
r(current($storyTaskList[1]))  && p() && e('19'); // 测试批量创建阶段下需求任务
r(count($storyTaskList[1]))    && p() && e('2');  // 测试批量创建阶段下需求任务
r(current($childTaskList[1]))  && p() && e('21'); // 测试批量创建阶段下任务1子任务
r(count($childTaskList[1]))    && p() && e('2');  // 测试批量创建阶段下任务1子任务
r(current($kanbanTaskList[1])) && p() && e('23'); // 测试批量创建阶段下测试任务并更新看板
r(count($kanbanTaskList[1]))   && p() && e('2');  // 测试批量创建阶段下测试任务并更新看板

r(current($testTaskList[2]))   && p() && e('25'); // 测试批量创建看板下测试任务
r(count($testTaskList[2]))     && p() && e('2');  // 测试批量创建看板下测试任务
r(current($storyTaskList[2]))  && p() && e('27'); // 测试批量创建看板下需求任务
r(count($storyTaskList[2]))    && p() && e('2');  // 测试批量创建看板下需求任务
r(current($childTaskList[2]))  && p() && e('29'); // 测试批量创建看板下任务1子任务
r(count($childTaskList[2]))    && p() && e('2');  // 测试批量创建看板下任务1子任务
r(current($kanbanTaskList[2])) && p() && e('31'); // 测试批量创建看板下测试任务并更新看板
r(count($kanbanTaskList[2]))   && p() && e('2');  // 测试批量创建看板下测试任务并更新看板
