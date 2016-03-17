$(function(){
    var $body = $('body');
    $('[table]').click(function(){
        var $this = $(this);
        var table = $this.attr('table');
        var url = url_action + '&table=' + table;
        console.log(url);
        $.get(url, function(re){
            if ( re['code'] ) {
                alert( re['message'] );
            }
            else {
                var rows = re['data']['rows'];
                console.log('rows:' + rows.length);
                var m = $('#entity-table-rows').html();
                //console.log(m);
                var tmp = _.template( m );
                var $display = $(".display-entity-items");
                $display.empty();
                $display.append("<h2>" + re['data']['table'] + "</h2>");
                for( var i = 0; i < rows.length; i++ ) {
                    var row = rows[i];
                    m = tmp(row);
                    $display.append(m);
                }
            }
        }, 'json');
    });
});