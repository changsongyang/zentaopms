<?php
$lang->metric->common        = "Metric";
$lang->metric->name          = "Name";
$lang->metric->stage         = "Stage";
$lang->metric->scope         = "Scope";
$lang->metric->object        = "Object";
$lang->metric->purpose       = "Purpose";
$lang->metric->unit          = "Unit";
$lang->metric->code          = "Metric Code";
$lang->metric->desc          = "Description";
$lang->metric->definition    = "Definition";
$lang->metric->formula       = "Formula";
$lang->metric->when          = "Collection Method";
$lang->metric->createdBy     = "Created By";
$lang->metric->implement     = "Implement";
$lang->metric->implementedBy = "Implemented By";
$lang->metric->offlineBy     = "Offline By";
$lang->metric->lastEdited    = "Last Edited";
$lang->metric->value         = "Value";
$lang->metric->date          = "Date";
$lang->metric->metricData    = "Metric Data";
$lang->metric->system        = "System";
$lang->metric->weekCell      = "Week %s of Year %s";
$lang->metric->create        = "Create " . $this->lang->metric->common;
$lang->metric->afterCreate   = "After Saving";
$lang->metric->definition    = "Definition";
$lang->metric->customUnit    = "Custom Unit";

$lang->metric->descTip       = 'Enter the meaning, purpose, and impact of the metric';
$lang->metric->definitionTip = 'Enter the calculation rules and filtering conditions of the metric';

$lang->metric->noDesc    = "No description available";
$lang->metric->noFormula = "No calculation rules available";
$lang->metric->noCalc    = "The PHP algorithm for this metric has not been implemented yet";

$lang->metric->legendBasicInfo  = 'Basic Information';
$lang->metric->legendCreateInfo = 'Creation and Editing Information';

$lang->metric->confirmDelete = "Are you sure you want to delete?";

$lang->metric->browse          = 'Browse Metrics';
$lang->metric->browseAction    = 'Metric List';
$lang->metric->viewAction      = 'View Metric';
$lang->metric->editAction      = 'Edit Metric';
$lang->metric->implementAction = 'Implement Metric';
$lang->metric->deleteAction    = 'Delete Metric';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "Not Released";
$lang->metric->stageList['released'] = "Released";

$lang->metric->featureBar['browse']['all']      = 'All';
$lang->metric->featureBar['browse']['wait']     = 'Not Released';
$lang->metric->featureBar['browse']['released'] = 'Released';

$lang->metric->unitList = array();
$lang->metric->unitList['single']  = 'Single';
$lang->metric->unitList['measure'] = 'Man-hour';
$lang->metric->unitList['hour']    = 'Hour';
$lang->metric->unitList['day']     = 'Day';
$lang->metric->unitList['manday']  = 'Man-day';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']   = 'Back to List Page';
$lang->metric->afterCreateList['finish'] = 'Implement Metric';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = 'Year';
$lang->metric->dateList['month'] = 'Month';
$lang->metric->dateList['week']  = 'Week';
$lang->metric->dateList['day']   = 'Day';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "Scale Estimation";
$lang->metric->purposeList['time']  = "Time Control";
$lang->metric->purposeList['cost']  = "Cost Calculation";
$lang->metric->purposeList['hour']  = "Hourly Statistics";
$lang->metric->purposeList['qc']    = "Quality Control";
$lang->metric->purposeList['rate']  = "Efficiency Improvement";

$lang->metric->scopeList = array();
$lang->metric->scopeList['system']    = "System";
$lang->metric->scopeList['program']   = "Program Set";
$lang->metric->scopeList['product']   = "Product";
$lang->metric->scopeList['project']   = "Project";
$lang->metric->scopeList['execution'] = "Execution";
$lang->metric->scopeList['dept']      = "Team";
$lang->metric->scopeList['user']      = "Individual";
$lang->metric->scopeList['code']      = "Code Repository";
$lang->metric->scopeList['pipeline']  = "Pipeline";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']     = "Program Set";
$lang->metric->objectList['line']        = "Product Line";
$lang->metric->objectList['product']     = "Product";
$lang->metric->objectList['project']     = "Project";
$lang->metric->objectList['productplan'] = "Plan";
$lang->metric->objectList['execution']   = "Execution";
$lang->metric->objectList['release']     = "Release";
$lang->metric->objectList['story']       = "Development Requirement";
$lang->metric->objectList['requirement'] = "User Requirement";
$lang->metric->objectList['task']        = "Task";
$lang->metric->objectList['bug']         = "Bug";
$lang->metric->objectList['case']        = "Test Case";
$lang->metric->objectList['user']        = "User";
$lang->metric->objectList['effort']      = "Effort";
$lang->metric->objectList['doc']         = "Document";
if($config->edition != 'open')
{
    $lang->metric->objectList['feedback']    = "Feedback";
    $lang->metric->objectList['risk']        = "Risk";
    $lang->metric->objectList['issue']       = "Issue";
}

$lang->metric->implementInstructions = "Implementation Instructions";
$lang->metric->implementTips = array();
$lang->metric->implementTips[] = '1. Download the metric template code.php. Note: The file name should be consistent with the metric code.';
$lang->metric->implementTips[] = '2. Perform coding and development operations on the file, referring to the manual for instructions.';
$lang->metric->implementTips[] = '3. Place the developed code.php file in the [User Zentao Directory]/tmp/metric.php directory.';
$lang->metric->implementTips[] = '4. Execute the command to grant executable permissions to the file.';
