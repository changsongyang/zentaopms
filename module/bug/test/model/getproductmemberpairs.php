#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

/**

title=bugModel->getProductMemberPairs();
timeout=0
cid=1

- 测试获取productID为1的bug的团队成员 @A:admin,U:用户32

- 测试获取productID为2的bug的团队成员 @U:用户23

- 测试获取productID为3的bug的团队成员 @U:用户14,U:用户44

- 测试获取productID为4的bug的团队成员 @U:用户5,U:用户35

- 测试获取productID为5的bug的团队成员 @U:用户26

- 测试获取productID为6的bug的团队成员 @U:用户17,U:用户47

- 测试获取不存在的product的bug的团队成员 @0

*/

zdTable('user')->gen(100);
zdTable('product')->gen(10);
zdTable('project')->gen(50);

$projectProduct = zdTable('projectproduct');
$projectProduct->product->range('1-10');
$projectProduct->gen(50);

zdTable('team')->gen(100);

$productIDList = array('1', '2', '3', '4','5', '6', '1000001');

$bug=new bugTest();
r($bug->getProductMemberPairsTest($productIDList[0])) && p() && e('A:admin,U:用户32');  // 测试获取productID为1的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[1])) && p() && e('U:用户23');          // 测试获取productID为2的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[2])) && p() && e('U:用户14,U:用户44'); // 测试获取productID为3的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[3])) && p() && e('U:用户5,U:用户35');  // 测试获取productID为4的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[4])) && p() && e('U:用户26');          // 测试获取productID为5的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[5])) && p() && e('U:用户17,U:用户47'); // 测试获取productID为6的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[6])) && p() && e('0');                 // 测试获取不存在的product的bug的团队成员