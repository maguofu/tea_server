<?php
  $link = new mysqli('localhost', 'root', '123456', 'mydb');
  if($link->connect_error){
    die('link failed ' . $link->connect_error);
  }else{
    print_r('success' . '<br/>');
  };

  $sql = "create table goods(
    id int not null primary key auto_increment,
    name text not null ,
    purchase_price int not null default 200,
    sale_price int not null default 200,
    description longtext not null,
    img_url_list_str mediumtext
  )auto_increment=5956 engine=innodb charset=utf8";
  if($link->query($sql) === true){
    echo 'the table created successful';
  }else{
    echo 'the table created failed ' . $link->error;
  }

  $link->close();



  // 查看MySQL建标语句：show create table table_name