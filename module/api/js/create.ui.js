$('.params-group').on('keyup', 'input,textarea', function(){
    generateParams($(this));
})

$('.params-group').on('change', 'input[type=checkbox]', function(){
    generateParams($(this));
})

$('.params-group').on('change', 'select', function(){
    generateParams($(this));
})

/* 变更请求类型时，判断是否隐藏拆分按钮. */
$('.form-group').on('change', '.objectType', function(){
    if($(this).val() != 'array' && $(this).val() != 'object')
    {
        $(this).closest('tr').find('.btn-split').addClass('hidden');
    }
    else
    {
        $(this).closest('tr').find('.btn-split').removeClass('hidden');
    }
})

/* 请求响应单独绑定事件. */
$('#form-response').on('keyup', 'input,textarea', function(){generateResponse($(this))});
$('#form-response').on('change', 'input[type=checkbox]', function(){generateResponse($(this))});
$('#form-response').on('change', 'select', function(){generateResponse($(this))});

/* 更改请求体类型. */
$('.params-group').on('change', 'input[type=radio]', function()
{
    let params = $('input[name=params]').val();
    params = JSON.parse(params);
    params['paramsType'] = $(this).val();
    $('input[name=params]').val(JSON.stringify(params));

    if($(this).val() != 'formData')
    {
        $('#form-params').find('.btn-split').removeClass('hidden');
    }
    else
    {
        $('#form-params').find('.btn-split').addClass('hidden');
    }
})

/**
 * 更改请求参数、请求头、请求体时，将表单值放到隐藏域中.
 * 
 * @param  obj $obj 
 * @access public
 * @return void
 */
function generateParams(obj)
{
    let params    = $('input[name=params]').val();
    let groupID   = $(obj).closest('.params-group').attr('id');
    let groupName = groupID.replace('form-', '');
    let group     = [];

    if(groupName != 'params')
    {
        $(obj).closest('.params-group').find('.input-row').each(function()
        {
            let values = {};
            $(this).find('input,textarea,select').each(function()
            {
                buildValues($(this), values);
            })

            group.push(values);
        })
    }
    else
    {
        /* 请求体是无限级的. */
        group = buildNestedParams(obj);
    }

    params = JSON.parse(params);
    params[groupName] = group;

    $('input[name=params]').val(JSON.stringify(params));
}

function generateResponse(obj)
{
    let group = buildNestedParams(obj);

    $('input[name=response]').val(JSON.stringify(group));
}

/**
 * 将params构造成无限级的树状结构. 
 * 
 * @param  object obj 
 * @access public
 * @return void
 */
function buildNestedParams(obj)
{
    const rows = Array.from($(obj).closest('.form-group').find('.input-row'));

    const group = [];

    rows.filter(row => row.dataset.parent === "0").forEach(parentRow => {
        const transformedRow = processRow(parentRow);
        group.push(transformedRow);
    });

    return group;
}

/**
 * 处理每一行的数据. 
 * 
 * @param  object row 
 * @access public
 * @return void
 */
function processRow(row)
{
    let values = {
        field: row.dataset.level,
        paramsType: "object",
        required: "",
        desc: "",
        structType: $('#form-paramsType').find('input[type=radio]:checked').val(),
        level: row.dataset.level,
        key: row.dataset.key,
        parentKey: row.dataset.parent,
        children: []
    };

    $(row).find('input,textarea,select').each(function()
    {
        buildValues($(this), values);
    })

    const childRows = Array.from($(row).closest('.form-group').find(`.input-row[data-parent=${row.dataset.key}]`));
    childRows.forEach(childRow => {
        const transformedChild = processRow(childRow);
        values.children.push(transformedChild);
    });

    return values;
}

/**
 * 获取各个表单的值.
 * 
 * @param  object $obj 
 * @param  object $values 
 * @access public
 * @return void
 */
function buildValues($obj, values)
{
    let value = $obj.val();
    if($obj.prop("type") === "text")
    {
        values.field = value;
    }
    else if($obj.prop("type") === "checkbox")
    {
        values.required = $obj.prop('checked');
    }
    else if($obj.prop("tagName").toLowerCase() === "select")
    {
        values.paramsType = value;
    }
    else if($obj.prop("tagName").toLowerCase() === "textarea")
    {
        values.desc = value;
    }

    return values;
}

/**
 * 给tr生成唯一的key.
 *
 * @access public
 * @return void
 */
function genKey()
{
    let key = Date.now().toString(36)
    key += Math.random().toString(36).substr(2)
    return key
}
