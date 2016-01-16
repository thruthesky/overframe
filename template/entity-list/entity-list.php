<link rel="stylesheet" href="<?php echo url_overframe()?>/etc/css/ajaxcrud.css">
<script src="<?php echo url_overframe()?>/etc/js/ajaxcrud.js"></script>

<?php
$db = database();
$tables = $db->getTables();
$entities = [];
foreach ($tables as $table)
{
    if ( strpos($table, '_entity')  ) {
        $name = str_replace('_node_entity', '', $table);
        $name = str_replace('_meta_entity', '', $name);
        $entities[$name] = $table;

    }
    else continue;
}

// @todo 여기서 부터... Ajax 로 CRUD & List & Navigation 을 할 것.
// @todo 활용도는 많다. 회원 목록 및 CRUD, 각종 정보 목록 및 CRUD 를 할 수 있다.
// @todo CRUD 는 필드별로 할 것. 그래야 깔끔함. 안그러면 처리가 매우 어려워 짐.
// @todo 새로운 레코드를 생성 할 때에도 그냥 add 버튼만 클릭하면 새로운 행이 생성되도록 한다.
// @todo 이것이 끝나면 philgo_banner 를 관리 할 수 있는 ACL 테이블을 만든다.
// @todo acl_node_entity 로 만든다.
// @todo 주의 복잡한 것은 어렵다. 예를 들어서 파일 업로드를 하는 경우 여러개의 테이블 정보가 필요하다.
// @todo 따라서 etc/js/ajaxcrud 에 골격을 만들고,각 모듈에서 활용하도록 한다.
// @todo underscore.js 의 template 을 활용한다.
foreach ($entities as $name => $table) {
    echo "<span class='btn btn-primary' table='$table'>$name</span>";
}
