<style>
    [name="code"] {
        padding:.6em;
        box-sizing: border-box;
        width:100%;
    }
    .c-indicator {
        background-color: #c6bf93;
        left:auto;
        right:-20px;
    }
    .c-input {
        padding-left:0;
    }
    .form-submit {
        margin:0 auto;
        width:20em;
    }
</style>
<script src="<?php echo url_overframe()?>/etc/js/jquery.form.min.js"></script>
<form class='philgo-banner-form' action="<?php echo ajax_endpoint()?>&do=data&what=file-upload" method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="">
    <input type="hidden" name="fid" value="">
    <input type="hidden" name="gid" value="philgo-banner">


    <div class="of-row form-group row">
        <label for="owner" class="col-sm-3 form-control-label">광고주</label>
        <div class="col-sm-8">
            <input type="text" name="owner" id="owner" class="form-control">
        </div>
    </div>


    <div class="of-row form-group row">

        <label for="owner" class="col-sm-3 form-control-label">광고 위치 선택</label>
        <div class="col-sm-8">
            <select name="code" class="form-control">
                <option value="">광고 위치를 선택하십시오.</option>
                <option value="main-center">메인 중간 배너 너비 500</option>
                <option value="mobile-second">모바일 상단 두번째 줄 배너</option>
            </select>
        </div>
    </div>



    <div class="display-file"></div>
    <div class="of-row form-group row">
        <label for="userfile" class="col-sm-3 form-control-label">배너</label>
        <div class="filebox col-sm-8">
            <input type="file" name="userfile" class="form-control" id="userfile" onchange="on_change_file_upload(this);">
        </div>
    </div>

    <div class="of-row form-group row">
        <label for="date_from" class="col-sm-3 form-control-label">광고 시작 날짜</label>
        <div class="col-sm-8">
            <input type="date" name="date_from" id="date_from" class="form-control">
        </div>
    </div>


    <div class="of-row form-group row">
        <label for="date_to" class="col-sm-3 form-control-label">광고 끝 날짜</label>
        <div class="col-sm-8">
            <input type="date" name="date_to" id="date_to" class="form-control">
        </div>
    </div>

    <div class="of-row form-group row">
        <label class="col-sm-3">광고 일시 중지</label>
        <div class="col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="active" value="N"> 광고를 표시 하지 않습니다.
                </label>
            </div>
        </div>
    </div>



    <div class="of-row form-group row">
        <label for="list_order" class="col-sm-3 form-control-label">광고 표시 순서</label>
        <div class="col-sm-8">
            <input type="number" name="list_order" id="list_order" class="form-control" min="0" max="99" value="0">
        </div>
    </div>




    <div class="of-row form-group row">
        <label for="subject" class="col-sm-3 form-control-label">제목</label>
        <div class="col-sm-8">
            <input type="text" name="subject" id="subject" class="form-control">
        </div>
    </div>



    <div class="of-row form-group row">
        <label for="url" class="col-sm-3 form-control-label">광고 페이지 URL</label>
        <div class="col-sm-8">
            <input type="text" name="url" id="url" class="form-control">
        </div>
    </div>





    <div class="of-row">
        <div class="form-submit btn btn-primary">광고 등록</div>
    </div>




</form>

<ul>
    <li>광고 표시 순서는 높은 값 일 수록 광고가 먼저 표시됩니다.</li>
    <li>광고 페이지 URL 은 필고 게시판 글 URL 또는 일반 홈페이지 URL 을 기록하시면 됩니다.</li>
    <li>제목은 광고 표시 또는 광고 목록에 나타납니다.</li>
    <li>광고주는 광고주 명 검색 또는 찾을 때 사용합니다.</li>
</ul>

<script>

    var $body = $('body');
    var $form = $('.philgo-banner-form');


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
                    $(".display-file").html("<img width='100%' file-id='"+re['data']['id']+"' src='"+re['data']['url']+"'><hr><span class='delete-file btn btn-danger'>배너 삭제</span>");
                    $form.find('[name="fid"]').val(re['data']['id']);
                }
                console.log(re);
            }
        });
        $filebox.val('');
    }

    $body.on('click', '.delete-file', function() {
        var id = $(this).parent().find('img').attr('file-id');
        console.log('id:' + id);
        $.get("<?php echo ajax_endpoint()?>&do=data&what=file-delete&id="+id, function(data) {
            var re = JSON.parse(data);
            console.log(re);
            if ( re['code'] ) {
                alert(re['message']);
            }
            else {
                $(".display-file").html('<h5>배너가 삭제되었습니다. 다시 등록하십시오.</h5>');
            }
        });
    });



    $body.on('click', '.form-submit', function(){
        var $this = $(this);
        var url = "<?php echo ajax_endpoint()?>&do=philgo_banner&what=upload";
        console.log('on form-submit: ' + url);
        var id = $form.find('[name="id"]').val();
        var msg;
        if ( id ) msg = '광고가 수정되었습니다.';
        else msg = '광고가 등록되었습니다.';
        $.post(url, $form.serialize(), function(re) {
            console.log(re);
            if ( re['code'] ) return alert( re['message'] );
            $form.find('[name="id"]').val(re['data']['id']);
            //$('.alert').remove();
            //$this.before('<div class="alert alert-warning">'+msg+'</div>');
            alert(msg);
            $this.text('업데이트');
        }, 'json')
            .fail(function(xhr){
                console.log(xhr.responseText);
            });
    });


</script>
