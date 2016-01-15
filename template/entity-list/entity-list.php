<?php
$db = database();
$tables = $db->getTables();
$entities = [];
foreach ($tables as $table)
{
    if ( strpos($table, '_entity')  ) {
        $entities[] = $table;
    }
    else continue;
}

// @todo 여기서 부터... Ajax 로 CRUD & List & Navigation 을 할 것.
// @todo 활용도는 많다. 회원 목록 및 CRUD, 각종 정보 목록 및 CRUD 를 할 수 있다.
// @todo CRUD 는 필드별로 할 것. 그래야 깔끔함. 안그러면 처리가 매우 어려워 짐.
// @todo 새로운 레코드를 생성 할 때에도 그냥 add 버튼만 클릭하면 새로운 행이 생성되도록 한다.
// @todo 이것이 끝나면 philgo_banner 를 관리 할 수 있는 ACL 테이블을 만든다.
// acl_node_entity 로 만든다.
di($entities);