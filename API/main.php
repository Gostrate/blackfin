<?php

use Plugins\FuncPack;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:POST,GET");
header("Access-Control-Allow-Headers:x-requested-with,content-type");
header("Content-type:text/json;charset=utf-8");

ini_set('max_execution_time', '0');
ini_set('display_errors', '1');
error_reporting(E_ALL);
require 'autoload.php';
$funcPack = new Plugins\FuncPack();
//test
if (isset($_GET["view"])) {
  switch ($_GET["view"]) {
    case "annoucement":
      switch ($_GET["type"]) {
        case "post":
          //新增公告_貼子   公告、優惠、活動
          $input_arr = json_decode($funcPack->ccgetfrominput(), true);
          // print_r($input_arr);
          // echo($input_arr["announcementType"]);
          // print_r($input_arr);
          if ($input_arr["announcementType"] != "公告" && $input_arr["announcementType"] != "優惠" && $input_arr["announcementType"] != "活動") {
            $funcPack->printWord("input_error: Without this type: " . __LINE__);
          }
          // $funcPack->printWord( $input_arr["announcementStartTime"]);

          $sql = 'INSERT INTO `annoucements` (`id`, `add_time`, `show_time`, `end_time`, `type`, `title`, `content`) VALUES (NULL, NULL,"' . $input_arr["announcementStartTime"] . '","' . $input_arr["announcementEndTime"] . '" ,"'   . $input_arr["announcementType"] . '   ",     "' . $input_arr["announcementTitle"] . ' " , "' . $input_arr["announcementText"] . '")';

          // $sql='SELECT * FROM `annoucements`';
          // echo $sql;
          $responseArr = $funcPack->postData($sql);
          // $funcPack->printWord($responseArr);
          echo '{"isSucess":"OK"}';

          break;
        case "get":
          $json = '[
            {
              "announcementType": "活動",
              "announcementTitle": "夏日水槍節",
              "announcementText": "2020 年最消暑的夏季活動來了！一年一度的新村水槍節即將在今年7 / 6 - 7 / 7舉辦，除了最讓人期待的水槍大戰之外，還有泡泡攻勢、DJ表演！快加入首爾年輕人潮流，在封街的市中心，用水槍消除煩悶又惱人的熱氣吧！",
              "announcementTime": "2020-08-19 16:53"
            },
            {
              "announcementType": "活動",
              "announcementTitle": "今夏天體營",
              "announcementText": "2020 年最消暑的夏季活動來了！一年一度的新村水槍節即將在今年8 / 1 - 8 / 5舉辦，除了最讓人期待的天體大戰之外，還有泡泡攻勢、DJ表演！快加入首爾年輕人潮流，在封街的市中心，用水槍消除煩悶又惱人的熱氣吧！",
              "announcementTime": "2020-08-19 16:53"
            },
            {
              "announcementType": "活動",
              "announcementTitle": "蘭嶼烤肉趴",
              "announcementText": "2020 年最消暑的夏季活動來了！一年一度的新村水槍節即將在今年7 / 6 - 7 / 7舉辦，除了最讓人期待的烤肉活動之外，還有泡泡攻勢、DJ表演！快加入首爾年輕人潮流，在封街的市中心，用水槍消除煩悶又惱人的熱氣吧！",
              "announcementTime": "2020-08-11 16:53"
            },
            {
              "announcementType": "優惠",
              "announcementTitle": "夏日瘋搶",
              "announcementText": "即刻起，使用BOOKING.COM預訂房間，即享15%回饋金，數量有限!",
              "announcementTime": "2020-08-16 16:53"
            },
            {
              "announcementType": "優惠",
              "announcementTitle": "黑鰭周年慶",
              "announcementText": "歡慶黑鰭2周年，即刻起，使用BOOKING.COM預訂房間，即享20%折扣，數量有限!",
              "announcementTime": "2020-08-17 16:53"
            },
            {
              "announcementType": "優惠",
              "announcementTitle": "夏日狂歡",
              "announcementText": "8 / 2 - 8 / 5 ，住宿享免費潛水，數量有限!",
              "announcementTime": "2020-08-13 16:53"
            },
            {
              "announcementType": "公告",
              "announcementTitle": "暫停營業",
              "announcementText": "因龍五颱風來襲，今日暫停營業，請大家小心注意安全，黑鰭飲冰宿關心您。",
              "announcementTime": "2020-08-18 16:53"
            },
            {
              "announcementType": "公告",
              "announcementTitle": "暫停營業",
              "announcementText": "因柳丁颱風來襲，今日暫停營業，請大家小心注意安全，黑鰭飲冰宿關心您。",
              "announcementTime": "2020-08-20 16:53"
            },
            {
              "announcementType": "公告",
              "announcementTitle": "暫停營業",
              "announcementText": "因小民颱風來襲，今日暫停營業，請大家小心注意安全，黑鰭飲冰宿關心您。",
              "announcementTime": "2020-08-21 16:53"
            }
          ]';
          echo $json;
          break;
        
      }
      break;
  }
  
}