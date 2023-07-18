#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->gen(50);
zdTable('story')->config('story')->gen(50);
zdTable('case')->config('case')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=testcase_coverage_of_story_in_product.php
timeout=0
cid=1

*/

r($calc->getResult(array('product' => '1')))  && p('0:value') && e('0.4'); // 测试产品1的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '2')))  && p('0:value') && e('0.6'); // 测试产品2的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '3')))  && p('0:value') && e('1');   // 测试产品3的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '4')))  && p('0:value') && e('0');   // 测试产品4的已立项研发需求用例覆盖率数。
r($calc->getResult(array('product' => '5')))  && p('0:value') && e('0');   // 测试产品5的已立项研发需求用例覆盖率数。
