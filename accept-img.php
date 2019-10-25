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
    $sql = '';
    if ($_POST['id']) {
      updateData($sql, $link);
    } else {
      insertData($sql, $link);
    }
  }

  /**
   * 接收的参数没有id，那么就是新录入的
   * 执行插入语句
   * params $sql要执行的sql语句
   * params $link已经链接的数据库
   */
  function insertData($sql, $link) {
    $sql = 'insert into goods (name, purchase_price, sale_price, description, img_url_list_str)
      values("'.$_POST['name'].'",'.$_POST['inComePrice'].','.$_POST['salePrice'].',"'.$_POST['description'].'","'.$GLOBALS["urlListStr"].'")';
    if ($link->connect_errno == 0) {
      if(!$link->query($sql)){
        $GLOBALS['res']['errNo'] = 502;
        $GLOBALS['res']['errStr'] = '数据插入失败';
      } else {
        $GLOBALS['res']['data'] = $GLOBALS['picUrlList'];
      }
    } else {
      $GLOBALS['res']['errNo'] = 501;
      $GLOBALS['res']['errStr'] = '数据库链接失败';
    }
  }

  /**
   * 接收的参数有id，那么就是更新数据
   * 执行更新语句
   * params $sql要执行的sql语句
   * params $link已经链接的数据库
   */
  function updateData($sql, $link){
    echo '666';
  }

  savePicEchoUrl();
  setGoodsDataIntoTable();

  print_r(json_encode($res));