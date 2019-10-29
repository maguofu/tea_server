<?php
  // 接口输出
  $res = array(
    'errNo' => 0,
    'errStr' => 'success',
    'data' => array(
      'recommondList' => array()
    )
  );

  function selectRecommond() {
    $link = new mysqli('localhost', 'root', '123456', 'mydb');
    if ($link->connect_errno == 0) {
      $selectSql = 'select id,img_url_list_str,description,name from goods';
      $res = $link->query($selectSql);
      if (!$link->query($selectSql)) {
        $GLOBALS['res']['errNo'] = 502;
        $GLOBALS['res']['errStr'] = '数据查询失败';
      } else {
        $rows = $res->fetch_all();
        $printData = array();
        for ($i=0; $i < count($rows); $i++) {
          $ele = $rows[$i][1];
          if (strpos($ele, ';')) {
            $ele = explode(';', $ele)[0];
          }
          $printData[$i] = array(
            'id' => $rows[$i][0],
            'img' => $ele,
            'content' => $rows[$i][2],
            'name' => $rows[$i][3]
          );
        }
        $GLOBALS['res']['data']['recommondList'] = $printData;
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }

  selectRecommond();
  print_r(json_encode($res));