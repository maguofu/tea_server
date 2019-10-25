<?php
  $res = array(
    'errNo' => 0,
    'errStr' => 'success',
    'data' => array(
      array(
        'title' => 'askdfalsdf'
      ),
      array(
        'title' => 'askdfalsdf'
      ),
      array(
        'title' => 'askdfalsdf'
      ),
      array(
        'title' => 'askdfalsdf'
      ),
    )
  );
  $n = 'a:1:{i:0;s:53:"https://www.greenteaxinyang.com/img/1571990323780.jpg";}';
  // print_r(unserialize($n));
  print_r(json_encode($res));