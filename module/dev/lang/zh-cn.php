<?php
$lang->dev->common       = '二次开发';
$lang->dev->api          = '接口文档';
$lang->dev->db           = '数据字典';
$lang->dev->editor       = '编辑器';
$lang->dev->dbList       = '数据库';
$lang->dev->moduleList   = '模块列表';
$lang->dev->params       = '参数列表';
$lang->dev->type         = '类型';
$lang->dev->desc         = '描述';
$lang->dev->noParams     = '无参数';
$lang->dev->post         = 'POST参数';
$lang->dev->modifyValue  = '修改值';
$lang->dev->defaultValue = '默认值';

$lang->dev->paramRange     = '取值范围：%s';
$lang->dev->paramDate      = '日期格式：YY-mm-dd，如：2019-01-01';
$lang->dev->paramColor     = '颜色格式：#RGB，如：#3da7f5';
$lang->dev->paramMailto    = "填写帐号，多个账号用','分隔。";
$lang->dev->noteEditor     = "编辑器功能存在部分安全问题，如需使用该功能，可在此开启。";
$lang->dev->confirmRestore = '是否要恢复默认？';
$lang->dev->apiTips        = '可访问的每个页面都可通过JSON接口调取';

$lang->dev->language    = '语言：%s';
$lang->dev->default     = '默认值';
$lang->dev->currentLang = '当前语言';
$lang->dev->change      = '修改值';
$lang->dev->ER          = '业务需求';
$lang->dev->UR          = '用户需求';
$lang->dev->SR          = '软件需求';
$lang->dev->branch      = '平台/分支';

$lang->dev->fields = array();
$lang->dev->fields['id']     = '序号';
$lang->dev->fields['name']   = '字段';
$lang->dev->fields['desc']   = '描述';
$lang->dev->fields['type']   = '类型';
$lang->dev->fields['length'] = '长度';
$lang->dev->fields['null']   = '是否可空';

$lang->dev->switchList['1'] = '开启';
$lang->dev->switchList['0'] = '关闭';

$lang->dev->tableList = array();
$lang->dev->tableList['action']                = '系统日志';
$lang->dev->tableList['bug']                   = 'Bug';
$lang->dev->tableList['build']                 = '版本';
$lang->dev->tableList['burn']                  = '燃尽图';
$lang->dev->tableList['case']                  = '测试用例';
$lang->dev->tableList['casestep']              = '用例步骤';
$lang->dev->tableList['company']               = '公司';
$lang->dev->tableList['config']                = '配置';
$lang->dev->tableList['custom']                = '自定义';
$lang->dev->tableList['dept']                  = '部门';
$lang->dev->tableList['doc']                   = '文档';
$lang->dev->tableList['doclib']                = '文档库';
$lang->dev->tableList['effort']                = '日志';
$lang->dev->tableList['extension']             = '插件';
$lang->dev->tableList['file']                  = '附件';
$lang->dev->tableList['group']                 = '权限分组';
$lang->dev->tableList['grouppriv']             = '组权限';
$lang->dev->tableList['history']               = '操作历史';
$lang->dev->tableList['lang']                  = '语言定义';
$lang->dev->tableList['module']                = '模块';
$lang->dev->tableList['program']               = '项目集';
$lang->dev->tableList['product']               = $lang->productCommon;
$lang->dev->tableList['productplan']           = $lang->productCommon . '计划';
$lang->dev->tableList['project']               = $lang->projectCommon;
$lang->dev->tableList['programplan']           = "{$lang->projectCommon}阶段";
$lang->dev->tableList['weekly']                = '周报';
$lang->dev->tableList['sonarqube']             = 'Sonarqube';
$lang->dev->tableList['projectproduct']        = $lang->projectCommon . $lang->productCommon;
$lang->dev->tableList['projectstory']          = $lang->projectCommon . $lang->SRCommon;
$lang->dev->tableList['execution']             = $lang->executionCommon;
$lang->dev->tableList['release']               = '发布';
$lang->dev->tableList['story']                 = $lang->SRCommon;
$lang->dev->tableList['storyspec']             = "需求描述";
$lang->dev->tableList['task']                  = '任务';
$lang->dev->tableList['taskestimate']          = '任务预计';
$lang->dev->tableList['team']                  = '团队';
$lang->dev->tableList['testresult']            = '测试结果';
$lang->dev->tableList['testrun']               = '测试执行';
$lang->dev->tableList['testtask']              = '测试单';
$lang->dev->tableList['todo']                  = '待办';
$lang->dev->tableList['user']                  = '用户';
$lang->dev->tableList['usercontact']           = '用户联系人';
$lang->dev->tableList['usergroup']             = '用户权限组';
$lang->dev->tableList['userquery']             = '用户查询';
$lang->dev->tableList['usertpl']               = '用户模板';
$lang->dev->tableList['admin']                 = '后台管理';
$lang->dev->tableList['api']                   = 'API接口';
$lang->dev->tableList['backup']                = '备份';
$lang->dev->tableList['common']                = '公有模块';
$lang->dev->tableList['convert']               = '导入';
$lang->dev->tableList['dev']                   = '二次开发';
$lang->dev->tableList['editor']                = '编辑器';
$lang->dev->tableList['git']                   = 'GIT';
$lang->dev->tableList['index']                 = '首页';
$lang->dev->tableList['install']               = '安装';
$lang->dev->tableList['mail']                  = '邮箱';
$lang->dev->tableList['misc']                  = '杂项';
$lang->dev->tableList['my']                    = '我的地盘';
$lang->dev->tableList['qa']                    = '测试';
$lang->dev->tableList['report']                = '统计';
$lang->dev->tableList['search']                = '搜索';
$lang->dev->tableList['sso']                   = '单点登录';
$lang->dev->tableList['svn']                   = 'SVN';
$lang->dev->tableList['testcase']              = '测试用例';
$lang->dev->tableList['automation']            = '自动化测试';
$lang->dev->tableList['testreport']            = '测试报告';
$lang->dev->tableList['testsuite']             = '测试套件';
$lang->dev->tableList['caselib']               = '用例库';
$lang->dev->tableList['tree']                  = '模块关系';
$lang->dev->tableList['upgrade']               = '更新';
$lang->dev->tableList['cron']                  = '定时任务';
$lang->dev->tableList['datatable']             = '数据表格';
$lang->dev->tableList['block']                 = '区块';
$lang->dev->tableList['branch']                = '平台/分支';
$lang->dev->tableList['doccontent']            = '文档内容';
$lang->dev->tableList['storystage']            = "{$lang->SRCommon}阶段";
$lang->dev->tableList['tutorial']              = '新手教程';
$lang->dev->tableList['suitecase']             = '套件用例';
$lang->dev->tableList['score']                 = '积分';
$lang->dev->tableList['entry']                 = '应用';
$lang->dev->tableList['webhook']               = 'WebHook';
$lang->dev->tableList['log']                   = '接口日志';
$lang->dev->tableList['message']               = '消息';
$lang->dev->tableList['notify']                = '通知';
$lang->dev->tableList['userview']              = '可访问权限';
$lang->dev->tableList['repo']                  = '代码';
$lang->dev->tableList['repohistory']           = '版本历史';
$lang->dev->tableList['repofiles']             = '代码文件';
$lang->dev->tableList['repobranch']            = '代码分支';
$lang->dev->tableList['ci']                    = '持续集成';
$lang->dev->tableList['compile']               = '构建';
$lang->dev->tableList['jenkins']               = 'Jenkins';
$lang->dev->tableList['job']                   = '构建任务';
$lang->dev->tableList['searchdict']            = '搜索字典';
$lang->dev->tableList['searchindex']           = '搜索索引';
$lang->dev->tableList['kanbanlane']            = '看板泳道';
$lang->dev->tableList['kanbancolumn']          = '看板列';
$lang->dev->tableList['stakeholder']           = '干系人';
$lang->dev->tableList['im_chat_message_index'] = 'im聊天消息';
$lang->dev->tableList['im_message_backup']     = 'im消息备份';
$lang->dev->tableList['im_message_index']      = 'im消息索引';
$lang->dev->tableList['im_userdevice']         = 'im用户设备';
$lang->dev->tableList['baseimage']             = '图片';
$lang->dev->tableList['baseimagebrowser']      = '浏览图片';
$lang->dev->tableList['browser']               = '浏览';
$lang->dev->tableList['kanban']                = '看板';
$lang->dev->tableList['kanbancard']            = '看板卡片';
$lang->dev->tableList['kanbancell']            = '看板单元格';
$lang->dev->tableList['kanbangroup']           = '看板组';
$lang->dev->tableList['kanbanregion']          = '看板区域';
$lang->dev->tableList['kanbanspace']           = '看板空间';
$lang->dev->tableList['personnel']             = '可访问人员';
$lang->dev->tableList['projectbuild']          = "{$lang->projectCommon}版本";
$lang->dev->tableList['projectrelease']        = "{$lang->projectCommon}发布";
$lang->dev->tableList['design']                = '设计';
$lang->dev->tableList['stage']                 = '阶段';
$lang->dev->tableList['gitlab']                = 'Gitlab';
$lang->dev->tableList['gitea']                 = 'Gitea';
$lang->dev->tableList['gogs']                  = 'Gogs';
$lang->dev->tableList['holiday']               = '节假日';
$lang->dev->tableList['projectplan']           = "{$lang->projectCommon}计划";
$lang->dev->tableList['im']                    = '喧喧';
$lang->dev->tableList['transfer']              = '转换';
$lang->dev->tableList['client']                = '客户端版本更新';
$lang->dev->tableList['conference']            = '音视频';
$lang->dev->tableList['integration']           = '集成';
$lang->dev->tableList['license']               = '授权';
$lang->dev->tableList['zanode']                = '执行节点';
$lang->dev->tableList['dashboard']             = '仪表盘';
$lang->dev->tableList['screen']                = '大屏';
$lang->dev->tableList['zahost']                = '宿主机';
$lang->dev->tableList['approval']              = '审批';
$lang->dev->tableList['approvalflow']          = '审批流';
$lang->dev->tableList['chart']                 = '图表';
$lang->dev->tableList['dataset']               = '数据集';
$lang->dev->tableList['dataview']              = '数据表';
$lang->dev->tableList['dimension']             = '维度';
$lang->dev->tableList['metric']                = '度量项';
$lang->dev->tableList['pivot']                 = '透视表';

$lang->dev->groupList['my']        = '我的地盘';
$lang->dev->groupList['program']   = '项目集';
$lang->dev->groupList['product']   = $lang->productCommon;
$lang->dev->groupList['project']   = $lang->projectCommon;
$lang->dev->groupList['execution'] = $lang->executionCommon;
$lang->dev->groupList['kanban']    = '看板';
$lang->dev->groupList['qa']        = '测试';
$lang->dev->groupList['doc']       = '文档';
$lang->dev->groupList['assetlib']  = '资产库';
$lang->dev->groupList['report']    = '统计';
$lang->dev->groupList['company']   = '组织';
$lang->dev->groupList['repo']      = '持续集成';
$lang->dev->groupList['api']       = 'API';
$lang->dev->groupList['message']   = '消息';
$lang->dev->groupList['search']    = '搜索';

global $config;
if($config->systemMode != 'ALM') unset($lang->dev->groupList['program']);

$lang->dev->endGroupList['admin']  = '后台';
$lang->dev->endGroupList['system'] = '系统';
$lang->dev->endGroupList['other']  = '其他';

$lang->dev->featureBar['api']['restapi'] = 'RESTful接口';
$lang->dev->featureBar['api']['index']   = '内置页面接口';

$lang->dev->featureBar['langItem']['common']  = '公共';
$lang->dev->featureBar['langItem']['first']   = '一级菜单';
$lang->dev->featureBar['langItem']['second']  = '二级菜单';
$lang->dev->featureBar['langItem']['third']   = '三级菜单';
$lang->dev->featureBar['langItem']['tag']     = '检索标签';

$lang->dev->projectMenu['project']       = "{$lang->projectCommon}通用";
$lang->dev->projectMenu['scrum']         = "敏捷 / 融合敏捷{$lang->projectCommon}";
$lang->dev->projectMenu['waterfall']     = "瀑布 / 融合瀑布{$lang->projectCommon}";
$lang->dev->projectMenu['kanbanProject'] = "看板{$lang->projectCommon}";
if($config->vision == 'lite') $lang->dev->projectMenu['kanbanProject'] = $lang->projectCommon;

if($config->vision == 'rnd') $this->lang->dev->replaceLable['project-execution'] = "{$lang->executionCommon} / 阶段";
