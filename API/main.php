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
          $input_arr = json_decode($funcPack->ccgetfrominput(), 1);
          print_r($input_arr);
          // print_r($input_arr);
          if ($input_arr["announcementType"] != "公告" && $input_arr["announcementType"] != "優惠" && $input_arr["announcementType"] != "活動") {
            $funcPack->printWord("input_error:Without this type: " . __LINE__);
          }
          // $funcPack->printWord( $input_arr["announcementStartTime"]);

          $sql = 'INSERT INTO `annoucements` (`id`, `add_time`, `show_time`, `end_time`, `type`, `title`, `content`) VALUES (NULL, NULL,"' . $input_arr["announcementStartTime"] . '","' . $input_arr["announcementEndTime"] . '" ,"'   . $input_arr["announcementType"] . '   ",     "' . $input_arr["announcementTitle"] . ' " , "' . $input_arr["announcementText"] . '")';

          // $sql='SELECT * FROM `annoucements`';
          // echo $sql;
          $responseArr = $funcPack->postData($sql);
          // $funcPack->printWord($responseArr);
          $json='{"isSucess":"OK"}';
          break;
        case "get":
          $json = '[
            {
              "announcementType": "活動",
              "announcementTitle": "公告標題",
              "announcementText": "公告內文",
              "announcementTime": "公告時間"
            },
            {
              "announcementType": "優惠",
              "announcementTitle": "公告標題",
              "announcementText": "公告內文",
              "announcementTime": "公告時間"
            },
            {
              "announcementType": "公告",
              "announcementTitle": "公告標題",
              "announcementText": "公告內文",
              "announcementTime": "公告時間"
            }
          ]';
          echo $json;
          break;
        
      }
      break;
  }
  
}
