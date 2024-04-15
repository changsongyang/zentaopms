ALTER TABLE `zt_metric` ADD COLUMN `alias` varchar(90) NOT NULL DEFAULT '' AFTER `code`;
CREATE INDEX `date` ON zt_metriclib (date);

UPDATE `zt_chart` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_pivot` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_pivot` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_chart` SET `editedBy` = 'system' where `editedBy` = 'admin';
UPDATE `zt_pivot` SET `editedBy` = 'system' where `editedBy` = 'admin';
UPDATE `zt_pivot` SET `editedBy` = 'system' where `editedBy` = 'admin';

UPDATE zt_project SET lifetime = 'long' where lifetime = 'waterfall';

UPDATE `zt_metric` SET `alias` = '所有层级的项目集总数'       WHERE `code` = 'count_of_program';
UPDATE `zt_metric` SET `alias` = '所有层级进行中项目集数'     WHERE `code` = 'count_of_doing_program';
UPDATE `zt_metric` SET `alias` = '所有层级已关闭项目集数'     WHERE `code` = 'count_of_closed_program';
UPDATE `zt_metric` SET `alias` = '所有层级已挂起项目集数'     WHERE `code` = 'count_of_suspended_program';
UPDATE `zt_metric` SET `alias` = '所有层级未开始项目集数'     WHERE `code` = 'count_of_wait_program';
UPDATE `zt_metric` SET `alias` = '一级项目集总数'             WHERE `code` = 'count_of_top_program';
UPDATE `zt_metric` SET `alias` = '已关闭一级项目集数'         WHERE `code` = 'count_of_closed_top_program';
UPDATE `zt_metric` SET `alias` = '未关闭的一级项目集数'       WHERE `code` = 'count_of_unclosed_top_program';
UPDATE `zt_metric` SET `alias` = '新增一级项目集数'           WHERE `code` = 'count_of_annual_created_top_program';
UPDATE `zt_metric` SET `alias` = '关闭一级项目集数'           WHERE `code` = 'count_of_annual_closed_top_program';
UPDATE `zt_metric` SET `alias` = '产品线总数'                 WHERE `code` = 'count_of_line';
UPDATE `zt_metric` SET `alias` = '产品总数'                   WHERE `code` = 'count_of_product';
UPDATE `zt_metric` SET `alias` = '正常的产品数'               WHERE `code` = 'count_of_normal_product';
UPDATE `zt_metric` SET `alias` = '结束的产品数'               WHERE `code` = 'count_of_closed_product';
UPDATE `zt_metric` SET `alias` = '新增产品数'                 WHERE `code` = 'count_of_annual_created_product';
UPDATE `zt_metric` SET `alias` = '结束产品数'                 WHERE `code` = 'count_of_annual_closed_product';
UPDATE `zt_metric` SET `alias` = '项目总数'                   WHERE `code` = 'count_of_project';
UPDATE `zt_metric` SET `alias` = '未开始项目数'               WHERE `code` = 'count_of_wait_project';
UPDATE `zt_metric` SET `alias` = '进行中项目数'               WHERE `code` = 'count_of_doing_project';
UPDATE `zt_metric` SET `alias` = '已挂起项目数'               WHERE `code` = 'count_of_suspended_project';
UPDATE `zt_metric` SET `alias` = '已关闭项目数'               WHERE `code` = 'count_of_closed_project';
UPDATE `zt_metric` SET `alias` = '未关闭项目数'               WHERE `code` = 'count_of_unclosed_project';
UPDATE `zt_metric` SET `alias` = '已完成项目中按期完成项目数' WHERE `code` = 'count_of_undelayed_finished_project_which_finished';
UPDATE `zt_metric` SET `alias` = '已完成项目中延期完成项目数' WHERE `code` = 'count_of_delayed_finished_project_which_finished';
UPDATE `zt_metric` SET `alias` = '新增项目数'                 WHERE `code` = 'count_of_annual_created_project';
UPDATE `zt_metric` SET `alias` = '关闭项目数'                 WHERE `code` = 'count_of_annual_closed_project';
UPDATE `zt_metric` SET `alias` = '启动项目中按期完成项目数'   WHERE `code` = 'count_of_undelayed_finished_project_which_annual_started';
UPDATE `zt_metric` SET `alias` = '完成项目中延期完成项目数'   WHERE `code` = 'count_of_delayed_finished_project_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '完成项目中按期完成项目数'   WHERE `code` = 'count_of_undelayed_finished_project_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '新增项目数'                 WHERE `code` = 'count_of_monthly_created_project';
UPDATE `zt_metric` SET `alias` = '关闭项目数'                 WHERE `code` = 'count_of_monthly_closed_project';
UPDATE `zt_metric` SET `alias` = '完成项目数'                 WHERE `code` = 'count_of_annual_finished_project';
UPDATE `zt_metric` SET `alias` = '关闭项目的任务预计工时数'   WHERE `code` = 'estimate_of_annual_closed_project';
UPDATE `zt_metric` SET `alias` = '关闭项目的任务消耗工时数'   WHERE `code` = 'consume_of_annual_closed_project';
UPDATE `zt_metric` SET `alias` = '关闭项目的任务消耗工时数'   WHERE `code` = 'consume_of_monthly_closed_project';
UPDATE `zt_metric` SET `alias` = '已关闭项目投入总人天'       WHERE `code` = 'day_of_annual_closed_project';
UPDATE `zt_metric` SET `alias` = '完成项目中项目的按期完成率' WHERE `code` = 'rate_of_undelayed_finished_project_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '完成项目中项目的延期完成率' WHERE `code` = 'rate_of_delayed_finished_project_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '计划总数'                   WHERE `code` = 'count_of_productplan';
UPDATE `zt_metric` SET `alias` = '新增计划数'                 WHERE `code` = 'count_of_annual_created_productplan';
UPDATE `zt_metric` SET `alias` = '完成计划数'                 WHERE `code` = 'count_of_annual_finished_productplan';
UPDATE `zt_metric` SET `alias` = '关闭计划数'                 WHERE `code` = 'count_of_annual_closed_productplan';
UPDATE `zt_metric` SET `alias` = '已完成计划数'               WHERE `code` = 'count_of_finished_productplan';
UPDATE `zt_metric` SET `alias` = '未完成计划数'               WHERE `code` = 'count_of_unfinished_productplan';
UPDATE `zt_metric` SET `alias` = '执行总数'                   WHERE `code` = 'count_of_execution';
UPDATE `zt_metric` SET `alias` = '未开始执行数'               WHERE `code` = 'count_of_wait_execution';
UPDATE `zt_metric` SET `alias` = '进行中执行数'               WHERE `code` = 'count_of_doing_execution';
UPDATE `zt_metric` SET `alias` = '已挂起执行数'               WHERE `code` = 'count_of_suspended_execution';
UPDATE `zt_metric` SET `alias` = '已关闭执行数'               WHERE `code` = 'count_of_closed_execution';
UPDATE `zt_metric` SET `alias` = '未关闭执行数'               WHERE `code` = 'count_of_unclosed_execution';
UPDATE `zt_metric` SET `alias` = '新增执行数'                 WHERE `code` = 'count_of_annual_created_execution';
UPDATE `zt_metric` SET `alias` = '关闭执行数'                 WHERE `code` = 'count_of_annual_closed_execution';
UPDATE `zt_metric` SET `alias` = '新增执行数'                 WHERE `code` = 'count_of_monthly_created_execution';
UPDATE `zt_metric` SET `alias` = '关闭执行数'                 WHERE `code` = 'count_of_monthly_closed_execution';
UPDATE `zt_metric` SET `alias` = '已完成执行中按期完成执行数' WHERE `code` = 'count_of_undelayed_finished_execution_which_finished';
UPDATE `zt_metric` SET `alias` = '已完成执行中延期完成执行数' WHERE `code` = 'count_of_delayed_finished_execution_which_finished';
UPDATE `zt_metric` SET `alias` = '完成执行中按期完成执行数'   WHERE `code` = 'count_of_undelayed_finished_execution_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '完成执行中延期完成执行数'   WHERE `code` = 'count_of_delayed_finished_execution_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '完成执行中执行的按期关闭率' WHERE `code` = 'rate_of_undelayed_closed_execution_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '完成执行中执行的延期关闭率' WHERE `code` = 'rate_of_delayed_closed_execution_which_annual_finished';
UPDATE `zt_metric` SET `alias` = '发布总数'                   WHERE `code` = 'count_of_release';
UPDATE `zt_metric` SET `alias` = '里程碑发布总数'             WHERE `code` = 'count_of_marker_release';
UPDATE `zt_metric` SET `alias` = '新增发布数'                 WHERE `code` = 'count_of_annual_created_release';
UPDATE `zt_metric` SET `alias` = '新增发布数'                 WHERE `code` = 'count_of_monthly_created_release';
UPDATE `zt_metric` SET `alias` = '新增发布数'                 WHERE `code` = 'count_of_weekly_created_release';
UPDATE `zt_metric` SET `alias` = '研发需求总数'               WHERE `code` = 'count_of_story';
UPDATE `zt_metric` SET `alias` = '已关闭研发需求数'           WHERE `code` = 'count_of_closed_story';
UPDATE `zt_metric` SET `alias` = '已完成研发需求数'           WHERE `code` = 'count_of_finished_story';
UPDATE `zt_metric` SET `alias` = '未关闭研发需求数'           WHERE `code` = 'count_of_unclosed_story';
UPDATE `zt_metric` SET `alias` = '无效研发需求数'             WHERE `code` = 'count_of_invalid_story';
UPDATE `zt_metric` SET `alias` = '有效研发需求数'             WHERE `code` = 'count_of_valid_story';
UPDATE `zt_metric` SET `alias` = '已交付研发需求数'           WHERE `code` = 'count_of_delivered_story';
UPDATE `zt_metric` SET `alias` = '新增研发需求数'             WHERE `code` = 'count_of_annual_created_story';
UPDATE `zt_metric` SET `alias` = '完成研发需求数'             WHERE `code` = 'count_of_annual_finished_story';
UPDATE `zt_metric` SET `alias` = '新增研发需求数'             WHERE `code` = 'count_of_monthly_created_story';
UPDATE `zt_metric` SET `alias` = '完成研发需求数'             WHERE `code` = 'count_of_monthly_finished_story';
UPDATE `zt_metric` SET `alias` = '交付研发需求数'             WHERE `code` = 'count_of_annual_delivered_story';
UPDATE `zt_metric` SET `alias` = '研发需求规模总数'           WHERE `code` = 'scale_of_story';
UPDATE `zt_metric` SET `alias` = '已完成研发需求规模数'       WHERE `code` = 'scale_of_finished_story';
UPDATE `zt_metric` SET `alias` = '无效研发需求规模数'         WHERE `code` = 'scale_of_invalid_story';
UPDATE `zt_metric` SET `alias` = '有效研发需求规模数'         WHERE `code` = 'scale_of_valid_story';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_annual_finished_story';
UPDATE `zt_metric` SET `alias` = '交付研发需求规模数'         WHERE `code` = 'scale_of_annual_delivered_story';
UPDATE `zt_metric` SET `alias` = '关闭研发需求规模数'         WHERE `code` = 'scale_of_annual_closed_story';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_monthly_finished_story';
UPDATE `zt_metric` SET `alias` = '关闭研发需求规模数'         WHERE `code` = 'scale_of_monthly_closed_story';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_weekly_finished_story';
UPDATE `zt_metric` SET `alias` = '完成需求数'                 WHERE `code` = 'count_of_weekly_finished_story';
UPDATE `zt_metric` SET `alias` = '新增研发需求数'             WHERE `code` = 'count_of_daily_created_story';
UPDATE `zt_metric` SET `alias` = '研发需求完成率'             WHERE `code` = 'rate_of_finished_story';
UPDATE `zt_metric` SET `alias` = '研发需求交付率'             WHERE `code` = 'rate_of_delivered_story';
UPDATE `zt_metric` SET `alias` = '研发需求完成率'             WHERE `code` = 'rate_of_annual_finished_story';
UPDATE `zt_metric` SET `alias` = '研发需求交付率'             WHERE `code` = 'rate_of_annual_delivered_story';
UPDATE `zt_metric` SET `alias` = '任务总数'                   WHERE `code` = 'count_of_task';
UPDATE `zt_metric` SET `alias` = '已完成任务数'               WHERE `code` = 'count_of_finished_task';
UPDATE `zt_metric` SET `alias` = '未完成任务数'               WHERE `code` = 'count_of_unfinished_task';
UPDATE `zt_metric` SET `alias` = '已关闭任务数'               WHERE `code` = 'count_of_closed_task';
UPDATE `zt_metric` SET `alias` = '新增任务数'                 WHERE `code` = 'count_of_annual_created_task';
UPDATE `zt_metric` SET `alias` = '完成任务数'                 WHERE `code` = 'count_of_annual_finished_task';
UPDATE `zt_metric` SET `alias` = '新增任务数'                 WHERE `code` = 'count_of_monthly_created_task';
UPDATE `zt_metric` SET `alias` = '完成任务数'                 WHERE `code` = 'count_of_monthly_finished_task';
UPDATE `zt_metric` SET `alias` = '任务预计工时数'             WHERE `code` = 'estimate_of_task';
UPDATE `zt_metric` SET `alias` = '任务消耗工时数'             WHERE `code` = 'consume_of_task';
UPDATE `zt_metric` SET `alias` = '任务剩余工时数'             WHERE `code` = 'left_of_task';
UPDATE `zt_metric` SET `alias` = '完成任务数'                 WHERE `code` = 'count_of_daily_finished_task';
UPDATE `zt_metric` SET `alias` = 'Bug总数'                    WHERE `code` = 'count_of_bug';
UPDATE `zt_metric` SET `alias` = '激活Bug数'                  WHERE `code` = 'count_of_activated_bug';
UPDATE `zt_metric` SET `alias` = '已解决Bug数'                WHERE `code` = 'count_of_resolved_bug';
UPDATE `zt_metric` SET `alias` = '已关闭Bug数'                WHERE `code` = 'count_of_closed_bug';
UPDATE `zt_metric` SET `alias` = '未关闭Bug数'                WHERE `code` = 'count_of_unclosed_bug';
UPDATE `zt_metric` SET `alias` = '已修复Bug数'                WHERE `code` = 'count_of_fixed_bug';
UPDATE `zt_metric` SET `alias` = '有效Bug数'                  WHERE `code` = 'count_of_valid_bug';
UPDATE `zt_metric` SET `alias` = '新增Bug数'                  WHERE `code` = 'count_of_annual_created_bug';
UPDATE `zt_metric` SET `alias` = '修复Bug数'                  WHERE `code` = 'count_of_annual_fixed_bug';
UPDATE `zt_metric` SET `alias` = '新增Bug数'                  WHERE `code` = 'count_of_monthly_created_bug';
UPDATE `zt_metric` SET `alias` = '修复Bug数'                  WHERE `code` = 'count_of_monthly_fixed_bug';
UPDATE `zt_metric` SET `alias` = '关闭Bug数'                  WHERE `code` = 'count_of_daily_closed_bug';
UPDATE `zt_metric` SET `alias` = 'Bug修复率'                  WHERE `code` = 'rate_of_fixed_bug';
UPDATE `zt_metric` SET `alias` = '用例总数'                   WHERE `code` = 'count_of_case';
UPDATE `zt_metric` SET `alias` = '新增用例数'                 WHERE `code` = 'count_of_annual_created_case';
UPDATE `zt_metric` SET `alias` = '执行用例次数'               WHERE `code` = 'count_of_daily_run_case';
UPDATE `zt_metric` SET `alias` = '用户总数'                   WHERE `code` = 'count_of_user';
UPDATE `zt_metric` SET `alias` = '添加用户数'                 WHERE `code` = 'count_of_annual_created_user';
UPDATE `zt_metric` SET `alias` = '日志记录的工时总数'         WHERE `code` = 'hour_of_annual_effort';
UPDATE `zt_metric` SET `alias` = '投入总人天'                 WHERE `code` = 'day_of_annual_effort';
UPDATE `zt_metric` SET `alias` = '投入总人天'                 WHERE `code` = 'day_of_daily_effort';
UPDATE `zt_metric` SET `alias` = '日志记录的工时总数'         WHERE `code` = 'hour_of_daily_effort';
UPDATE `zt_metric` SET `alias` = '文档总数'                   WHERE `code` = 'count_of_doc';
UPDATE `zt_metric` SET `alias` = '新增文档个数'               WHERE `code` = 'count_of_annual_created_doc';
UPDATE `zt_metric` SET `alias` = '反馈总数'                   WHERE `code` = 'count_of_feedback';
UPDATE `zt_metric` SET `alias` = '已关闭反馈数'               WHERE `code` = 'count_of_closed_feedback';
UPDATE `zt_metric` SET `alias` = '新增反馈数'                 WHERE `code` = 'count_of_annual_created_feedback';
UPDATE `zt_metric` SET `alias` = '关闭反馈数'                 WHERE `code` = 'count_of_annual_closed_feedback';
UPDATE `zt_metric` SET `alias` = '制品库总数'                 WHERE `code` = 'count_of_artifactrepo';
UPDATE `zt_metric` SET `alias` = '节点总数'                   WHERE `code` = 'count_of_node';
UPDATE `zt_metric` SET `alias` = '应用总数'                   WHERE `code` = 'count_of_application';
UPDATE `zt_metric` SET `alias` = '代码库待处理问题数'         WHERE `code` = 'count_of_pending_issue';
UPDATE `zt_metric` SET `alias` = '代码库中待处理的合并请求数' WHERE `code` = 'count_of_pending_mergeRequest';
UPDATE `zt_metric` SET `alias` = '待处理的上线计划数'         WHERE `code` = 'count_of_pending_deployment';
UPDATE `zt_metric` SET `alias` = '计划总数'                   WHERE `code` = 'count_of_productplan_in_product';
UPDATE `zt_metric` SET `alias` = '新增计划数'                 WHERE `code` = 'count_of_annual_created_productplan_in_product';
UPDATE `zt_metric` SET `alias` = '完成计划数'                 WHERE `code` = 'count_of_annual_finished_productplan_in_product';
UPDATE `zt_metric` SET `alias` = '发布总数'                   WHERE `code` = 'count_of_release_in_product';
UPDATE `zt_metric` SET `alias` = '新增发布数'                 WHERE `code` = 'count_of_annual_created_release_in_product';
UPDATE `zt_metric` SET `alias` = '新增发布数'                 WHERE `code` = 'count_of_monthly_created_release_in_product';
UPDATE `zt_metric` SET `alias` = '研发需求总数'               WHERE `code` = 'count_of_story_in_product';
UPDATE `zt_metric` SET `alias` = '已完成研发需求数'           WHERE `code` = 'count_of_finished_story_in_product';
UPDATE `zt_metric` SET `alias` = '已关闭研发需求数'           WHERE `code` = 'count_of_closed_story_in_product';
UPDATE `zt_metric` SET `alias` = '未关闭研发需求数'           WHERE `code` = 'count_of_unclosed_story_in_product';
UPDATE `zt_metric` SET `alias` = '已交付研发需求数'           WHERE `code` = 'count_of_delivered_story_in_product';
UPDATE `zt_metric` SET `alias` = '无效研发需求数'             WHERE `code` = 'count_of_invalid_story_in_product';
UPDATE `zt_metric` SET `alias` = '有效研发需求数'             WHERE `code` = 'count_of_valid_story_in_product';
UPDATE `zt_metric` SET `alias` = '研发完毕的研发需求数'       WHERE `code` = 'count_of_developed_story_in_product';
UPDATE `zt_metric` SET `alias` = '已立项研发需求的用例覆盖率' WHERE `code` = 'case_coverage_of_projected_story_in_product';
UPDATE `zt_metric` SET `alias` = '新增研发需求数'             WHERE `code` = 'count_of_annual_created_story_in_product';
UPDATE `zt_metric` SET `alias` = '完成研发需求数'             WHERE `code` = 'count_of_annual_finished_story_in_product';
UPDATE `zt_metric` SET `alias` = '交付研发需求数'             WHERE `code` = 'count_of_annual_delivered_story_in_product';
UPDATE `zt_metric` SET `alias` = '关闭研发需求数'             WHERE `code` = 'count_of_annual_closed_story_in_product';
UPDATE `zt_metric` SET `alias` = '完成研发需求数'             WHERE `code` = 'count_of_monthly_finished_story_in_product';
UPDATE `zt_metric` SET `alias` = '已立项研发需求数'           WHERE `code` = 'count_of_projected_story_in_product';
UPDATE `zt_metric` SET `alias` = '交付研发需求数'             WHERE `code` = 'count_of_monthly_delivered_story_in_product';
UPDATE `zt_metric` SET `alias` = '有用例的已立项研发需求数'   WHERE `code` = 'count_of_projected_story_with_case_in_product';
UPDATE `zt_metric` SET `alias` = '关闭研发需求数'             WHERE `code` = 'count_of_monthly_closed_story_in_product';
UPDATE `zt_metric` SET `alias` = '新增研发需求数'             WHERE `code` = 'count_of_monthly_created_story_in_product';
UPDATE `zt_metric` SET `alias` = '研发需求规模总数'           WHERE `code` = 'scale_of_story_in_product';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_annual_finished_story_in_product';
UPDATE `zt_metric` SET `alias` = '交付研发需求规模数'         WHERE `code` = 'scale_of_annual_delivered_story_in_product';
UPDATE `zt_metric` SET `alias` = '关闭研发需求规模数'         WHERE `code` = 'scale_of_annual_closed_story_in_product';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_monthly_finished_story_in_product';
UPDATE `zt_metric` SET `alias` = '研发需求评审通过率'         WHERE `code` = 'rate_of_approved_story_in_product';
UPDATE `zt_metric` SET `alias` = '研发需求完成率'             WHERE `code` = 'rate_of_finish_story_in_product';
UPDATE `zt_metric` SET `alias` = '研发需求交付率'             WHERE `code` = 'rate_of_delivery_story_in_product';
UPDATE `zt_metric` SET `alias` = '用户需求总数'               WHERE `code` = 'count_of_requirement_in_product';
UPDATE `zt_metric` SET `alias` = '新增用户需求数'             WHERE `code` = 'count_of_annual_created_requirement_in_product';
UPDATE `zt_metric` SET `alias` = '研发完毕研需规模的Bug密度'  WHERE `code` = 'bug_concentration_of_developed_story_in_product';
UPDATE `zt_metric` SET `alias` = 'Bug总数'                    WHERE `code` = 'count_of_bug_in_product';
UPDATE `zt_metric` SET `alias` = '激活Bug数'                  WHERE `code` = 'count_of_activated_bug_in_product';
UPDATE `zt_metric` SET `alias` = '有效Bug数'                  WHERE `code` = 'count_of_effective_bug_in_product';
UPDATE `zt_metric` SET `alias` = '已修复Bug数'                WHERE `code` = 'count_of_fixed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '严重程度为1级的Bug数'       WHERE `code` = 'count_of_severity_1_bug_in_product';
UPDATE `zt_metric` SET `alias` = '严重程度为2级的Bug数'       WHERE `code` = 'count_of_severity_2_bug_in_product';
UPDATE `zt_metric` SET `alias` = '严重程度为1、2级的Bug数'    WHERE `code` = 'count_of_severe_bug_in_product';
UPDATE `zt_metric` SET `alias` = '新增Bug数'                  WHERE `code` = 'count_of_annual_created_bug_in_product';
UPDATE `zt_metric` SET `alias` = '新增有效Bug数'              WHERE `code` = 'count_of_annual_created_effective_bug_in_product';
UPDATE `zt_metric` SET `alias` = '修复Bug数'                  WHERE `code` = 'count_of_annual_fixed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '新增Bug数'                  WHERE `code` = 'count_of_daily_created_bug_in_product';
UPDATE `zt_metric` SET `alias` = '解决Bug数'                  WHERE `code` = 'count_of_daily_resolved_bug_in_product';
UPDATE `zt_metric` SET `alias` = '关闭Bug数'                  WHERE `code` = 'count_of_daily_closed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '解决Bug数'                  WHERE `code` = 'count_of_monthly_fixed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '关闭Bug数'                  WHERE `code` = 'count_of_monthly_closed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '新增Bug数'                  WHERE `code` = 'count_of_monthly_created_bug_in_product';
UPDATE `zt_metric` SET `alias` = 'Bug修复率'                  WHERE `code` = 'rate_of_fixed_bug_in_product';
UPDATE `zt_metric` SET `alias` = '用例总数'                   WHERE `code` = 'count_of_case_in_product';
UPDATE `zt_metric` SET `alias` = '新增用例数'                 WHERE `code` = 'count_of_annual_created_case_in_product';
UPDATE `zt_metric` SET `alias` = '反馈总数'                   WHERE `code` = 'count_of_feedback_in_product';
UPDATE `zt_metric` SET `alias` = '新增反馈数'                 WHERE `code` = 'count_of_annual_created_feedback_in_product';
UPDATE `zt_metric` SET `alias` = '关闭反馈数'                 WHERE `code` = 'count_of_annual_closed_feedback_in_product';
UPDATE `zt_metric` SET `alias` = '计划工期'                   WHERE `code` = 'planned_period_of_project';
UPDATE `zt_metric` SET `alias` = '剩余工期'                   WHERE `code` = 'left_period_of_project';
UPDATE `zt_metric` SET `alias` = '实际工期'                   WHERE `code` = 'count_of_actual_time_in_project';
UPDATE `zt_metric` SET `alias` = '工期偏差'                   WHERE `code` = 'variance_of_time_in_project';
UPDATE `zt_metric` SET `alias` = '已关闭执行数'               WHERE `code` = 'count_of_closed_execution_in_project';
UPDATE `zt_metric` SET `alias` = '已挂起执行数'               WHERE `code` = 'count_of_suspended_execution_in_project';
UPDATE `zt_metric` SET `alias` = '进行中执行数'               WHERE `code` = 'count_of_doing_execution_in_project';
UPDATE `zt_metric` SET `alias` = '未开始执行数'               WHERE `code` = 'count_wait_execution_in_project';
UPDATE `zt_metric` SET `alias` = '关闭执行数'                 WHERE `code` = 'count_annual_closed_execution_in_project';
UPDATE `zt_metric` SET `alias` = '执行总数'                   WHERE `code` = 'count_of_execution_in_project';
UPDATE `zt_metric` SET `alias` = '研发需求总数'               WHERE `code` = 'count_of_story_in_project';
UPDATE `zt_metric` SET `alias` = '已关闭研发需求数'           WHERE `code` = 'count_of_closed_story_in_project';
UPDATE `zt_metric` SET `alias` = '未关闭研发需求数'           WHERE `code` = 'count_of_unclosed_story_in_project';
UPDATE `zt_metric` SET `alias` = '已完成研发需求数'           WHERE `code` = 'count_of_finished_story_in_project';
UPDATE `zt_metric` SET `alias` = '无效研发需求数'             WHERE `code` = 'count_of_invalid_story_in_project';
UPDATE `zt_metric` SET `alias` = '有效研发需求数'             WHERE `code` = 'count_of_valid_story_in_project';
UPDATE `zt_metric` SET `alias` = '所有研发需求规模数'         WHERE `code` = 'scale_of_story_in_project';
UPDATE `zt_metric` SET `alias` = '完成研发需求数'             WHERE `code` = 'count_of_annual_finished_story_in_project';
UPDATE `zt_metric` SET `alias` = '完成研发需求规模数'         WHERE `code` = 'scale_of_annual_finished_story_in_project';
UPDATE `zt_metric` SET `alias` = '研发需求完成率'             WHERE `code` = 'rate_of_finished_story_in_project';
UPDATE `zt_metric` SET `alias` = '任务总数'                   WHERE `code` = 'count_of_task_in_project';
UPDATE `zt_metric` SET `alias` = '未开始任务数'               WHERE `code` = 'count_of_wait_task_in_project';
UPDATE `zt_metric` SET `alias` = '进行中任务数'               WHERE `code` = 'count_of_doing_task_in_project';
UPDATE `zt_metric` SET `alias` = '已完成任务数'               WHERE `code` = 'count_of_finished_task_in_project';
UPDATE `zt_metric` SET `alias` = '任务预计工时数'             WHERE `code` = 'estimate_of_task_in_project';
UPDATE `zt_metric` SET `alias` = '任务消耗工时数'             WHERE `code` = 'consume_of_task_in_project';
UPDATE `zt_metric` SET `alias` = '任务剩余工时数'             WHERE `code` = 'left_of_task_in_project';
UPDATE `zt_metric` SET `alias` = '已完成任务工作的预计工时'   WHERE `code` = 'ev_of_finished_task_in_waterfall';
UPDATE `zt_metric` SET `alias` = '任务计划完成工时'           WHERE `code` = 'pv_of_task_in_waterfall';
UPDATE `zt_metric` SET `alias` = '任务进度'                   WHERE `code` = 'progress_of_task_in_project';
UPDATE `zt_metric` SET `alias` = '进度偏差率'                 WHERE `code` = 'sv_in_waterfall';
UPDATE `zt_metric` SET `alias` = '成本偏差率'                 WHERE `code` = 'cv_in_waterfall';
UPDATE `zt_metric` SET `alias` = 'Bug总数'                    WHERE `code` = 'count_of_bug_in_project';
UPDATE `zt_metric` SET `alias` = '激活Bug数'                  WHERE `code` = 'count_of_activated_bug_in_project';
UPDATE `zt_metric` SET `alias` = '已关闭Bug数'                WHERE `code` = 'count_of_closed_bug_in_project';
UPDATE `zt_metric` SET `alias` = '人员总数'                   WHERE `code` = 'count_of_user_in_project';
UPDATE `zt_metric` SET `alias` = '所有消耗工时数'             WHERE `code` = 'consume_of_all_in_project';
UPDATE `zt_metric` SET `alias` = '已投入人天'                 WHERE `code` = 'day_of_invested_in_project';
UPDATE `zt_metric` SET `alias` = '瀑布项目实际花费工时'       WHERE `code` = 'ac_of_all_in_waterfall';
UPDATE `zt_metric` SET `alias` = '开放的风险数'               WHERE `code` = 'count_of_opened_risk_in_project';
UPDATE `zt_metric` SET `alias` = '开放的问题数'               WHERE `code` = 'count_of_opened_issue_in_project';
UPDATE `zt_metric` SET `alias` = '研发需求总数'               WHERE `code` = 'count_of_story_in_execution';
UPDATE `zt_metric` SET `alias` = '已完成研发需求数'           WHERE `code` = 'count_of_finished_story_in_execution';
UPDATE `zt_metric` SET `alias` = '无效研发需求数'             WHERE `code` = 'count_of_invalid_story_in_execution';
UPDATE `zt_metric` SET `alias` = '有效研发需求数'             WHERE `code` = 'count_of_valid_story_in_execution';
UPDATE `zt_metric` SET `alias` = '研发完成的研发需求数'       WHERE `code` = 'count_of_developed_story_in_execution';
UPDATE `zt_metric` SET `alias` = '研发需求完成率'             WHERE `code` = 'rate_of_finished_story_in_execution';
UPDATE `zt_metric` SET `alias` = '研发完成需求占比'           WHERE `code` = 'rate_of_developed_story_in_execution';
UPDATE `zt_metric` SET `alias` = '任务总数'                   WHERE `code` = 'count_of_task_in_execution';
UPDATE `zt_metric` SET `alias` = '已完成任务数'               WHERE `code` = 'count_of_finished_task_in_execution';
UPDATE `zt_metric` SET `alias` = '未完成任务数'               WHERE `code` = 'count_of_unfinished_task_in_execution';
UPDATE `zt_metric` SET `alias` = '完成任务数'                 WHERE `code` = 'count_of_daily_finished_task_in_execution';
UPDATE `zt_metric` SET `alias` = '任务预计工时数'             WHERE `code` = 'estimate_of_task_in_execution';
UPDATE `zt_metric` SET `alias` = '任务消耗工时数'             WHERE `code` = 'consume_of_task_in_execution';
UPDATE `zt_metric` SET `alias` = '任务剩余工时数'             WHERE `code` = 'left_of_task_in_execution';
UPDATE `zt_metric` SET `alias` = '任务进度'                   WHERE `code` = 'progress_of_task_in_execution';
UPDATE `zt_metric` SET `alias` = '待评审研发需求数'           WHERE `code` = 'count_of_reviewing_story_in_user';
UPDATE `zt_metric` SET `alias` = '评审研发需求数'             WHERE `code` = 'count_of_daily_review_story_in_user';
UPDATE `zt_metric` SET `alias` = '待处理研发需求数'           WHERE `code` = 'count_of_pending_story_in_user';
UPDATE `zt_metric` SET `alias` = '完成任务数'                 WHERE `code` = 'count_of_daily_finished_task_in_user';
UPDATE `zt_metric` SET `alias` = '待处理任务数'               WHERE `code` = 'count_of_assigned_task_in_user';
UPDATE `zt_metric` SET `alias` = '解决Bug数'                  WHERE `code` = 'count_of_daily_fixed_bug_in_user';
UPDATE `zt_metric` SET `alias` = '待处理Bug数'                WHERE `code` = 'count_of_assigned_bug_in_user';
UPDATE `zt_metric` SET `alias` = '待处理用例数'               WHERE `code` = 'count_of_assigned_case_in_user';
UPDATE `zt_metric` SET `alias` = '待处理反馈数'               WHERE `code` = 'count_of_assigned_feedback_in_user';
UPDATE `zt_metric` SET `alias` = '待评审反馈数'               WHERE `code` = 'count_of_reviewing_feedback_in_user';
UPDATE `zt_metric` SET `alias` = '评审反馈数'                 WHERE `code` = 'count_of_daily_review_feedback_in_user';
UPDATE `zt_metric` SET `alias` = '完成执行数'                 WHERE `code` = 'count_of_annual_finished_execution';

UPDATE `zt_metric` SET `name` = 'count_of_actual_time_in_project' WHERE `code` = '按项目统计的实际工期';
UPDATE `zt_metric` SET `name` = 'variance_of_time_in_project'     WHERE `code` = '按项目统计的工期偏差';
UPDATE `zt_metric` SET `dateType` = 'nodate' WHERE `code` = 'count_of_feedback_in_product';

ALTER TABLE `zt_story` ADD COLUMN `unlinkReason` ENUM('', 'omit', 'other') NOT NULL DEFAULT '';

UPDATE `zt_stage` SET `name` = '生命周期' WHERE `type` = 'lifecycle' AND `projectType` = 'ipd';

ALTER TABLE `zt_relationoftasks` DROP INDEX `relationoftasks`;
ALTER TABLE `zt_relationoftasks` ADD INDEX `relationoftasks`(`execution` ASC, `task` ASC);
