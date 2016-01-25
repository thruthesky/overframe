<?php

namespace of\entity;
use of\Entity;

class Crud extends Entity {

    public function __construct()
    {
        parent::__construct();
    }


    /**
     *
     *
     * @note HTTP 입력 변수로 condition 값이 들어오며 적절한 처리를 하여 JSON 으로 리턴한다.
     *
     * 내부적으로는 Entity::search() 를 사용하므로 where, limit, offset, page, order_by, fields 의 값을 그대로 사용 할 수 있다.
     *
     *
     *  &entity=데이터베이스 테이블
     *  &where=의 값은 entity->search() 의 where SQL 컨디션과 동일
     *  &order_by=SQL ORDER BY 컨디션
     *  &limit=, &offset=, &page= 의 값은 entity::search() 의 것과 동일
     *
     * @return JSON 내부의 동작은 entity::search() 의 것과 동일하지만, 결과는 JSON 으로 리턴한다.
     *
     * @code 쿼리 예제
     * http://philgo.org/?module=overframe&action=index&model=entity.crud.collect&entity=data&order_by=id%20ASC&fields=id,finish,name&where=id%3E3&limit=3
     * @endcode
     */
    public function collect() {

        $in = http_input();

        $o['fields'] = isset($in['fields']) ? $in['fields'] : '*';
        $o['where'] = isset($in['where']) ? $in['where'] : null;
        $o['order_by'] = isset($in['order_by']) ? $in['order_by'] : 'id DESC';
        $o['limit'] = isset($in['limit']) ? $in['limit'] : 10;
        $o['page'] = isset($in['page']) ? $in['page'] : 1;
        $o['offset'] = isset($in['offset']) ? $in['offset'] : 0;

        $entity = $in['entity']();
        $entities = $entity->search( $o );

        $data = array();

        foreach ( $entities as $e ) {
            $data[] = $e->getRecord();
        }

        json_success($data);
    }

}