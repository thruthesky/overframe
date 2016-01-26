# Overframe


## Overview

Overframe is a framewhich runs upon other frameworks like codeigniter, laravel, slim, etc.

## 사용 설명

* Test.php 파일을 살펴보면, 각 모듈 별 활용도를 잘 이해 할 수 있다. 


## TODO

* 기본 설치를 할 수 있도록 한다.
 설치를 하면 config_meta_entity, module_meta_entity 를 설치한다.
 config_meta_entity 에는
 module_meta_entity 에는 어떤 모듈이 설치되었는지 표시를 하고,
 설치된 모듈의 경우에는 메뉴를 관리자 페이지에 표시 할 수 있도록 한다.
 
* 뷰(스킨, 테마)가 필요 없는 모듈의 경우, 모듈 내에서 모든 것을 다 처리 할 수 있도록 한다.

template() 을 호출 할 때, 템플릿 폴더에 데이터가 없으면 모듈 폴더에서 템플릿을 찾는다.


* 뷰와 컨트롤러 기능을 추가한다.

현재 template 이 뷰와 컨트롤러 역활을 하는데, 이를 변경한다.




 

## installation 설치

git submodule add https://github.com/thruthesky/overframe

루트 폴더에 설치해야 한다.

    * autoload.php 가 정상동작을 하기 위해서는 루트 폴더에 설치를 할 필요가 있다.


### git sub-module 로만 진행. packagist 로는 하지 않음.

Since project is not for the public use, we do not use composer/packagist.org<br>


### sapcms 1.2 에 설치 후 관리 페이지 ( Dash Board )

    /?module=overframe&action=index

sapcms 1.2 의 관리자 아이디로 접속을 하면 각종 메뉴를 볼 수 있다.


## 코딩


### 접속 경로

모든 접속 경로는 아래와 같다.

    /?module=overframe&action=index

오직 위 경로만 사용한다.


위 경로로 접속하는 경우 root/overframe/template/sapcms1/sapcms1.php 가 실행되며 모든 작업을 이 스크립트에서 진행한다.

단, mode 가 'ajax' 인 경우에는 root/overframe/model/ajax/Ajax.php 의 run() 함수가 모든 작업을 맡아서 한다.

mode=ajax 인 경우 리턴되는 값은 ajax 이다.

mode=ajax 인 경우,

실행(쿼리)를 위한 condition 값이 필요한데 'model' 이 그 역활을 한다.

( 2016년 1월 25일 이전에는 'do', 'what' 으로 했다. )

model 의 3 개의 경로 값을 점(.)으로 분리하여 가지며

예를 들어 &model=entity.ItemList.collect 와 같이 하면
 
autoload.php 에 따라서 적절한 namespace 의 ItemList.php 를 로드하고

object 를 생성한 다음 collect() 메소드를 호출한다.


다음은 http://philgo.org/?module=overframe&action=index&model=entity.crud.collect&entity=philgo_attend 와 같이 접속을 한 경우,
 
실행되는 최종 collect() 메소드이다.

예제) root/overframe/entity/ItemList.php 의 collect() 메소드. 

    public function collect() {
        json_success(array());
    }






## Unit Test
Overframe unit test is builtin.<br>
It is run on web server not on CLI.<br> 
To run the unit test, input this code where ever you want.

    <?php
		echo runTest();
	?>


## 각종 경로

각 framework 마다 시작페이지(메인페이지, front page)가 틀릴 수 있다.

아래와 같이 url_action() 함수가 각 시작 페이지 URL 을 리턴한다.

    <a href="<?php echo url_action(); ?>">HOME</a>


## autoload

if model name and php class name are the same, then you can simply put<br>

namespace of;<br>

as its namespace and use like below.<br>

new \of\Database();<br>



## 각 Framework 별 설치

새로운 framework 에 적용하기 위해서는

database/DatabaseLayer.php 와 user/UserLayer.php 를 수정해야 한다.





## Entity

Entity is a group of items that has the same characters.<br>
Entity is a table.<br>

Entity 는 하나의 테이블로서 Entity 를 생성하면 해당 테이블에 하나의 레코드가 생성된다.<br>
그리고 그 레코드 정보를 Entity->record 에 보관하는 데, 이것이 바로 item 이 된다.<br>
다시말하면, 각 entity 객체에는 $record private 변수가 있는데, 이것은 하나의 테이블 레코드의 정보를 가지며 이것은 해당 entity 의 아이템이 된다.<br>

즉, entity 는 테이블이면서 내부의 record 변수에 하나의 행을 담고 있다. 따라서 entity 객체는 테이블이면서 동시에 하나의 레코드를 가진다.<br>

따라서 entity 의 객체는 테이블 작업을 할 수 있으면서 동시에 하나의 행에 대한 작업을 할 수 있다.<br>





## Model 루틴

각 모델에는 init.php 가 존재하며,
 
overframe 이 시작 될 때, auotoload.php 에서 인클루드되어 실행된다.


## Model 목록/설치/제거

모듈 목록은 template/model-list/model-list.php 에서 처리를 한다.

설치는 해당 모듈의 install.php 의 is_installed, install, uninstall 훅을 사용해서 처리 한다.



설치는 hook_모듈이름_install() 을 호출하므로서 사용가능하다.

따라서 어떤 방식으로든 부모 프레임워크의 테마 또는 컨트롤러에서 "hook_모듈이름_install()"을 호출하면 된다.


## Ajax Endpoint

서버 모듈로 데이터 전송 및 표시는 Ajax 로 하는 것이 원칙이다.

Ajax 가 아니면 아예 하지를 않는다.

각 부모 모듈마다 Ajax Endpoint 를 제공해야한다.
 
그 endpoint script 는 각 model 의 controller 부분을 wrapping 하여 ajax json 데이터를 바로 출력하도록 한다.
  
    <?php echo ajax_endpoint()?>&do=행동값


ajax_endpoint() 로 호출한 정보는

Ajax 클래스가 담당한다.


## Ajax 처리

파일 업로드나 글 업로드를 할 때, ajax 를 통한 처리 방법을 설명한다.

codeigniter3 나 sapcms1.2 에서 Ajax 를 통한 호출인지 아닌지를 판별한다.

이 것은 HTTP 입력 변수에 action=ajax 와 같이 들어오면 ajax 호출로 인지한다.

그러면 ajax()->run() 을 호출하며

결국 이는 Ajax::run() 을 호출하게 되는데,

이 메소드에서 HTTP 입력 변수 'do' 에 있는 값을 바탕으로 해당 클래스를 호출 한다.

예를 들어 &do=data 와 같이 입력되면

data()->runAjax() 가 호출되며

&do=philgo_banner 와 같이 입력되면

philgo_banner::runAjax() 가 호출된다.


따라서 각 모델 별로 Ajax 처리 코드를 작성 할 수 있다.



## model 처리

2016년 1월 25일 이후부터는 기존의 "Ajax" 방식 보다는 좀 더 유연한

"Model" 직접 호출 방식을 사용하며,

또한 entity/ItemList.php 의 collect() 를 사용해서 보다 범용적이며 유연한 방식으로 코딩을 한다.


## Template 통한 웹브라우저로 페이지 정보(화면) 출력

모든 처리는 template/overframe-index-page 에서 한다.

@todo @검토 이 template 은 view 로 변경이 되어야 할 것 같다.

즉, 스킨을 보여 줄 때에는 template/overframe-index-page 에서 하며

데이터 송/수신은 ajax 로 한다.




## Data 모듈

FORM 문장에서 파일을 전달하는 변수명은 항상 'userfile' 이어야 한다.

    <input type='file' name='userfile'>

data_node_entity.code 는 indexing 이 되는 필드이며 FORM 의 입력 변수로 들어 와야 한다.

    <input type='hidden' name='code' value='photo'>

파일 업로드 폼 입력 변수에 unqiue=1 의 값이 전달되면 해당 code 의 모든 파일을 다 지우고 현재 업로드 되는 파일만 올린다. 

    <input type='hidden' name='unique' value='1'>
    
gid 는 업로드되는 파일의 그룹을 말한다. gid 를 통해서 글에 첨부되는 여러 파일을 하나의 그룹으로 묶을 수 있다.

    <input type='hidden' name='gid' value='group-id'>
    
gid 는 주로 unique_id() 로 생성하거나 자바스크립트 등으로 생성 할 수 있다.

글을 쓸 때, 파일 업로드에서 gid 를 통해서 업로드를 한 다음, 글을 저장 할 때, gid 를 "post-123", "comment-456" 과 같이 첨부 파일을 추출하게 쉽게 변경한다.

예를 들어 글 번호가 123 이면, gid 가 "post-123" 인 것을 찾도록 한다.

### File Upload FORM 문장

    <form action="<?php echo sys()->url_ajax_endpoint()?>&do=data&what=file-upload" method="post" enctype="multipart/form-data">
    
## 사용자 정보

sapcms1.2 나 Drupal8 의 경우에는 사용자 관련 기능이 이미 들어가 있다.

하지만 CodeIgniter3 의 경우 그러한 기본 기능이 없다.

따라서 CodeIgniter3 의 경우 항상 그러한 기능을 추가적으로 만들어야 하는데,

각 CodeIgniter3 마다 모두 틀릴 수 있다.

UserLayer.php (데이터레이어) 를 통해서

User::getLogin(), User::getName(), User::getID(), User::getEmail(), User::isAdmin() 의 메소드를 두고

처리를 한다.


    di( user()->getLogin() );
    di( user()->isAdmin() );





## 모듈 설치

모듈 설치의 기본 예제는 module/data/install.php 에서 찾을 수 있다.

하지만 하나의 모듈에 여러개의 entity 가 들어가는 경우에는

module/philgo/install.php 와 같이 exists() 를 overriding 해서 entity 테이블을 다르게 점검해야 할 필요가 있다.


모듈 설치 예제)
---

    <?php
    namespace of;
    class Philgo extends Node
    {
        public function __construct()
        {
            parent::__construct();
    
        }
        /**
         *
         */
        public function install() {
            $attend = node('philgo_attend');
            $attend->init();
            $attend->addColumn('user_id', 'varchar', 64);
            $attend->addColumn('date', 'int');
            $attend->addUniqueKey('user_id,date');
        }
    
        public function uninstall()
        {
            $attend = node('philgo_attend');
            $attend->uninit();
        }
    
        public function exists( $tablename = null )
        {
            $attend = node('philgo_attend');
            $tablename = $attend->getTableName();
            return parent::exists($tablename);
        }
    }
---

