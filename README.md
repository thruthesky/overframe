# Overframe


## Overview

Overframe is a framewhich runs upon other frameworks like codeigniter, laravel, slim, etc.

## TODO

* Customize database class to use the active database connect of parent framework. This is the key point why it is overframe.
* Database.php 에는 최소한의 코드만 두어야 각 프레임의 DB 를 쉽게 활용 할 수 있다.
query 함수 하나면 insert, select, update, delete, table create, update, delete 다 할 수 있다.

 

## installation
git submodule add https://github.com/thruthesky/overframe

for sapcms_1_2, add it into root/etc folder.<br>
for codeigniter3, add it into root folder.


### git sub-module

Since project is not for the public use, we do not use composer/packagist.org<br>






## Unit Test
Overframe unit test is builtin.<br>
It is run on web server not on CLI.<br> 
To run the unit test, input this code where ever you want.

    <?php
		echo runTest();
	?>




## autoload

if model name and php class name are the same, then you can simply put<br>

namespace of;<br>

as its namespace and use like below.<br>

new \of\Database();<br>



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
    