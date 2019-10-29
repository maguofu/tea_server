<?php
  // 请求参数
  $id = $_GET['id'];

  // 接口输出
  $res = array(
    'errNo' => 0,
    'errStr' => 'success',
    'data' => array(
      'description' => '',
      'detailList' => array()
    )
  );

  function selectDetail() {
    $link = new mysqli('localhost', 'root', '123456', 'mydb');
    if ($link->connect_errno == 0) {
      $selectSql = 'select description,img_url_list_str from goods where id='.$GLOBALS['id'];
      $res = $link->query($selectSql);
      if (!$link->query($selectSql)) {
        $GLOBALS['res']['errNo'] = 502;
        $GLOBALS['res']['errStr'] = '数据查询失败';
      } else {
        $rows = $res->fetch_row();
        if (count($rows) == 0) {
          $GLOBALS['res']['errNo'] = 503;
          $GLOBALS['res']['errStr'] = '数据查询失败，请检查id！';
        } else {
          $GLOBALS['res']['data']['description'] = $rows[0];
          if (strpos($rows[1], ';')) {
            $GLOBALS['res']['data']['detailList'] = explode(';', $rows[1]);
          } else {
            $GLOBALS['res']['data']['detailList'] = array( $rows[1] );
          }
        }
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }
  selectDetail();
  print_r(json_encode($res));