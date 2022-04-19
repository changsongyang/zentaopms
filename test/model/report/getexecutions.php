#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getExecutions();
cid=1
pid=1

测试查询 开始时间 0 结束时间 0 的执行 >> 151:30,15,项目51;183:15,30,项目83;121:27,15,项目21;111:27,12,项目11;107:30,35,项目7;103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;
测试查询 开始时间大于 -40 day 结束时间小于 -10 day 的执行 >> 103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;
测试查询 开始时间大于 -28 day 结束时间小于 +5 day 的执行 >> 107:30,35,项目7;103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;
测试查询 开始时间大于 -24 day 结束时间小于 +10 day 的执行 >> 111:27,12,项目11;107:30,35,项目7;
测试查询 开始时间大于 -20 day 结束时间小于 +15 day 的执行 >> 111:27,12,项目11;
测试查询 开始时间大于 -20 day 结束时间小于 +20 day 的执行 >> 121:27,15,项目21;

*/

$begin = array('0', ' -70 day', ' -60 day', ' -55 day', ' -50 day', ' -40 day');
$end   = array('0', ' +10 day', ' +15 day', ' +20 day', ' +25 day', ' +30 day');

$report = new reportTest();

global $tester;
$tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('closed')->where('id')->in('101,102,103,107,111,121,151,183')->exec();

r($report->getExecutionsTest($begin[0], $end[0])) && p() && e('151:30,15,项目51;183:15,30,项目83;121:27,15,项目21;111:27,12,项目11;107:30,35,项目7;103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;'); // 测试查询 开始时间 0 结束时间 0 的执行
r($report->getExecutionsTest($begin[1], $end[1])) && p() && e('103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;');                                                                                     // 测试查询 开始时间大于 -40 day 结束时间小于 -10 day 的执行
r($report->getExecutionsTest($begin[2], $end[2])) && p() && e('107:30,35,项目7;103:23,35,项目3;102:9,18,项目2;101:3,15,项目1;');                                                                     // 测试查询 开始时间大于 -28 day 结束时间小于 +5 day 的执行
r($report->getExecutionsTest($begin[3], $end[3])) && p() && e('111:27,12,项目11;107:30,35,项目7;');                                                                                                  // 测试查询 开始时间大于 -24 day 结束时间小于 +10 day 的执行
r($report->getExecutionsTest($begin[4], $end[4])) && p() && e('111:27,12,项目11;');                                                                                                                  // 测试查询 开始时间大于 -20 day 结束时间小于 +15 day 的执行
r($report->getExecutionsTest($begin[5], $end[5])) && p() && e('121:27,15,项目21;');                                                                                                                  // 测试查询 开始时间大于 -20 day 结束时间小于 +20 day 的执行
$tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('wait')->where('id')->in('101,103,107,111,121,151,183')->exec();
$tester->dao->update(TABLE_EXECUTION)->set('`status`')->eq('doing')->where('id')->in('102')->exec();
