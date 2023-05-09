#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(6);

/**

title=测试 projectModel->getProjectExecutionPairs();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$project = $tester->project->getProjectExecutionPairs();

r(count($project)) && p() && e('6'); //查看全部项目与执行键值对数组数量为6
r($project[4])     && p() && e('3'); //查看瀑布项目编号为3的阶段4
r($project[5])     && p() && e('3'); //查看瀑布项目编号为3的阶段5
r($project[6])     && p() && e('0'); //查看scrum项目编号为6的无执行
