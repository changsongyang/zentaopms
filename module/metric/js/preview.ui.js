window.renderHeight = function()
{
    return $('.table-side').height();
}

$(document).ready()
{
    $(document).on('change', '.checkbox-primary input[type="checkbox"]', function()
    {
      $('.checkbox-primary input[type="checkbox"]').each(function()
      {
        if($(this).is(":checked")) $(this).closest('.checkbox-primary').addClass('metric-current');
        if($(this).is(":not(:checked)")) $(this).closest('.checkbox-primary').removeClass('metric-current');
      });
    });
}
