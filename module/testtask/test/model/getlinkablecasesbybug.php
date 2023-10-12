#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('case')->config('case')->gen(20);
zdTable('build')->config('build')->gen(3);

/**

title=测试 testtaskModel->getLinkableCasesByBug();
cid=1
pid=1

*/

global $tester, $app;

$app->setModuleName('testtask');
$app->setMethodName('linkCase');
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

$task1 = (object)array('build' => 0, 'branch' => 0); // 测试单 1 版本 0 分支 0 没有用例。
$task2 = (object)array('build' => 1, 'branch' => 0); // 测试单 1 版本 1 分支 0 没有用例。
$task3 = (object)array('build' => 3, 'branch' => 0); // 测试单 1 版本 3 分支 0 没有用例。
$task4 = (object)array('build' => 2, 'branch' => 0); // 测试单 1 版本 2 分支 0 有用例。
$task5 = (object)array('build' => 2, 'branch' => 1); // 测试单 1 版本 2 分支 1 有用例。

r($testtask->getLinkableCasesByBug(0, $task4)) && p() && e(0); // 产品 0 测试单 4 可关联到测试单的用例数为 0
r($testtask->getLinkableCasesByBug(2, $task4)) && p() && e(0); // 产品 2 测试单 4 可关联到测试单的用例数为 0。
r($testtask->getLinkableCasesByBug(1, $task1)) && p() && e(0); // 产品 1 测试单 1 可关联到测试单的用例数为 0。
r($testtask->getLinkableCasesByBug(1, $task2)) && p() && e(0); // 产品 1 测试单 2 可关联到测试单的用例数为 0。
r($testtask->getLinkableCasesByBug(1, $task3)) && p() && e(0); // 产品 1 测试单 3 可关联到测试单的用例数为 0。

$cases = $testtask->getLinkableCasesByBug(1, $task4);
r(count($cases)) && p() && e(6); // 产品 1 测试单 4 可关联到测试单的用例数为 6。
r($cases) && p('0:id,title,pri,type,auto,status') && e('8,这个是测试用例8,4,feature,no,investigate'); // 查看可关联的用例 8 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('7,这个是测试用例7,3,other,no,blocked');       // 查看可关联的用例 7 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('6,这个是测试用例6,2,interface,no,normal');    // 查看可关联的用例 6 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5);
r(count($cases)) && p() && e(12); // 产品 1 测试单 5 可关联到测试单的用例数为 12。
r($cases) && p('0:id,title,pri,type,auto,status') && e('19,这个是测试用例19,3,security,no,blocked');        // 查看可关联的用例 19 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id > 19');
r(count($cases)) && p() && e(0); // 产品 1 测试单 5 查询 id > 19 后可关联到测试单的用例数为 0。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id < 19');
r(count($cases)) && p() && e(11); // 产品 1 测试单 5 查询 id < 19 后可关联到测试单的用例数为 11。
r($cases) && p('0:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('14,这个是测试用例14,2,other,no,normal');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id < 19', array(2));
r(count($cases)) && p() && e(10); // 产品 1 测试单 5 查询 id < 19 并排除用例 2 后可关联到测试单的用例数为 10。
r($cases) && p('0:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('14,这个是测试用例14,2,other,no,normal');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id < 19', array(), $pager);
r(count($cases)) && p() && e(5); // 产品 1 测试单 5 查询 id < 19 并限制每页查询 5 条后可关联到测试单的用例数为 5。
r($cases) && p('0:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('14,这个是测试用例14,2,other,no,normal');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id < 19', array(2), $pager);
r(count($cases)) && p() && e(5); // 产品 1 测试单 5 查询 id < 19、排除用例 2 并限制每页查询 5 条后可关联到测试单的用例数为 5。
r($cases) && p('0:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('14,这个是测试用例14,2,other,no,normal');            // 查看可关联的用例 14 的详细信息。

$cases = $testtask->getLinkableCasesByBug(1, $task5, 'id < 19', array(2,3,4,5,6,7,8,9,10,11,12), $pager);
r(count($cases)) && p() && e(3); // 产品 1 测试单 5 查询 id < 19、排除用例 2-12 并限制每页查询 5 条后可关联到测试单的用例数为 3。
r($cases) && p('0:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('14,这个是测试用例14,2,other,no,normal');            // 查看可关联的用例 14 的详细信息。

$testtask->lang->navGroup->testtask = 'project';
$testtask->session->set('project', 2);
$cases = $testtask->getLinkableCasesByBug(1, $task5);
r(count($cases)) && p() && e(6); // 产品 1 测试单 5 在项目 2 中可关联到测试单的用例数为 6。
r($cases) && p('0:id,title,pri,type,auto,status') && e('19,这个是测试用例19,3,security,no,blocked');        // 查看可关联的用例 19 的详细信息。
r($cases) && p('1:id,title,pri,type,auto,status') && e('18,这个是测试用例18,2,install,no,normal');          // 查看可关联的用例 18 的详细信息。
r($cases) && p('2:id,title,pri,type,auto,status') && e('16,这个是测试用例16,4,performance,no,investigate'); // 查看可关联的用例 16 的详细信息。
