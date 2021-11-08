$(document).ready(function()
{
    $('#avatarUploadBtn').on('click', function()
    {
        $('#files').click();
    });
    $("#files").change(function(){
        var files = this.files;
        if(!files.length)
        {
            return;
        }

        $(".avatar img").attr("src", window.URL.createObjectURL(files[0]));
        $(".avatar").removeClass('hidden');
    });
});