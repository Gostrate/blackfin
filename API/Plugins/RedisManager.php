<?php
namespace Plugins;
use Redis;
use RedisCluster;
class RedisManager
{
    private $test_times = 0;
    private  $redis_que =array();
    private $redis;
    private $game_core;
    private $redis_type;
    private $is_online=false;//是否是线上版本，测试请使用false方能使用flush
    public function __construct($game_core, $adress, $port, $type = "")
    {
        $redis_type=$type;$type;
        if ($type == "cluster") {
            $servers = [
                "pts-math-redis.kmgpvk.clustercfg.apne1.cache.amazonaws.com:6379"
            ];
            $host = ['pts-math-redis.kmgpvk.clustercfg.apne1.cache.amazonaws.com:6379'];
            $this->redis = new \RedisCluster(null, $host);
            $this->game_core = $game_core;
        } else {
            $this->redis = new Redis();
            // //之後加上是本機還是遠端的設定
            $this->game_core = $game_core;
            $this->redis->connect($adress, $port);
        }
    }
    public function getData($parameter_name, $default_value = "NoData")
    {
        global $test_times;

        // echo $parameter_name;
        array_push($this->redis_que, $parameter_name);
        // print_r($this->redis_que);
        $test_times += 1;
        
            $data = $this->redis->get($this->game_core . $parameter_name);
            if ($data != "" && $data != null) {
                return $data;
            } else {
                return $default_value;
            }
    }
    public function setData($parameter_name, $value)
    {
        global $test_times;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        if ($value == "") {
            return $this->redis->del($this->game_core . $parameter_name);
        } else {
            return $this->redis->set($this->game_core . $parameter_name, $value);
        }
    }
    public function delKey($parameter_name)
    {
        global $test_times;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        return $this->redis->del($this->game_core . $parameter_name);
    }

    public function checkData($parameter_name)
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        try {
            return $this->redis->exists($this->game_core . $parameter_name);
        } catch (Exception $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    //List相关 v191007
    //塞资料到list
    public function lpushData($parameter_name, $value)
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "lPush-".$parameter_name);
        $test_times += 1;
        $this->redis->lPush($this->game_core . $parameter_name, $value);
        return $this->game_core . $parameter_name;
        
    }
    //取list长度
    public function llenData($parameter_name)
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        try {

            return $this->redis->llen($this->game_core . $parameter_name);
        } catch (Exception $e) {

            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    //取最上面一笔的list
    public function lpopData($parameter_name, $instead = "None")
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "lPop-".$parameter_name);
        $test_times += 1;
        $data = $this->redis->lPop($this->game_core . $parameter_name);
        if ($data != "" && $data != null) {
            return $data;
        } else {
            return $instead;
        }
    }

    public function rpopData($parameter_name, $instead = "None")
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "rPop-".$parameter_name);
        $test_times += 1;
        $data=$this->redis->rPop($this->game_core . $parameter_name);
        try {
            if(isset($data) && $data != "" && $data != null){
                return $data;
            }else{
                return $instead;
            }
        } catch (Exception $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    public function rpoplpush($parameter_name,$parameter_name2, $instead = "None"){
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "rpoplpush_".$parameter_name);
        $test_times += 1;
        $data = $this->redis->rpoplpush($this->game_core . $parameter_name,$this->game_core . $parameter_name2);
        if ($data != "" && $data != null) {
            return $data;
        } else {
            return $instead;
        }
    }

    //將指定數字加到該key上
    public function incrby($parameter_name, $number)
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        try {
            return $this->redis->incrbyfloat($this->game_core . $parameter_name, $number);
        } catch (Exception $e) {
            echo $parameter_name . "," . $number;
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public function flush()
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "flush");
        $test_times += 1;
        if($this->is_online){
            die("you can't flush redis with online version");
        }else if($this->redis_type==""){
            return $this->redis->flushAll();
        }else{
            return $this->redis->flushDb();
        }
    }
    public function addSet($parameter_name, $member)
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "sAdd-".$parameter_name);
        $test_times += 1;
        return $this->redis->sAdd($this->game_core . $parameter_name, $member);
    }
    public function addArrayToSet($parameter_name, $member){
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "sAddArray-".$parameter_name);
        $test_times += 1;
        // return $this->redis->sAdd($this->game_core . $parameter_name, ...$member);
       return $this->redis->sAddArray($this->game_core . $parameter_name, $member);
    }
    public function getSetMembers($parameter_name,$instead = "None")
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, "sMembers-".$parameter_name);
        $test_times += 1;
        $data = $this->redis->sMembers($this->game_core . $parameter_name);
        if ($data != "" && $data != null) {
            return $data;
        } else {
            return $instead;
        }
    }
    public function getSetRandomMember($parameter_name,$instead = "None")
    {
        global   $test_times;
        global $redis_que;
        array_push($this->redis_que, $parameter_name);
        $test_times += 1;
        $data = $this->redis->sRandMember($this->game_core . $parameter_name);
        if ($data != "" && $data != null) {
            return $data;
        } else {
            return $instead;
        }
    }
    public function addCronSQL($DB_name,$sql_name,$count,$redis_name){
        $arr=array(
            "sql"=>$sql_name,
            "count"=>$count,
            "db_name"=>$DB_name,
            "game_core"=>$this->game_core,
            "redis_name"=>$this->game_core . $redis_name
        );
        $this->redis->sAdd("need_cron_list", json_encode($arr));
    }
    public function getRedisUsedTimes()
    {
        global   $test_times;
        global $redis_que;
        return $test_times;
    }
    public function getRedisUsedThings()
    {
        global $redis_que;
        return json_encode($this->redis_que);
    }

    
    // if($location==0){
    //     //連到本機的redis
    //     $redis->connect('127.0.0.1', 6379);  

    // }else{
    //     $redis->connect('math-test-redis-001.kmgpvk.0001.apne1.cache.amazonaws.com', 6379);  

    // }
}
