<?php
  // 获取post提交参数
  $id = $_POST['id'];
  $name = $_POST['name'];
  $inComePrice = $_POST['inComePrice'];
  $salePrice = $_POST['salePrice'];
  $description = $_POST['description'];
  $acceptImgList = $_POST['picData'];

  // 存储图片生成的url
  $picUrlList = array();
  $urlListStr = '';

  // 接口输出给前端的数据
  $res = array(
    'errNo' => 0,
    'errStr' => 'success',
    'data' => array(
      'urlList' => $picUrlList
    )
  );
  
  /**
   * 转码base64数据并存储
   * 修改$picUrlList
   */
  function savePicEchoUrl() {
    for($i = 0; $i < count($GLOBALS['acceptImgList']); $i++){
      $base_img = $GLOBALS['acceptImgList'][$i];
      $base_img = explode('base64,', $base_img)[1];
      $base_url = 'https://www.greenteaxinyang.com/img/';
      $path = '../img/';
      $output_file = time().rand(100,999).'.jpg';
      $path = $path.$output_file;
      //  创建将数据流文件写入我们创建的文件内容中
      file_put_contents($path, base64_decode($base_img));
      $currentUrl = $base_url.$output_file;
      array_push($GLOBALS['picUrlList'], $currentUrl);
      if ($GLOBALS['urlListStr']) {
        $GLOBALS['urlListStr'] = $GLOBALS['urlListStr'].';'.$currentUrl;
      } else {
        $GLOBALS['urlListStr'] = $currentUrl;
      }
    }
  }

  /**
   * 图片存储之后将数据写到goods表中
   */
  function setGoodsDataIntoTable() {
    $link = new mysqli('localhost', 'root', '123456', 'mydb');
    if ($_POST['id']) {
      updateData($link);
    } else {
      insertData($link);
    }
  }

  /**
   * 接收的参数没有id，那么就是新录入的
   * 执行插入语句
   * params $link已经链接的数据库
   */
  function insertData($link) {
    $sql = 'insert into goods (name, purchase_price, sale_price, description, img_url_list_str)
      values("'.$_POST['name'].'",'.$_POST['inComePrice'].','.$_POST['salePrice'].',"'.$_POST['description'].'","'.$GLOBALS["urlListStr"].'")';
    if ($link->connect_errno == 0) {
      if(!$link->query($sql)){
        $GLOBALS['res']['errNo'] = 502;
        $GLOBALS['res']['errStr'] = '数据插入失败';
      } else {
        $GLOBALS['res']['data']['urlList'] = $GLOBALS['picUrlList'];
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }

  /**
   * 接收的参数有id，那么就是更新数据
   * 执行更新语句
   * params $link已经链接的数据库
   */
  function updateData($link){
    // 查询sql
    $selectSql = 'select name,purchase_price,sale_price,description,img_url_list_str from goods where id='.$GLOBALS['id'];
    if ($link->connect_errno == 0) {
      $res = $link->query($selectSql);
      if(!$res){
        $GLOBALS['res']['errNo'] = 502;
        $GLOBALS['res']['errStr'] = '数据查询失败';
      } else {
        $rows = $res->fetch_row();
        // 没有查到数据
        if (!count($rows)) {
          $GLOBALS['res']['errNo'] = 503;
          $GLOBALS['res']['errStr'] = '请检查id是否正确！id不存在';
        } else {
          // 查询成功，格列的值
          $name_row = $rows[0];
          $purchase_price_row = $rows[1];
          $sale_price_row = $rows[2];
          $description_row = $rows[3];
          $img_url_list_str_row = $rows[4];

          // 要更新的值
          $update_name = $GLOBALS['name'] ? $GLOBALS['name'] : $name_row;
          $update_purchase_price = $GLOBALS['inComePrice'] ? $GLOBALS['inComePrice'] : $purchase_price_row;
          $update_sale_price = $GLOBALS['salePrice'] ? $GLOBALS['salePrice'] : $sale_price_row;
          $update_description = $GLOBALS['description'] ? $GLOBALS['description'] : $description_row;
          $update_img_url_list_str = $img_url_list_str_row ? $img_url_list_str_row.';'.$GLOBALS['urlListStr'] : $GLOBALS['urlListStr'];

          // 更新sql
          $updateSql = 'update goods set name="'.$update_name.
                '",purchase_price='.$update_purchase_price.
                ',sale_price='.$update_sale_price.
                ',description="'.$update_description.
                '",img_url_list_str="'.$update_img_url_list_str.
                '" where id='.$GLOBALS['id'];
          // 更新失败
          if (!$link->query($updateSql)) {
            $GLOBALS['res']['errNo'] = 502;
            $GLOBALS['res']['errStr'] = '数据更新失败';
          } else {
            $GLOBALS['res']['data']['urlList'] = $GLOBALS['picUrlList'];
          }
        }
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }

  savePicEchoUrl();
  setGoodsDataIntoTable();

  print_r(json_encode($res));