<?php
  // 查询参数
  $id = $_POST['id'];

  // 接口输出
  $res = array(
    'errNo' => 0,
    'errStr' => 'success',
    'data' => array()
    );

  function goodsSearch() {
    $link = new mysqli('localhost', 'root', '123456', 'mydb');
    if ($link->connect_errno == 0) {
      $selectSql = 'select name,purchase_price,sale_price from goods where id='.$GLOBALS['id'];
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
          $GLOBALS['res']['data']['id'] = $GLOBALS['id'];
          $GLOBALS['res']['data']['name'] = $rows[0];
          $GLOBALS['res']['data']['purchasePrice'] = $rows[1];
          $GLOBALS['res']['data']['salePrice'] = $rows[2];
        }
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }

  goodsSearch();
  print_r(json_encode($res));