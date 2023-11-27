<?php
/**
 * The model file of screen module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-02-08 17:15:54Z $
 * @link        https://www.zentao.net
 */
class screenModel extends model
{
    /**
     * Filters
     *
     * @var object
     * @access public
     */
    public $filter;

    /**
     * 初始化函数。
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadBIDAO();

        $this->filter = new stdclass();
        $this->filter->screen  = '';
        $this->filter->year    = '';
        $this->filter->dept    = '';
        $this->filter->account = '';
        $this->filter->charts  = array();
    }

    /**
     * 获取大屏列表。
     * Get screen list.
     *
     * @param  int    $dimensionID
     * @access public
     * @return array
     */
    public function getList(int $dimensionID): array
    {
        return $this->dao->select('*')->from(TABLE_SCREEN)->where('dimension')->eq($dimensionID)->andWhere('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * 通过id获取大屏信息。
     * Get screen by id.
     *
     * @param  int         $screenID
     * @param  int         $year
     * @param  int         $dept
     * @param  string      $account
     * @access public
     * @return object|bool
     */
    public function getByID(int $screenID, int $year = 0, int $dept = 0, string $account = ''): object|bool
    {
        $screen = $this->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq($screenID)->fetch();
        if($screen->id == 5) return $screen;
        if(!$screen) return false;

        if(empty($screen->scheme)) $screen->scheme = file_get_contents(__DIR__ . '/json/screen.json');
        $screen->chartData = $this->genChartData($screen, $year, $dept, $account);

        return $screen;
    }

    /**
     * 构建大屏图表数据。
     * Generate chartData of screen.
     *
     * @param  object $screen
     * @param  int    $year
     * @param  int    $dept
     * @param  string $account
     * @access public
     * @return object
     */
    public function genChartData(object $screen, int $year, int $dept, string $account): object
    {
        $this->filter = new stdclass();
        $this->filter->screen  = $screen->id;
        $this->filter->year    = $year;
        $this->filter->dept    = $dept;
        $this->filter->account = $account;
        $this->filter->charts  = array();

        if(!$screen->builtin || in_array($screen->id, $this->config->screen->builtinScreen)) return $this->genNewChartData($screen, $year, $dept, $account);

        $config = new stdclass();
        $config->width            = 1300;
        $config->height           = 1080;
        $config->filterShow       = false;
        $config->hueRotate        = 0;
        $config->saturate         = 1;
        $config->contrast         = 1;
        $config->brightness       = 1;
        $config->opacity          = 1;
        $config->rotateZ          = 0;
        $config->rotateX          = 0;
        $config->rotateY          = 0;
        $config->skewX            = 0;
        $config->skewY            = 0;
        $config->blendMode        = 'normal';
        $config->background       = '#001028';
        $config->selectColor      = true;
        $config->chartThemeColor  = 'dark';
        $config->previewScaleType = 'scrollY';

        $componentList = json_decode($screen->scheme);
        if(empty($componentList)) $componentList = array();

        /* Reset height of canvas. */
        foreach($componentList as $component)
        {
            if(!isset($component->attr)) continue;

            $height = $component->attr->y + $component->attr->h;
            if($height > $config->height) $config->height = $height;
        }
        $config->height += 50;

        $chartData = new stdclass();
        $chartData->editCanvasConfig    = $config;
        $chartData->componentList       = $this->buildComponentList($componentList);
        $chartData->requestGlobalConfig =  json_decode('{ "requestDataPond": [], "requestOriginUrl": "", "requestInterval": 30, "requestIntervalUnit": "second", "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

        return $chartData;
    }

    /**
     * 为新的大屏构建图表数据。
     * Generate chartData of new screen.
     *
     * @param  object $screen
     * @access public
     * @return object
     */
    public function genNewChartData(object $screen): object
    {
        $scheme = json_decode($screen->scheme);
        foreach($scheme->componentList as $component)
        {
            $list = !empty($component->isGroup) ? $component->groupList : array($component);
            foreach($list as $groupComponent)
            {
                if(isset($groupComponent->key) && $groupComponent->key === 'Select') $groupComponent = $this->buildSelect($groupComponent);
            }
        }

        /** Fileter chart. */
        $list = array();
        array_map(function($component)use(&$list){
            !empty($component->isGroup) ? array_merge($list, $component->groupList) : array_push($list, $component);
        }, array_filter($scheme->componentList));
        foreach($list as $component) $this->getLatestChart($component);

        return $scheme;
    }

    /**
     * 获取最新的图表。
     * Get the latest chart.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function getLatestChart(object $component): void
    {
        if(isset($component->key) && $component->key === 'Select') return;
        $chartID = zget($component->chartConfig, 'sourceID', '');
        if(!$chartID) return;

        $type  = $component->chartConfig->package == 'Tables' ? 'pivot' : 'chart';
        $table = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $chart = $this->dao->select('*')->from($table)->where('id')->eq($chartID)->fetch();

        $this->genComponentData($chart, $type, $component);
    }

    /**
     * 构建组件数据。
     * Generate a component of screen.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @param  string $filters
     * @access public
     * @return void
     */
    public function genComponentData(object $chart, string $type = 'chart', object $component = null, string $filters = ''): void
    {
        $chart = clone($chart);
        if($type == 'pivot' && $chart)
        {
            $chart = $this->loadModel('pivot')->processPivot($chart);
            $chart->settings = json_encode($chart->settings);
        }

        if(empty($filters) && !empty($chart->filters))
        {
            $params = array(json_decode($chart->filters, true));
            if($type == 'pivot') array_unshift($params, $chart->sql);
            $result = call_user_func_array(array($this->loadModel($type), 'getFilterFormat'), $params);

            list($chart->sql, $filters) = isset($result[0]) ? $result : array($chart->sql, $result);
        }

        list($component) = $this->initComponent($chart, $type, $component);
        $this->completeComponent($chart, $type, $filters, $component);
    }

    /**
     * 补充组件信息。
     * Complete component info.
     *
     * @param  object $chart
     * @param  string $type
     * @param  array  $filters
     * @param  object $component
     * @access public
     * @return void
     */
    public function completeComponent(object $chart, string $type, array $filters, object $component): void
    {
        if(empty($chart) || ($chart->stage == 'draft' || $chart->deleted == '1'))
        {
            $this->completeComponentShowInfo($chart, $component, $type);
            return;
        }

        $this->getChartOption($chart, $component, $filters);

        $component->chartConfig->dataset  = $component->option->dataset;
        $component->chartConfig->fields   = json_decode($chart->fields);
        $component->chartConfig->filters  = $this->getChartFilters($chart);

        if($type == 'chart' && (!$chart->builtin || in_array($chart->id, $this->config->screen->builtinChart)))
        {
            if(!empty($component->option->series))
            {
                $defaultSeries = $component->option->series;
                if($component->type == 'radar')
                {
                    $component->option->radar->indicator = $component->option->dataset->radarIndicator;
                    $defaultSeries[0]->data = $component->option->dataset->seriesData;

                    $component->option->legend->data = array_map(function($item){return $item->name;}, $component->option->dataset->seriesData);
                }
                else
                {
                    $component->option->series = array_pad([], count($component->option->dataset->dimensions), $defaultSeries[0]);
                }
            }
        }
    }

    /**
     * 补充组件展示信息。
     * Complete component chart info.
     *
     * @param  object $chart
     * @param  object $component
     * @param  string $type
     * @access public
     * @return void
     */
    private function completeComponentShowInfo(object $chart, object $component, string $type): void
    {
        $component->option = new stdclass();
        if($type == 'chart') $this->completeChartShowInfo($chart, $component);
        if($type == 'pivot') $this->completePivotShowInfo($chart, $component);
    }

    /**
     * 补充图表展示信息。
     * Complete chart show info.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return void
     */
    private function completeChartShowInfo(object $chart, object $component): void
    {
        $component->option->title = new stdclass();
        $component->option->title->text = sprintf($this->lang->screen->noChartData, $chart->name);
        $component->option->title->left = 'center';
        $component->option->title->top  = '50%';

        $component->option->xAxis = new stdclass();
        $component->option->xAxis->show = false;
        $component->option->yAxis = new stdclass();
        $component->option->yAxis->show = false;
    }

    /**
     * 补充透视表展示信息。
     * Complete pivot show info.
     *
     * @param  object $chart
     * @param  object $component
     * @access public
     * @return void
     */
    private function completePivotShowInfo(object $chart, object $component): void
    {
        $component->option->ineffective = 1;
        $component->option->header      = array();
        $component->option->align       = array('center');
        $component->option->headerBGC   = 'transparent';
        $component->option->oddRowBGC   = 'transparent';
        $component->option->evenRowBGC  = 'transparent';
        $component->option->columnWidth = array();
        $component->option->rowspan     = array();
        $component->option->colspan     = array();
        $component->option->rowNum      = 1;
        $component->option->dataset     = array(array(sprintf($this->lang->screen->noPivotData, $chart->name)));
    }

    /**
     * 获取图表配置。
     * Get chart option.
     *
     * @param  object        $chart
     * @param  object        $component
     * @param  array         $filters
     * @access public
     * @return void
     */
    public function getChartOption(object $chart, object $component, array $filters): void
    {
        $type = $component->type ? : 'default';
        switch($type)
        {
            case 'line':
                $this->getLineChartOption($component, $chart, $filters);
                break;
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
            case 'bar':
                $this->getBarChartOption($component, $chart, $filters);
                break;
            case 'piecircle':
                $this->buildPieCircleChart($component, $chart);
                break;
            case 'pie':
                $this->getPieChartOption($component, $chart, $filters);
                break;
            case 'table':
                $this->getTableChartOption($component, $chart, $filters);
                break;
            case 'radar':
                $this->getRadarChartOption($component, $chart, $filters);
                break;
            case 'card':
                $this->buildCardChart($component, $chart);
                break;
            case 'waterpolo':
                $this->buildWaterPolo($component, $chart);
                break;
            default:
                break;
        }
    }

    /**
     * 获取条形图配置。
     * Get bar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getBarChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true);
            $settings = current($settings);

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);

            $fields       = json_decode($chart->fields);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                array_push($dimensions, $field);

                foreach($dataList as $valueField => $value)
                {
                    $valueField = $xLabelValues[$valueField];
                    if(empty($sourceData[$valueField]))
                    {
                        $sourceData[$valueField] = new stdclass();
                        $sourceData[$valueField]->{$settings['xaxis'][0]['field']} = $valueField;
                    }
                    $sourceData[$valueField]->{$field} = $value;
                }
            }
            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 获取折线图配置。
     * Get line chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getLineChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs, true);
            $settings = current($settings);

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);

            $fields       = json_decode($chart->fields);
            $dimensions   = array($settings['xaxis'][0]['field']);
            $sourceData   = array();
            $clientLang   = $this->app->getClientLang();
            $xLabelValues = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';
                $dimensions[] = $field;

                foreach($dataList as $valueField => $value)
                {
                    $valueField = $xLabelValues[$valueField];
                    if(empty($sourceData[$valueField]))
                    {
                        $sourceData[$valueField] = new stdclass();
                        $sourceData[$valueField]->{$settings['xaxis'][0]['field']} = $valueField;
                    }
                    $sourceData[$valueField]->{$field} = $value;
                }
            }

            /* Completing empty values. */
            foreach($sourceData as $lineData)
            {
                foreach($dimensions as $dimension)
                {
                    if(empty($lineData->{$dimension})) $lineData->{$dimension} = 0;
                }
            }

            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 获取饼图配置。
     * Get pie chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getPieChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $settings = current($settings);

            $options = $this->loadModel('chart')->genPie(json_decode($chart->fields, true), $settings, $chart->sql, $filters);
            $groupField = $settings['group'][0]['field'];
            $metricField = $settings['metric'][0]['field'];

            if($groupField == $metricField) $groupField .= '1';
            $dimensions = array($groupField, $metricField);
            $sourceData = array();
            foreach($options['series'] as $dataList)
            {
                $field = $settings['metric'][0]['field'];
                foreach($dataList['data'] as $data)
                {
                    $fieldValue = $data['name'];
                    if(empty($sourceData[$fieldValue]))
                    {
                        $sourceData[$fieldValue] = new stdclass();
                        $sourceData[$fieldValue]->{$groupField} = (string)$fieldValue;
                    }
                    $sourceData[$fieldValue]->{$field} = $data['value'];
                }
            }

            if(empty($sourceData)) $dimensions = array();

            $component->option->dataset->dimensions = $dimensions;
            $component->option->dataset->source     = array_values($sourceData);
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 获取雷达图配置。
     * Get radar chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getRadarChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $langs    = json_decode($chart->langs,    true);
            $settings = current($settings);

            list($group, $metrics, $aggs, $xLabels, $yStats) = $this->loadModel('chart')->getMultiData($settings, $chart->sql, $filters);

            $fields         = json_decode($chart->fields);
            $radarIndicator = array();
            $seriesData     = array();
            $max            = 0;
            $clientLang     = $this->app->getClientLang();
            $xLabelValues   = $this->processXLabel($xLabels, $fields->{$group}->type, $fields->{$group}->object, $fields->{$group}->field);

            foreach($yStats as $index => $dataList)
            {
                $fieldConfig = zget($fields, $metrics[$index]);
                $fieldName   = $langs[$fieldConfig->field][$clientLang] ?? $fieldConfig->name;
                $field       = $fieldName . '(' . zget($this->lang->chart->aggList, $aggs[$index]) . ')';

                $seriesData[$index] = new stdclass();
                $seriesData[$index]->name = $field;

                $values = array_map(function($value){return (float)$value;}, $dataList);
                $max = max($values);
                $seriesData[$index]->value = $values;
            }

            if(!empty($dataList))
            {
                foreach(array_keys($dataList) as $valueField)
                {
                    $indicator = new stdclass();
                    $indicator->name   = $xLabelValues[$valueField];
                    $indicator->max    = $max;
                    $radarIndicator[]  = $indicator;
                }
            }
            $component->option->dataset->radarIndicator = $radarIndicator;
            $component->option->dataset->seriesData     = $seriesData;
        }

        $this->setComponentDefaults($component);
    }


    /**
     * Get table chart option.
     *
     * @param  object $component
     * @param  object $chart
     * @param  array  $filters
     * @access public
     * @return void
     */
    public function getTableChartOption(object $component, object $chart, array $filters = array()): void
    {
        if($chart->sql)
        {
            $settings = json_decode($chart->settings, true);
            $fields   = json_decode($chart->fields,   true);
            $langs    = json_decode($chart->langs,    true);
            list($options, $config) = $this->loadModel('pivot')->genSheet($fields, $settings, $chart->sql, $filters, $langs);

            $colspan = array();
            if($options->columnTotal && $options->columnTotal == 'sum' && !empty($options->array))
            {
                $optionsData = $options->array;
                $count       = count($optionsData);
                foreach($optionsData as $index => $data)
                {
                    if($index == ($count - 1))
                    {
                        $newData = array('total' => $this->lang->pivot->step2->total);
                        foreach($options->groups as $field) unset($data[$field]);
                        $newData += $data;
                        $optionsData[$index] = $newData;
                    }
                }
                $options->array = $optionsData;
                $colspan[$count - 1][0] = count($options->groups);
            }

            $dataset = array_map(function($data){return array_values($data);}, $options->array);

            foreach($config as $i => $data)
            {
                foreach($data as $j => $rowspan)
                {
                    for($k = 1; $k < $rowspan; $k ++) unset($dataset[$i + $k][$j]);
                }
            }

            $this->setComponentTableInfo($component, $options->cols, $dataset, $config, $colspan);
        }

        $this->setComponentDefaults($component);
    }

    /**
     * 设置组件表格信息。
     * Set component table info.
     *
     * @param  object  $component
     * @param  array   $cols
     * @param  array   $dataset
     * @param  array   $config
     * @param  array   $colspan
     * @access private
     * @return void
     */
    public function setComponentTableInfo(object $component, array $cols, array $dataset, array $config, array $colspan): void
    {
        $align = array_map(function(){return 'center';}, current($cols));

        if(!isset($component->chartConfig->tableInfo)) $component->chartConfig->tableInfo = new stdclass();
        $component->option->header      = $component->chartConfig->tableInfo->header      = $cols;
        $component->option->align       = $component->chartConfig->tableInfo->align       = $align;
        $component->option->columnWidth = $component->chartConfig->tableInfo->columnWidth = array();
        $component->option->rowspan     = $component->chartConfig->tableInfo->rowspan     = $config;
        $component->option->colspan     = $component->chartConfig->tableInfo->colspan     = $colspan;
        $component->option->dataset     = $dataset;
    }

    /**
     * Get chart filters
     *
     * @param object $chart
     * @access public
     * @return array
     */
    public function getChartFilters(object $chart): array
    {
        $filters = json_decode($chart->filters, true);
        $fields  = json_decode($chart->fields,  true);

        return !empty($filters) ? array_map(function($filter)use($fields, $chart){
            $isQuery = (isset($filter['from']) && $filter['from'] == 'query');
            if($isQuery) $this->setIsQueryScreenFilters($filter);
            if(!$isQuery && ($filter['type'] == 'date' || $filter['type'] == 'datetime')) $this->setDefaultByDate($filter);
            if(!$isQuery && $filter['type'] == 'select')
            {
                $field = zget($fields, $filter['field']);
                $options = $this->getSysOptions($field['type'], $field['object'], $field['field'], $chart->sql);
                $filter['options'] = array_map(function($item, $index){return array('label' => $item, 'value' => $index);}, $options, array_keys($options));
            }

            return $filter;
        }, $filters) : array();
    }

    /**
     * Set screen filters when is query.
     *
     * @param  array  $filter
     * @access public
     * @return void
     */
    public function setIsQueryScreenFilters(array &$filter): void
    {
        if($filter['type'] == 'date' || $filter['type'] == 'datetime')
        {
            if(isset($filter['default']))
            {
                $default = $this->loadModel('pivot')->processDateVar($filter['default']);
                $filter['default'] = empty($default) ? null : strtotime($default) * 1000;
            }
        }

        if($filter['type'] == 'select')
        {
            $options = $this->getSysOptions($filter['typeOption']);
            $filter['options'] = array_map(function($item, $index){return array('label' => $item, 'value' => $index);}, $options, array_keys($options));
        }
    }

    /**
     * Set default by date.
     *
     * @param  array  $filter
     * @access public
     * @return void
     */
    public function setDefaultByDate(array &$filter): void
    {
        $filter['default'] = null;

        if(isset($filter['default']))
        {
            extract($filter['default']);
            if(empty($begin) || empty($end))   $filter['default'] = empty($begin) ? strtotime($end) * 1000 : strtotime($begin) * 1000;
            if(!empty($begin) || !empty($end)) $filter['default'] = array(strtotime($begin) * 1000, strtotime($end) * 1000);
        }
    }

    /**
     * Process xLabel with lang
     *
     * @param  array  $xLabel
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function processXLabel(array $xLabels, string $type, string $object, string $field): array
    {
        $options = $this->getSysOptions($type, $object, $field);
        $xLabelValues = array();
        foreach($xLabels as $label) $xLabelValues[$label] = isset($options[$label]) ? $options[$label] : $label;

        return $xLabelValues;
    }

    /**
     * Get system options.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getSysOptions(string $type, string $object = '', string $field = '', string $sql = ''): array
    {
        $options = array();
        switch($type)
        {
            case 'user':
                $options = $this->loadModel('user')->getPairs();
                break;
            case 'product':
                $options = $this->loadModel('product')->getPairs();
                break;
            case 'project':
                $options = $this->loadModel('project')->getPairsByProgram();
                break;
            case 'execution':
                $options = $this->loadModel('execution')->getPairs();
                break;
            case 'dept':
                $options = $this->loadModel('dept')->getOptionMenu(0);
                break;
            case 'project.status':
                $this->app->loadLang('project');
                $options = $this->lang->project->statusList;
                break;
            case 'option':
                if($field)
                {
                    $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
                    if(is_file($path))
                    {
                        include $path;
                        $options = $schema->fields[$field]['options'];
                    }
                }
                break;
            case 'object':
                if($field)
                {
                    $table = zget($this->config->objectTables, $object, '');
                    if($table) $options = $this->dao->select("id, {$field}")->from($table)->fetchPairs();
                }
                break;
            default:
                if($field && $sql)
                {
                    $options = $this->dao->select("tt.`$field`,tt.`$field`")
                        ->from("($sql)")->alias('tt')
                        ->groupBy("tt.`$field`")
                        ->orderBy("tt.`$field` desc")
                        ->fetchPairs();
                }
                break;

        }

        return array_filter($options);
    }

    /**
     * Build component list.
     *
     * @param  array  $componentList
     * @access public
     * @return array
     */
    public function buildComponentList(array $componentList): array
    {
        return array_map(function($component){$this->buildComponent($component);return $component;}, array_filter($componentList));
    }

    /**
     * Build component.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildComponent(object $component): void
    {
        /* If chart is builtin, build it. */
        if(isset($component->sourceID) && $component->sourceID)
        {
            $this->buildChart($component);
        }
        elseif(isset($component->key) && $component->key === 'Select')
        {
            $this->buildSelect($component);
        }elseif(empty($component->isGroup))
        {
            $this->setComponentDefaults($component);
        }
        else
        {
            $component->groupList = $this->buildComponentList($component->groupList);
            $this->buildGroup($component);
        }
    }

    /**
     * Build chart group.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function buildGroup(object $component): void
    {
        $this->setComponentDefaults($component);
    }

    /**
     * Set component defaults.
     *
     * @param  object $component
     * @access public
     * @return object
     */
    public function setComponentDefaults(object $component): void
    {
        if(!isset($component->styles))  $component->styles  = json_decode('{"filterShow": false, "hueRotate": 0, "saturate": 1, "contrast": 1, "brightness": 1, "opacity": 1, "rotateZ": 0, "rotateX": 0, "rotateY": 0, "skewX": 0, "skewY": 0, "blendMode": "normal", "animations": []}');
        if(!isset($component->status))  $component->status  = json_decode('{"lock": false, "hide": false}');
        if(!isset($component->request)) $component->request = json_decode('{ "requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');
    }

    /**
     * Build select.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildSelect(object $component): void
    {
        switch($component->type)
        {
            case 'year':
                $component->option->value = $this->filter->year;

                $begin = $this->dao->select('YEAR(MIN(date)) year')->from(TABLE_ACTION)->where('date')->notZeroDate()->fetch('year');
                if($begin < 2009) $begin = 2009;

                $options = array();
                for($year = date('Y'); $year >= $begin; $year--) $options[] = array('label' => $year, 'value' => $year);
                $component->option->dataset = $options;

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen. "&year=' + value + '&dept=" . $this->filter->dept . "&account=" . $this->filter->account . "')";
                break;
            case 'dept':
                $component->option->value = (string)$this->filter->dept;

                $options = array(array('label' => $this->lang->screen->allDepts, 'value' => '0'));
                $depts = $this->dao->select('id,name')->from(TABLE_DEPT)->where('grade')->eq(1)->fetchAll();
                $component->option->dataset = array_map(function($dept)use(&$options){array_push($options, array('label' => $dept->name, 'value' => $dept->id));}, $depts);

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=' + value + '&account=')";
                break;
            case 'account':
                $component->option->value = $this->filter->account;

                $options = array(array('label' => $this->lang->screen->allUsers, 'value' => ''));
                $depts   = array();
                if($this->filter->dept) $depts = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like(',' . $this->filter->dept . ',%')->fetchPairs();
                $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('deleted')->eq(0)->beginIF($this->filter->dept)->andWhere('dept')->in($depts)->fi()->fetchAll();
                $component->option->dataset = array_map(function($user)use(&$options){array_push($options, array('label' => $user->realname, 'value' => $user->account));}, $users);

                $url = "createLink('screen', 'view', 'screenID=" . $this->filter->screen . "&year=" . $this->filter->year . "&dept=" . $this->filter->dept . "&account=' + value)";
                break;
        }

        if(isset($url)) $component->option->onChange = "window.location.href = {$url}";

        foreach($component->filterCharts as $chart)
        {
            if(!isset($this->filter->charts[$chart->chart])) $this->filter->charts[$chart->chart] = array();
            $this->filter->charts[$chart->chart][$component->type] = $chart->field;
        }

        $this->setComponentDefaults($component);
    }

    /**
     * Build chart.
     *
     * @param  object $component
     * @access public
     * @return void
     */
    public function buildChart(object $component): void
    {
        $chart = $this->dao->select('*')->from(TABLE_CHART)->where('id')->eq($component->sourceID)->fetch();
        switch($chart->type)
        {
            case 'card':
                $this->buildCardChart($component, $chart);
                break;
            case 'line':
                $this->buildLineChart($component, $chart);
                break;
            case 'bar':
                $this->buildBarChart($component, $chart);
                break;
            case 'piecircle':
                $this->buildPieCircleChart($component, $chart);
                break;
            case 'pie':
                $chart->builtin == '0' ? $this->getPieChartOption($component, $chart) : $this->buildPieChart($component, $chart);
                break;
            case 'radar':
                $this->buildRadarChart($component, $chart);
                break;
            case 'funnel':
                $this->buildFunnelChart($component, $chart);
                break;
            case 'table':
                $this->buildTableChart($component, $chart);
                break;
            case 'cluBarY':
            case 'stackedBarY':
            case 'cluBarX':
            case 'stackedBar':
                $this->getBarChartOption($component, $chart);
                break;
        }
    }

    /**
     * Set SQL filter
     *
     * @param  object $chart
     * @access public
     * @return string
     */
    public function setFilterSQL(object $chart): string
    {
        if(isset($this->filter->charts[$chart->id]))
        {
            $conditions = array();
            foreach($this->filter->charts[$chart->id] as $key => $field)
            {
                switch($key)
                {
                    case 'year':
                        $conditions[] = $field . " = '" . $this->filter->$key . "'";
                        break;
                    case 'dept':
                        if($this->filter->dept && !$this->filter->account)
                        {
                            $accountField = $this->filter->charts[$chart->id]['account'];
                            $users = $this->dao->select('account')->from(TABLE_USER)->alias('t1')
                                ->leftJoin(TABLE_DEPT)->alias('t2')
                                ->on('t1.dept = t2.id')
                                ->where('t2.path')->like(',' . $this->filter->dept . ',%')
                                ->fetchPairs('account');
                            $accounts = array_map(function($account){return "'" . $account . "'";}, $users);

                            $conditions[] = $accountField . ' IN (' . implode(',', $accounts) . ')';
                        }
                        break;
                    case 'account':
                        if($this->filter->account) $conditions[] = $field . " = '" . $this->filter->$key . "'";
                        break;
                }
            }

            if($conditions) return 'SELECT * FROM (' . str_replace(';', '', $chart->sql) . ') AS t1 WHERE ' . implode(' AND ', $conditions);
        }

        return $chart->sql;
    }

    /**
     * Build card chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildCardChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->option->dataset = '?';
        }
        else
        {
            $value = 0;
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->value))
                {
                    $field   = $settings->value->field;
                    $sql     = $this->setFilterSQL($chart);
                    $results = $this->dao->query($sql)->fetchAll();

                    if($settings->value->type === 'value') $value = count($results) ? 0 : current($results)->$field;
                    if($settings->value->agg  === 'count') $value = count($results);
                    if($settings->value->agg  === 'sum')
                    {
                        foreach($results as $result) $value += (float)$result->$field;
                        $value = round($value);
                    }
                }
                else
                {
                    $value = '?';
                }
            }
            $component->option->dataset = (string)$value;
        }

        $this->setComponentDefaults($component);
    }

    /**
     * Build line chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildLineChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "LineCommon";
            $component->chartConfig = json_decode('{"key":"LineCommon","chartKey":"VLineCommon","conKey":"VCLineCommon","title":"折线图","category":"Lines","categoryName":"折线图","package":"Charts","chartFrame":"echarts","image":"/static/png/line-e714bc74.png"}');
            $component->option      = json_decode('{"legend":{"show":true,"top":"5%","textStyle":{"color":"#B9B8CE"}},"xAxis":{"type":"category"},"yAxis":{"show":true,"axisLine":{"show":true},"type":"value"},"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    $sourceData = array();

                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;
                        $row   = array($key => $result->$field);

                        foreach($settings->yaxis as $yaxis)
                        {
                            $field = $yaxis->field;
                            $row[$yaxis->name] = $result->$field;
                        }
                        $sourceData[] = $row;
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = $sourceData;
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build table chart
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildTableChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "TableScrollBoard";
            $component->chartConfig = json_decode('{"key":"TableScrollBoard","chartKey":"VTableScrollBoard","conKey":"VCTableScrollBoard","title":"轮播列表","category":"Tables","categoryName":"表格","package":"Tables","chartFrame":"common","image":"/static/png/table_scrollboard-fb642e78.png"}');
            $component->option      = json_decode('{"header":["列1","列2","列3"],"dataset":[["行1列1","行1列2","行1列3"],["行2列1","行2列2","行2列3"],["行3列1","行3列2","行3列3"]],"rowNum":2,"waitTime":2,"headerHeight":35,"carousel":"single","headerBGC":"#00BAFF","oddRowBGC":"#003B51","evenRowBGC":"#0A2732"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->column))
                {
                    $header = $dataset = array();
                    foreach($settings->column as $column) $header[$column->field] = $column->name;

                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result) $dataset[] = array_map(function($field)use($result){return $result->$field;}, array_keys($header));

                    $component->option->header  = array_values($header);
                    $component->option->dataset = $dataset;
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build bar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildBarChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {}}}');
            $component->events      = json_decode('{"baseEvent": {}, "advancedEvents": {}}');
            $component->key         = "BarCrossrange";
            $component->chartConfig = json_decode('{"key": "BarCrossrange", "chartKey": "VBarCrossrange", "conKey": "VCBarCrossrange", "title": "横向柱状图", "category": "Bars", "categoryName": "柱状图", "package": "Charts", "chartFrame": "echarts", "image": "/static/png/bar_y-05067169.png" }');
            $component->option      = json_decode('{"xAxis": { "show": true, "type": "category" }, "yAxis": { "show": true, "axisLine": { "show": true }, "type": "value" }, "series": [], "backgroundColor": "rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->xaxis))
                {
                    $dimensions = array($settings->xaxis[0]->name);
                    foreach($settings->yaxis as $yaxis) $dimensions[] = $yaxis->name;

                    $sourceData = array();

                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    foreach($results as $result)
                    {
                        $key   = $settings->xaxis[0]->name;
                        $field = $settings->xaxis[0]->field;

                        if($settings->yaxis[0]->agg == 'sum')
                        {
                            if(!isset($sourceData[$result->$field])) $sourceData[$result->$field] = array($key => $result->$field);

                            foreach($settings->yaxis as $yaxis)
                            {
                                if(!isset($sourceData[$result->$field][$yaxis->name])) $sourceData[$result->$field][$yaxis->name] = 0;
                                $sourceData[$result->$field][$yaxis->name] += $result->{$yaxis->field};
                            }
                        }
                        else
                        {
                            $row = array($key => $result->$field);
                            foreach($settings->yaxis as $yaxis) $row[$yaxis->name] = $result->{$yaxis->field};
                            $sourceData[] = $row;
                        }
                    }

                    $component->option->dataset->dimensions = $dimensions;
                    $component->option->dataset->source     = array_values($sourceData);
                }
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build pie chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildPieChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCommon";
            $component->chartConfig = json_decode('{"key":"PieCommon","chartKey":"VPieCommon","conKey":"VCPieCommon","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-9620f191.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $sourceData = array();
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->metric))
                {
                    $dimensions = array($settings->group[0]->name, $settings->metric[0]->field);

                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    $group = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->$group])) $groupCount[$result->$group] = 0;
                            $groupCount[$result->$group]++;
                        }
                    }
                    arsort($groupCount);

                    foreach($groupCount as $groupValue => $groupCount) $sourceData[] = array($settings->group[0]->name => $groupValue, $settings->metric[0]->field => $groupCount);
                }
                if(empty($sourceData)) $dimensions = array();

                $component->option->dataset->dimensions = $dimensions;
                $component->option->dataset->source     = $sourceData;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build piecircle chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildPieCircleChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCircle";
            $component->chartConfig = json_decode('{"key":"PieCircle","chartKey":"VPieCircle","conKey":"VCPieCircle","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-circle-258fcce7.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $sourceData = array();
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->metric))
                {
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    $group   = $settings->group[0]->field;

                    $groupCount = array();
                    foreach($results as $result)
                    {
                        if($settings->metric[0]->agg == 'count')
                        {
                            if(!isset($groupCount[$result->$group])) $groupCount[$result->$group] = 0;
                            $groupCount[$result->$group]++;
                        }
                    }

                    foreach($groupCount as $groupValue => $groupCount) $sourceData[$groupValue] = $groupCount;
                }
                $doneData = round((array_sum($sourceData) != 0 && !empty($sourceData['done'])) ? $sourceData['done'] / array_sum($sourceData) : 0, 4);
                $component->option->dataset = $doneData;
                $component->option->series[0]->data[0]->value  = array($doneData);
                $component->option->series[0]->data[1]->value  = array(1 - $doneData);
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build water polo chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildWaterPolo(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "PieCircle";
            $component->chartConfig = json_decode('{"key":"WaterPolo","chartKey":"VWaterPolo","conKey":"VCWaterPolo","title":"水球图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"water_WaterPolo.png"}');
            $component->option      = json_decode('{"type":"nomal","series":[{"type":"liquidFill","radius":"90%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            if($chart->sql)
            {
                $settings   = json_decode($chart->settings);
                $sourceData = 0;
                if($settings && isset($settings->metric))
                {
                    $result     = $this->dao->query($this->setFilterSQL($chart))->fetch();
                    $group      = $settings->group[0]->field;
                    $sourceData = zget($result, $group, 0);
                }
                $component->option->dataset = $sourceData;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build radar chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return object
     */
    public function buildRadarChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "Radar";
            $component->chartConfig = json_decode('{"key":"Radar","chartKey":"VRadar","conKey":"VCRadar","title":"雷达图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"/static/png/radar-91567f95.png"}');
            $component->option      = json_decode('{"radar":{"indicator":[{"name":"数据1","max":6500},{"name":"数据2","max":16000},{"name":"数据3","max":30000},{"name":"数据4","max":38000},{"name":"数据5","max":52000}]},"series":[{"name":"radar","type":"radar","areaStyle":{"opacity":0.1},"data":[{"name":"data1","value":[4200,3000,20000,35000,50000]}]}],"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
        else
        {
            $indicator = $seriesData = array();
            if($chart->sql)
            {
                $settings = json_decode($chart->settings);
                if($settings && isset($settings->metric))
                {
                    $results = $this->dao->query($this->setFilterSQL($chart))->fetchAll();
                    $group   = $settings->group[0]->field;

                    $metrics = array();
                    foreach($settings->metric as $metric) $metrics[$metric->key] = array('field' => $metric->field, 'name' => $metric->name, 'value' => 0);

                    foreach($results as $result)
                    {
                        if(isset($metrics[$result->$group]))
                        {
                            $field = $metrics[$result->$group]['field'];
                            $metrics[$result->$group]['value'] += $result->$field;
                        }
                    }

                    $max = 0;
                    foreach($metrics as $data) $max = $data['value'] > $max ? $data['value'] : $max;

                    $data  = array('name' => '', 'value' => array());
                    $value = array();
                    foreach($metrics as $metric)
                    {
                        $indicator[]     = array('name' => $metric['name'], 'max' => $max);
                        $data['value'][] = $metric['value'];
                        $value[]         = $metric['value'];
                    }
                    $seriesData[] = $data;
                }

                $component->option->dataset->radarIndicator   = $indicator;
                $component->option->radar->indicator          = $indicator;
                $component->option->dataset->seriesData       = $seriesData;
                $component->option->series[0]->data[0]->value = $value;
            }

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Build funnel chart.
     *
     * @param  object $component
     * @param  object $chart
     * @access public
     * @return void
     */
    public function buildFunnelChart(object $component, object $chart): void
    {
        if(!$chart->settings)
        {
            $component->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
            $component->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
            $component->key         = "Funnel";
            $component->chartConfig = json_decode('{"key":"Funnel","chartKey":"VFunnel","conKey":"VCFunnel","title":"漏斗图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"echarts","image":"/static/png/funnel-d032fdf6.png"}');
            $component->option      = json_decode('{"dataset":{"dimensions":["product","dataOne"],"source":[{"product":"data1","dataOne":20},{"product":"data2","dataOne":40},{"product":"data3","dataOne":60},{"product":"data4","dataOne":80},{"product":"data5","dataOne":100}]},"series":[{"name":"Funnel","type":"funnel","gap":5,"label":{"show":true,"position":"inside","fontSize":12}}],"backgroundColor":"rgba(0,0,0,0)"}');

            $this->setComponentDefaults($component);
        }
    }

    /**
     * Get burn data.
     *
     * @access public
     * @return array
     */
    public function getBurnData()
    {
        $type = 'withdelay';
        $this->loadModel('execution');
        $executions    = $this->execution->getList(0, 'sprint', 'doing') + $this->execution->getList(0, 'stage', 'doing');

        $executionData = array();

        foreach($executions as $executionID => $execution)
        {
            $execution = $this->execution->getByID($executionID);

            /* Splice project name for the execution name. */
            $execution->name = $this->loadModel('project')->getByID($execution->project)->name . '--' . $execution->name;

            /* Get date list. */
            if(((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
                or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
                or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
                and strpos($type, 'delay') === false)
                $type .= ',withdelay';

            $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
            $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
            $endDate  = (strpos($type, 'withdelay') !== false and $deadline > $execution->end) ? $deadline : $execution->end;
            list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $deadline);

            $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
            $chartData = $this->execution->buildBurnData($executionID, $dateList, 'left', $executionEnd);

            $execution->chartData = $chartData;
            $executionData[$executionID] = $execution;
        }
        return $executionData;
    }

    /**
     * Init component.
     *
     * @param  object $chart
     * @param  string $type
     * @param  object $component
     * @access public
     * @return void
     */
    public function initComponent($chart, $type, $component = null)
    {
        if(!$component) $component = new stdclass();
        if(!$chart) return $component;

        $settings = is_string($chart->settings) ? json_decode($chart->settings) : $chart->settings;

        if(!isset($component->id))       $component->id       = $chart->id;
        if(!isset($component->sourceID)) $component->sourceID = $chart->id;
        if(!isset($component->title))    $component->title    = $chart->name;

        if($type == 'chart') $chartType = ($chart->builtin and !in_array($chart->id, $this->config->screen->builtinChart)) ? $chart->type : $settings[0]->type;
        if($type == 'pivot') $chartType = 'table';
        $component->type = $chartType;

        $typeChanged = false;

        // Get type is changed or not.
        if(isset($component->chartConfig))
        {
            foreach($this->config->screen->chartConfig as $type => $chartConfig)
            {
                $chartConfig = json_decode($chartConfig, true);
                if($chartConfig['key'] == $component->chartConfig->key) $componentType = $type;
            }

            $typeChanged = $chartType != $componentType;
        }

        // New component type or change component type.
        if(!isset($component->chartConfig) or $typeChanged)
        {
            $chartConfig = json_decode(zget($this->config->screen->chartConfig, $chartType));
            if(empty($chartConfig)) return null;

            $component->chartConfig = $chartConfig;
        }

        if(!isset($component->option) or $typeChanged)
        {
            $component->option = json_decode(zget($this->config->screen->chartOption, $component->type));
            $component->option->dataset = new stdclass();
        }

        if(!isset($component->option->dataset)) $component->option->dataset = new stdclass();
        $component->chartConfig->title    = $chart->name;
        $component->chartConfig->sourceID = $component->sourceID;

        return array($component, $typeChanged);
    }

    /**
     * Check if the Chart is in use.
     *
     * @param  int    $chartID
     * @param  string $type
     * @access public
     * @return void
     */
    public function checkIFChartInUse($chartID, $type = 'chart')
    {
        static $screenList = array();
        if(empty($screenList)) $screenList = $this->dao->select('scheme')->from(TABLE_SCREEN)->where('deleted')->eq(0)->andWhere('status')->eq('published')->fetchAll();

        foreach($screenList as $screen)
        {
            $scheme = json_decode($screen->scheme);
            if(empty($scheme->componentList)) continue;

            foreach($scheme->componentList as $component)
            {
                if(!empty($component->isGroup))
                {
                    foreach($component->groupList as $key => $groupComponent)
                    {
                        if(!isset($groupComponent->chartConfig)) continue;

                        $sourceID   = zget($groupComponent->chartConfig, 'sourceID', '');
                        $sourceType = zget($groupComponent->chartConfig, 'package', '') == 'Tables' ? 'pivot' : 'chart';

                        if($chartID == $sourceID and $type == $sourceType) return true;
                    }
                }
                else
                {
                    if(!isset($component->chartConfig)) continue;

                    $sourceID   = zget($component->chartConfig, 'sourceID', '');
                    $sourceType = zget($component->chartConfig, 'package', '') == 'Tables' ? 'pivot' : 'chart';
                    if($chartID == $sourceID and $type == $sourceType) return true;
                }

            }
        }
        return false;
    }
}
