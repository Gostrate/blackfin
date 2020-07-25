<?php 

ini_set('display_errors','on');  
switch($_GET["view"]){
    case "annoucement":
    $json='[
        {
          "announcementType": "活動",
          "announcemntTitle": "公告標題",
          "announcementText": "公告內文",
          "announcementTime": "公告時間"
        },
        {
          "announcementType": "優惠",
          "announcemntTitle": "公告標題",
          "announcementText": "公告內文",
          "announcementTime": "公告時間"
        },
        {
          "announcementType": "公告",
          "announcemntTitle": "公告標題",
          "announcementText": "公告內文",
          "announcementTime": "公告時間"
        }
      ]';
        echo $json;
    break;
}

?>
