$(document).ready()
{
    $('#addUnitBox').find("[name^='unit']").prop('disabled', true);
}

window.addUnit = function(e)
{
    if($(e.target).prop('checked'))
    {
        $('#unitBox').addClass('hidden');
        $('#addUnitBox').removeClass('hidden');
        $("[name^='customUnit']").prop('checked', true);

        $('#addUnitBox').find("[name^='unit']").prop('disabled', false);
    }
    else
    {
        $('#unitBox').removeClass('hidden');
        $('#addUnitBox').addClass('hidden');
        $("[name^='customUnit']").prop('checked', false);

        $('#addUnitBox').find("[name^='unit']").prop('disabled', true);
    }
}
