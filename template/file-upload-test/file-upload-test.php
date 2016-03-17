<?php

?>
<script src="<?php echo url_overframe()?>/etc/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo url_overframe()?>/etc/js/jquery.form.min.js"></script>


<h3>Ajax File Upload</h3>
<form action="<?php echo ajax_endpoint()?>&do=data&what=file-upload" method="post" enctype="multipart/form-data">
    <input type="hidden" name="code" value="test">
    <input type="hidden" name="unique" value="1">
    <input type="hidden" name="gid" value="<?php echo unique_id()?>">
    <input type="file" name="userfile" onchange="on_change_file_upload(this);">
</form>
<div class="display-file"></div>
<script>
    function on_change_file_upload(filebox) {
        var $filebox = $(filebox);
        var $form = $filebox.parents("form");

        $form.ajaxSubmit({
            error : function (xhr) {
                console.log(xhr);
                console.log("ERROR on ajaxSubmit() ...");
            },
            complete: function (xhr) {
                console.log("File upload completed through jquery.form.js");
                var re;
                try {
                    re = JSON.parse(xhr.responseText);
                }
                catch (e) {
                    console.log("ERROR: Failed on file upload...");
                    $form.after().html(xhr.responseText);
                }

                if ( re['code'] ) {
                    return alert(re['message']);
                }
                else {
                    $(".display-file").html("<span class='delete-file'>DELETE</span> <span class='finish-file'>FINISH</span><hr><img width='100%' file-id='"+re['data']['id']+"' src='"+re['data']['url']+"'>");
                }
                console.log(re);
            }
        });
        $filebox.val('');
    }

    $('body').on('click', '.delete-file', function() {
        var id = $(this).parent().find('img').attr('file-id');
        console.log('id:' + id);
        $.get("<?php echo ajax_endpoint()?>&do=data&what=file-delete&id="+id, function(data) {
            var re = JSON.parse(data);
            console.log(re);
            if ( re['code'] ) {
                alert(re['message']);
            }
            else {
                $(".display-file").html('<h3>File deleted</h3>');
            }
        });
    });

    $('body').on('click', '.finish-file', function() {
        var id = $(this).parent().find('img').attr('file-id');
        console.log('.finish-file id:' + id);
        var url = "<?php echo ajax_endpoint()?>&do=data&what=file-finish&id="+id;
        console.log(url);
        $.get(url, function(data) {
            var re = JSON.parse(data);
            console.log(re);
            if ( re['code'] ) {
                alert(re['message']);
            }
            else {
                $(".display-file").prepend('<h3>File finished</h3>');
            }
        });
    });

</script>
