#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveOneCommit();
cid=1
pid=1

*/

$repo = new repoTest();

r($repo->saveOneCommitTest()) && p() && e();