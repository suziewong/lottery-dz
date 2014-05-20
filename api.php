<?php
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json");

require '../source/class/class_core.php';
include_once 'class.mysql.php';
include_once 'config.inc.php';


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
    $randnum = mt_rand(-1000,1000);
    //$randnum = 1000;
    $data =array();
    $uidcjnum = cjnum($uid);
    //预先扣掉10个精弘币
    if($uidcjnum <= 10)
    {
        update_jhb($uid,-10);
    }
    else
    {

    }
    
    if($randnum > 885 && $uidcjnum <= 10)
    {

        //开始抽奖
        //三等奖
        
        if($randnum > 885 && $randnum <= 945)
        {
            // 迷你金属书签 
            if($randnum <= 905 )
            {

                $data = jpresult(1);
            }
            // A5初品笔记本 
            if($randnum > 905 && $randnum <= 915)
            {
                $data = jpresult(2);
            }
            // 卡通造型圆珠笔
            if($randnum > 915 && $randnum <= 930)
            {
                $data = jpresult(3);
            }
            // 木质卡通磁贴 
            if($randnum > 930 && $randnum <= 945)
            {
                $data = jpresult(4);
            }

        }
        //二等奖
        if($randnum > 945 && $randnum <= 975)
        {
           // 松紧带笔盒  
           if($randnum <= 950 )
           {
               $data = jpresult(5);
           }
           // 便携式折叠餐具盒 
           if($randnum > 950 && $randnum <= 960)
           {
               $data = jpresult(6);
           }
           // 长脖子斑马中性笔
           if($randnum > 960 && $randnum <= 975)
           {
               $data = jpresult(7);
           }
        }
        //一等奖
        if($randnum > 975 && $randnum <= 990 )
        {
            // 创意迷你植物桌面小盆栽鸟叔 
            if($randnum > 975 && $randnum <= 983)
            {
                $data = jpresult(8);
            }
            // 按扣式收纳盒/多用储物盒 
           if($randnum > 983 && $randnum <= 990)
           {
               $data = jpresult(9);
           } 
        }
        //神秘大奖
        if($randnum > 990 && $randnum <= 995 )
        {
            $data = jpresult(10);
        }
        //特等奖
        if($randnum > 995 && $randnum <= 1000 )
        {
            $data = jpresult(11);
        }
        //echo cjnum($uid);
        /*$update_jhb = choujiang($uid);
        
        update_jhb($uid,$update_jhb-10);
        $return_message = '恭喜您获得'.strval($update_jhb).'个精弘币';*/

    }
    else
    {
       
        if($uidcjnum > 10)
        {
            $data['code'] = -1;
            $data['message'] = "你已经达到抽奖次数上限10次了";
        }
        else
        {

            $data['code'] = -1;
            $data['message'] = "对不起,没有中奖！";
        }
    }
    //var_dump($_G);
    //var_dump($data);
    $return_result = $data['code'];
    $return_message = $data['message'];
    intodb($uid,$username,$return_result,$return_message);
//    $data = array();
    $data['code'] = $data['code']-1;
   // $data['message'] = $return_message;

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
    $db = new mysql(HOST,USERNAME,PASSWORD,DBNAME,'lottery_action');
    $arr = array();
    $arr['uid']     = $uid;
    $arr['username'] = $username;
    $arr['result']  = $result;
    $arr['message'] = $message;
    $arr['time']    = time();
    $data = $db->insert($arr);

}

/*
    设置每个童鞋的抽奖上限次数
*/
function cjnum($uid)
{
    $db = new mysql(HOST,USERNAME,PASSWORD,DBNAME,'lottery_action');

    $cjnum = $db->count("uid",$uid);

    return $cjnum;
}

/*
    返回某个奖品的数目
*/
function prizenum($jpid)
{
    $db = new mysql(HOST,USERNAME,PASSWORD,DBNAME,'lottery_jp');

    $data['id'] = $jpid;
    $cjnum = $db->find($data);

    return $cjnum[0]['num'];
}
/*
    减去某个奖品的个数
*/
function updatejp($jpid)
{
    $db = new mysql(HOST,USERNAME,PASSWORD,DBNAME,'lottery_jp');
    $result= $db->reduce($jpid);
}

/*
    关于奖品的计算   
*/
function jpresult($jpid)
{
    $jp = array();
    $jp[1] = "迷你金属书签";
    $jp[2] = "A5初品笔记本";
    $jp[3] = "卡通造型圆珠笔";
    $jp[4] = "木质卡通磁贴";
    $jp[5] = "松紧带笔盒";
    $jp[6] = "便携式折叠餐具盒";
    $jp[7] = "长脖子斑马中性笔";
    $jp[8] = "创意迷你植物桌面小盆栽";
    $jp[9] = "按扣式收纳盒/多用储物盒";
    $jp[10] = "安藤木子扑克牌";
    $jp[11] = "大肚小口马克杯";

    $jpnum =  prizenum($jpid);
    $result = array();
    if($jpnum > 0)
    {
        //将奖品数减一
        updatejp($jpid);
        $result['code'] = $jpid;
        $result['message'] = "恭喜你获得".$jp[$jpid];   
    }
    else
    {
        $result['code'] = -1;
        $result['message'] = "对不起,没有中奖！";
    }
    return $result;
}
