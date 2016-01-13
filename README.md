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





