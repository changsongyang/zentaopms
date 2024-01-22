<?php
$now   = helper::now();
$today = helper::today();

global $app, $lang;
$config->story->form = new stdclass();
$config->story->form->create = array();
$config->story->form->create['product']     = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['branch']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['module']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['plan']        = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['assignedTo']  = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->create['source']      = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => $lang->story->sourceList);
$config->story->form->create['sourceNote']  = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->create['feedbackBy']  = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->create['notifyEmail'] = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->create['reviewer']    = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->create['URS']         = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'options' => array());
$config->story->form->create['parent']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['region']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['lane']        = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->create['title']       = array('type' => 'string',  'control' => 'text',         'required' => true,  'filter'  => 'trim');
$config->story->form->create['color']       = array('type' => 'string',  'control' => 'color',        'required' => false, 'default' => '');
$config->story->form->create['category']    = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'feature', 'options' => $lang->story->categoryList);
$config->story->form->create['pri']         = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => $config->story->defaultPriority, 'options' => array_filter($lang->story->priList));
$config->story->form->create['estimate']    = array('type' => 'float',   'control' => 'text',         'required' => false, 'default' => 0);
$config->story->form->create['spec']        = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->create['verify']      = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->create['keywords']    = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '');
$config->story->form->create['type']        = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'story');
$config->story->form->create['mailto']      = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'options' => 'users');
$config->story->form->create['status']      = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'active');
$config->story->form->create['branches']    = array('type' => 'array',   'control' => 'select',       'required' => false, 'default' => array(), 'options' => array());
$config->story->form->create['modules']     = array('type' => 'array',   'control' => 'select',       'required' => false, 'default' => 0, 'options' => array());
$config->story->form->create['plans']       = array('type' => 'array',   'control' => 'select',       'required' => false, 'default' => 0, 'options' => array());
$config->story->form->create['vision']      = array('type' => 'string',  'control' => '',             'required' => false, 'default' => $config->vision);
$config->story->form->create['version']     = array('type' => 'int',     'control' => '',             'required' => false, 'default' => 1);
$config->story->form->create['openedBy']    = array('type' => 'string',  'control' => '',             'required' => false, 'default' => $app->user->account);
$config->story->form->create['openedDate']  = array('type' => 'string',  'control' => '',             'required' => false, 'default' => helper::now());

$config->story->form->edit = array();
$config->story->form->edit['product']        = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->edit['branch']         = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->edit['module']         = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->edit['parent']         = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->edit['title']          = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->edit['color']          = array('type' => 'string',  'control' => 'color',        'required' => false, 'default' => '');
$config->story->form->edit['spec']           = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->edit['verify']         = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->edit['plan']           = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->story->form->edit['source']         = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => $lang->story->sourceList);
$config->story->form->edit['sourceNote']     = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->edit['stage']          = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => $lang->story->stageList);
$config->story->form->edit['category']       = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'feature', 'options' => $lang->story->categoryList);
$config->story->form->edit['pri']            = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => $config->story->defaultPriority, 'options' => array_filter($lang->story->priList));
$config->story->form->edit['estimate']       = array('type' => 'float',   'control' => 'text',         'required' => false, 'default' => 0);
$config->story->form->edit['feedbackBy']     = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->edit['notifyEmail']    = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->edit['keywords']       = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '');
$config->story->form->edit['mailto']         = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'options' => 'users');
$config->story->form->edit['reviewer']       = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->edit['status']         = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'active');
$config->story->form->edit['assignedTo']     = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->edit['closedBy']       = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->edit['closedReason']   = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => '', 'options' => $lang->story->reasonList);
$config->story->form->edit['duplicateStory'] = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->story->form->edit['childStories']   = array('type' => 'array',   'control' => 'select',       'required' => false, 'default' => '', 'options' => array(), 'filter' => 'join');

$config->story->form->batchCreate = common::formConfig('story', 'batchCreate');
$config->story->form->batchCreate['branch']     = array('ditto' => true,  'type' => 'int',    'control' => 'select',   'required' => false, 'default' => 0,  'options' => array());
$config->story->form->batchCreate['module']     = array('ditto' => true,  'type' => 'int',    'control' => 'select',   'required' => false, 'default' => 0,  'options' => array());
$config->story->form->batchCreate['plan']       = array('ditto' => true,  'type' => 'int',    'control' => 'select',   'required' => false, 'default' => 0,  'options' => array());
$config->story->form->batchCreate['assignedTo'] = array('ditto' => false, 'type' => 'string', 'control' => 'select',   'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->batchCreate['region']     = array('ditto' => false, 'type' => 'int',    'control' => 'select',   'required' => false, 'default' => 0,  'options' => array());
$config->story->form->batchCreate['lane']       = array('ditto' => false, 'type' => 'int',    'control' => 'select',   'required' => false, 'default' => 0,  'options' => array());
$config->story->form->batchCreate['title']      = array('ditto' => false, 'type' => 'string', 'control' => 'text',     'required' => true,  'default' => '', 'filter'  => 'trim', 'base' => true);
$config->story->form->batchCreate['color']      = array('ditto' => false, 'type' => 'string', 'control' => 'text',     'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->batchCreate['spec']       = array('ditto' => false, 'type' => 'string', 'control' => 'textarea', 'required' => false, 'default' => '');
$config->story->form->batchCreate['source']     = array('ditto' => true,  'type' => 'string', 'control' => 'select',   'required' => false, 'default' => '', 'options' => $lang->story->sourceList);
$config->story->form->batchCreate['sourceNote'] = array('ditto' => false, 'type' => 'string', 'control' => 'text',     'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->batchCreate['verify']     = array('ditto' => false, 'type' => 'string', 'control' => 'textarea', 'required' => false, 'default' => '');
$config->story->form->batchCreate['category']   = array('ditto' => true,  'type' => 'string', 'control' => 'select',   'required' => false, 'default' => 'feature', 'options' => $lang->story->categoryList);
$config->story->form->batchCreate['pri']        = array('ditto' => false, 'type' => 'int',    'control' => 'select',   'required' => false, 'default' => $config->story->defaultPriority, 'options' => $lang->story->priList);
$config->story->form->batchCreate['estimate']   = array('ditto' => false, 'type' => 'float',  'control' => 'text',     'required' => false, 'default' => 0);
$config->story->form->batchCreate['reviewer']   = array('ditto' => true,  'type' => 'array',  'control' => 'select',   'required' => false, 'default' => '', 'multiple' => true, 'options' => array());
$config->story->form->batchCreate['URS']        = array('ditto' => false, 'type' => 'array',  'control' => 'select',   'required' => false, 'default' => '', 'multiple' => true);
$config->story->form->batchCreate['parent']     = array('ditto' => false, 'type' => 'int',    'control' => 'select',   'required' => false, 'default' => '');
$config->story->form->batchCreate['keywords']   = array('ditto' => false, 'type' => 'string', 'control' => 'text',     'required' => false, 'default' => '');
$config->story->form->batchCreate['mailto']     = array('ditto' => false, 'type' => 'array',  'control' => 'select',   'required' => false, 'default' => array(''), 'multiple' => true, 'options' => 'users', 'filter' => 'join');

$config->story->form->batchEdit = array();
$config->story->form->batchEdit['branch']       = array('type' => 'int',    'width' => '200px', 'control' => 'picker', 'required' => false, 'default' => 0, 'options' => array());
$config->story->form->batchEdit['module']       = array('type' => 'int',    'width' => '200px', 'control' => array('type' => 'picker', 'required' => true), 'required' => false, 'default' => 0, 'options' => array());
$config->story->form->batchEdit['plan']         = array('type' => 'int',    'width' => '200px', 'control' => 'picker', 'required' => false, 'default' => 0, 'options' => array());
$config->story->form->batchEdit['title']        = array('type' => 'string', 'width' => '240px', 'control' => array('type' => 'colorInput', 'inputClass' => 'filter-none'), 'required' => true,  'filter'  => 'trim', 'base' => true);
$config->story->form->batchEdit['color']        = array('type' => 'string',                     'control' => 'color',  'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->batchEdit['estimate']     = array('type' => 'float',  'width' => '76px',  'control' => 'text',   'required' => false, 'default' => '0');
$config->story->form->batchEdit['category']     = array('type' => 'string', 'width' => '160px', 'control' => 'picker', 'required' => false, 'default' => 'feature', 'options' => array_filter($lang->story->categoryList));
$config->story->form->batchEdit['pri']          = array('type' => 'string', 'width' => '92px',  'control' => array('type' => 'picker', 'required' => true), 'required' => false, 'default' => $config->story->defaultPriority,  'options' => array_filter($lang->story->priList));
$config->story->form->batchEdit['assignedTo']   = array('type' => 'string', 'width' => '136px', 'control' => 'picker', 'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->batchEdit['source']       = array('type' => 'string', 'width' => '160px', 'control' => 'picker', 'required' => false, 'default' => '', 'options' => array_filter($lang->story->sourceList));
$config->story->form->batchEdit['sourceNote']   = array('type' => 'string', 'width' => '200px', 'control' => 'text',   'required' => false, 'default' => '', 'filter'  => 'trim');
$config->story->form->batchEdit['status']       = array('type' => 'string', 'width' => '80px',  'control' => 'static', 'required' => false, 'default' => 0,  'options' => array_filter($lang->story->statusList));
$config->story->form->batchEdit['stage']        = array('type' => 'string', 'width' => '120px', 'control' => 'picker', 'required' => false, 'default' => 0,  'options' => array_filter($lang->story->stageList));
$config->story->form->batchEdit['closedBy']     = array('type' => 'string', 'width' => '136px', 'control' => 'picker', 'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->batchEdit['closedReason'] = array('type' => 'string', 'width' => '200px', 'control' => 'picker', 'required' => false, 'default' => '', 'options' => array_filter($lang->story->reasonList));
$config->story->form->batchEdit['keywords']     = array('type' => 'string', 'width' => '200px', 'control' => 'text',   'required' => false, 'default' => '', 'filter'  => 'trim');

$config->story->form->batchclose = array();
$config->story->form->batchclose['id']             = array('type' => 'int',    'required' => true,  'default' => '', 'base' => true);
$config->story->form->batchclose['closedReason']   = array('type' => 'string', 'required' => true,  'default' => '');
$config->story->form->batchclose['duplicateStory'] = array('type' => 'int',    'required' => false, 'default' => 0);

$config->story->form->assignTo = array();
$config->story->form->assignTo['assignedTo']     = array('type' => 'string',   'control' => 'picker', 'required' => false, 'default' => '');
$config->story->form->assignTo['lastEditedBy']   = array('type' => 'string',   'control' => 'hidden', 'required' => false, 'default' => $app->user->account);
$config->story->form->assignTo['lastEditedDate'] = array('type' => 'datetime', 'control' => 'hidden', 'required' => false, 'default' => $now);
$config->story->form->assignTo['assignedDate']   = array('type' => 'datetime', 'control' => 'hidden', 'required' => false, 'default' => $now);

$config->story->form->change = array();
$config->story->form->change['reviewer']       = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'options' => '');
$config->story->form->change['title']          = array('type' => 'string',  'control' => 'text',         'required' => true,  'filter'  => 'trim');
$config->story->form->change['color']          = array('type' => 'string',  'control' => 'color',        'required' => false, 'default' => '');
$config->story->form->change['spec']           = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->change['verify']         = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->story->form->change['relievedTwins']  = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '1');
$config->story->form->change['status']         = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => '');
$config->story->form->change['lastEditedDate'] = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => '');

$config->story->form->review = array();
$config->story->form->review['reviewedDate']   = array('type' => 'date',   'control' => 'datetimePicker', 'required' => false, 'default' => '');
$config->story->form->review['result']         = array('type' => 'string', 'control' => 'picker',         'required' => true,  'default' => '', 'options' => '', 'title' => $lang->story->reviewResult);
$config->story->form->review['assignedTo']     = array('type' => 'string', 'control' => 'picker',         'required' => false, 'default' => '', 'options' => 'users');
$config->story->form->review['closedReason']   = array('type' => 'string', 'control' => 'picker',         'required' => false, 'default' => '', 'options' => $lang->story->reasonList, 'title' => $lang->story->rejectedReason);
$config->story->form->review['pri']            = array('type' => 'int',    'control' => 'priPicker',      'required' => false, 'default' => '', 'options' => $lang->story->priList);
$config->story->form->review['estimate']       = array('type' => 'float',  'control' => 'text',           'required' => false, 'default' => '');
$config->story->form->review['duplicateStory'] = array('type' => 'string', 'control' => 'text',           'required' => false, 'default' => '');
$config->story->form->review['childStories']   = array('type' => 'string', 'control' => 'text',           'required' => false, 'default' => '');
$config->story->form->review['status']         = array('type' => 'string', 'control' => 'hidden',         'required' => false, 'default' => '');

$config->story->form->activate = array();
$config->story->form->activate['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->activate['activatedDate']  = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->activate['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->activate['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->story->form->activate['closedBy']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->activate['closedReason']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->activate['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->story->form->activate['reviewedBy']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->activate['reviewedDate']   = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->story->form->activate['assignedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->activate['duplicateStory'] = array('type' => 'int',      'required' => false, 'default' => 0);
$config->story->form->activate['childStories']   = array('type' => 'string',   'required' => false, 'default' => '');

$config->story->form->close = array();
$config->story->form->close['status']         = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->story->form->close['stage']          = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->story->form->close['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->close['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->story->form->close['closedBy']       = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->story->form->close['closedReason']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->close['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->close['assignedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->story->form->close['duplicateStory'] = array('type' => 'int',      'required' => false, 'default' => 0);
$config->story->form->close['childStories']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->story->form->close['closeSync']      = array('type' => 'string',   'required' => false, 'default' => '');

$config->story->form->submitReview = array();
$config->story->form->submitReview['reviewer']     = array('type' => 'array',    'control' => 'multi-select', 'required' => false, 'default' => '');
$config->story->form->submitReview['reviewedBy']   = array('type' => 'string',   'control' => 'hidden',       'required' => false, 'default' => '');
$config->story->form->submitReview['reviewedDate'] = array('type' => 'datetime', 'control' => 'hidden',       'required' => false, 'default' => '');
$config->story->form->submitReview['status']       = array('type' => 'string',   'control' => 'hidden',       'required' => false, 'default' => 'active');

$config->story->form->batchToTask['module']     = array('type' => 'int',    'required' => false, 'default' => 0);
$config->story->form->batchToTask['story']      = array('type' => 'int',    'required' => false, 'default' => 0);
$config->story->form->batchToTask['name']       = array('type' => 'string', 'required' => false, 'default' => '', 'base' => true, 'filter' => 'trim');
$config->story->form->batchToTask['type']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->story->form->batchToTask['assignedTo'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->story->form->batchToTask['estimate']   = array('type' => 'float',  'required' => false, 'default' => 0);
$config->story->form->batchToTask['estStarted'] = array('type' => 'date',   'required' => false, 'default' => '');
$config->story->form->batchToTask['deadline']   = array('type' => 'date',   'required' => false, 'default' => '');
$config->story->form->batchToTask['pri']        = array('type' => 'int',    'required' => false, 'default' => 3);
$config->story->form->batchToTask['status']     = array('type' => 'string', 'required' => false, 'default' => 'wait');
$config->story->form->batchToTask['vision']     = array('type' => 'string', 'required' => false, 'default' => 'rnd');
$config->story->form->batchToTask['openedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->story->form->batchToTask['openedDate'] = array('type' => 'string', 'required' => false, 'default' => $now);
$config->story->form->batchToTask['version']    = array('type' => 'int',    'required' => false, 'default' => 1);
