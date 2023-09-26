#!/usr/bin/env php
<?php
/**

title=测试 productplanTao->getPlanProjects()
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('execution')->gen(20);
$projectproduct = zdTable('projectproduct')->config('projectproduct');
$projectproduct->project->range('101-120');
$projectproduct->gen(20);

global $tester, $app;
$app->rawModule  = 'productplan';
$app->rawMethod  = 'browse';
$app->moduleName = 'productplan';
$app->methodName = 'browse';
$productplan = $tester->loadModel('productplan');

r($productplan->getPlanProjects(array()))                 && p()                   && e('0');         // 获取空数据
r($productplan->getPlanProjects(array(1))[1])             && p('101:project,name') && e('101,迭代5'); // 获取计划是1的项目
r($productplan->getPlanProjects(array(1, 4), 0)[1])       && p()                   && e('0');         // 获取计划是1,4，产品是0的项目
r($productplan->getPlanProjects(array(1, 4), 2)[4])       && p('102:project,name') && e('102,迭代6'); // 获取计划是1,4，产品是2的项目
r($productplan->getPlanProjects(array(1, 4, 7), null)[7]) && p('103:project,name') && e('103,迭代7'); // 获取计划是1,4,7的项目
