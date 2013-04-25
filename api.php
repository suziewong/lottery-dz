<?php
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json");

require '../source/class/class_core.php';

$discuz = & discuz_core::instance();
$discuz->init();

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if(!$_G['uid']){
    

   $url = "http://lo/bbs/member.php?mod=logging&action=login";
   // 判断用户是否登录
   //Header("Location: $url"); 
   //showmessage('抱歉，您尚未登录，无法进行此操作', $url, array(), array('refreshtime ' => 0));
   $data['error'] = '1';
   $data['message'] = "尚未登录";
   echo json_encode($data);
}
else
{
    //拿到用户uid
    $uid = $_G['uid'];
    $username = $_G['username'];
    //获取抽奖结果
    $return_result = mt_rand(-10,6);
    if($return_result > 0)
    {
        //预先扣掉10个精弘币
        update_jhb($uid,-10);
        //然后开始抽奖
        $update_jhb = choujiang($uid);
        update_jhb($uid,$update_jhb);
        $return_message = '恭喜您获得'.strval($update_jhb).'个精弘币';
    }
    else
    {
        update_jhb($uid,-10);
        $return_result = -1;
        $return_message = "对不起,没有中奖！";
    }
    //var_dump($_G);
    //intodb($uid,$username,$return_result,$return_message);
    $data = array();
    $data['code'] = $return_result;
    $data['message'] = $return_message;

    //echo "我当前的精弘币:".get_jhb($uid);
    //var_dump($data);
    echo json_encode($data);

}

/*
    获取精弘币
*/
function get_jhb($uid)
{

    $row = C::t('common_member_count')->fetch($uid);
    //精弘币
    $jhb = $row['extcredits1'];
    return $jhb;
}
/* 
    更改精弘币
*/
function update_jhb($uid,$jhb)
{
    $old_jhb = get_jhb($uid);

    $new_jhb = $old_jhb + $jhb;
    C::t('common_member_count')->update($uid, array('extcredits1' => $new_jhb));

}


/*
    产生一个0-200的基数
*/
function choujiang($uid)
{

    //获取该用户的精弘币
    $now_jhb = get_jhb($uid);

    //待考量的随机数
    $result =  mt_rand(0,100) - ($now_jhb % 10) + time() % 10;

    return $result;
}

/*
    每个人的抽奖记录和结果都存数据库
*/
function intodb($uid,$username,$result,$message)
{

    include_once 'class.mysql.php';
    include_once 'config.inc.php';
    $db = new mysql(HOST,USERNAME,PASSWORD,DBNAME,'lottery_action');

    $arr = array();
    $arr['uid']     = $uid;
    $arr['username'] = $username;
    $arr['result']  = $result;
    $arr['message'] = $message;
    $arr['time']    = time();
    $data = $db->insert($arr);

}

