#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('testreport')->gen(100);
zdTable('testresult')->config('testresult')->gen(100);
zdTable('testrun')->gen(50);
zdTable('testtask')->gen(30);
zdTable('user')->gen(300);

su('admin');

/**

title=测试 testreportModel->getPerCaseRunner4Report();
cid=1
pid=1

*/

$taskID   = array('1,2,3', '4,5,6', '0');
$reportID = array(1, 3, 5);

$testreport = new testreportTest();

r($testreport->getPerCaseRunner4ReportTest($taskID[0], $reportID[0])) && p() && e('admin:4,用户2:2,用户3:2');         // 测试获取测试单 1 2 3 测试报告 1 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[0], $reportID[1])) && p() && e('admin:4,用户3:2,用户4:2');         // 测试获取测试单 1 2 3 测试报告 3 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[0], $reportID[2])) && p() && e('0');                               // 测试获取测试单 1 2 3 测试报告 5 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[1], $reportID[0])) && p() && e('0');                               // 测试获取测试单 4 5 6 测试报告 1 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[1], $reportID[1])) && p() && e('0');                               // 测试获取测试单 4 5 6 测试报告 3 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[1], $reportID[2])) && p() && e('admin:2,用户2:2,用户3:2,用户4:2'); // 测试获取测试单 4 5 6 测试报告 5 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[2], $reportID[0])) && p() && e('0');                               // 测试获取测试单 空 测试报告 1 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[2], $reportID[1])) && p() && e('0');                               // 测试获取测试单 空 测试报告 3 的结果。
r($testreport->getPerCaseRunner4ReportTest($taskID[2], $reportID[2])) && p() && e('0');                               // 测试获取测试单 空 测试报告 5 的结果。
