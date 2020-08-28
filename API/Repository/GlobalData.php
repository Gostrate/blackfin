<?php

namespace Repository;

class GlobalData
{
    public $math_version=0.001;
    public $location = "1";//0=local ;1=test 2=finaltest
    private $host = '127.0.0.1:8889';
    private $dbuser = 'root';
    private $pwd  = 'root';
    private $dbname="blackfin";
    // private $redis_remain_num=5;
    private $redis_record_count=50;
    private $official_mode=false;//上線

// redis:pts-math-redis.kmgpvk.clustercfg.apne1.cache.amazonaws.com:6379
// mysql:pth-math-test.cluster-csqvxxeawkwb.ap-northeast-1.rds.amazonaws.com
// phpmyadmin:http://54.95.43.201:81/  下拉伺服器就可以看到另一台,帳秘一樣
 // private $enable_lose=500000000;//denom 值
    public function __construct()
    {
        if ($this->location == "1") {
            // $this->host = "localhost";
            // $this->dbuser = "root";
            // $this->pwd = "ma5tgb6yhntest";
            $this->host = "34.80.56.1";
            $this->dbuser = "gostrte";
            $this->pwd = "Lbj1868414";
            $this->dbname="mytest";

        }else if ($this->location == "2") {
            $this->host = "pth-math-test.cluster-csqvxxeawkwb.ap-northeast-1.rds.amazonaws.com";
            $this->dbuser = "root";
            $this->pwd = "ma5tgb6yhntest";
        }
    }
    public function __get($property_name)
    {
        if (isset($this->$property_name)) {
            return ($this->$property_name);
        } else {
            return ("no find");
        }
    }
    //__set()方法用来设置私有属性
    // public function __set($property_name, $value)
    // {
    //     $this->$property_name = $value;
    // }
}
