Discuz! 抽奖程序
=====================

#### 准备着手
对数据库表进行更改，增添奖品信息增删的接口。

#### 说明

事实上它不是一个标准的DZ插件，因为没有按照标准的DZ插件写法来写
不过可以基本实现操纵积分的目的

#### 使用方法

直接把这个文件移动到主目录下即可

#### 备注

* [Discuz!官网](http://www.discuz.net/)
* [Discuz!技术文库](http://dev.discuz.org/wiki/)
* [Discuz! 开放平台](http://open.discuz.net)
* [Discuz! 的插件机制](http://open.discuz.net/?ac=document&page=dev_plugin)
* [source/class/class_core.php](http://dev.discuz.org/wiki/index.php?title=%E6%8F%92%E4%BB%B6%E8%AE%BE%E8%AE%A1%E7%9A%84%E5%87%86%E5%A4%87%E5%B7%A5%E4%BD%9C)
* [Discuz! X2.5数据字典](http://dev.discuz.org/wiki/index.php?title=Discuz!_X2.5%E6%95%B0%E6%8D%AE%E5%AD%97%E5%85%B8)


#### 表结构

CREATE TABLE `lottery_action` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(50) NOT NULL COMMENT 'uid',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `result`   varchar(70)  NOT NULL DEFAULT '' COMMENT '中奖码',
  `message`  varchar(70)  NOT NULL DEFAULT '' COMMENT '具体信息',
  `time`  int  NOT NULL DEFAULT '0' COMMENT '抽奖时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='行为表';

CREATE TABLE `lottery_jp` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  PRIMARY KEY (`id`)  `num`  int NOT NULL COMMENT '奖品数目',
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='奖品数目表';

### 奖品素材
存在于 ./assets/slot/ 中  
按照 slotlineX.png (X为编号，例 1,2,3,4,5,...)
### 关于抽奖策略

1. get_current_user()
2. pay_jhb(value)
3. check_user_login()
4. check_session_avaliable()

API: 
js请求API.php
返回值：
    jsonp({index: 2}) // 返回当前抽中的号码索引。
其它错误情况返回:
     jsonp({message: }) 

API.php文件中通过匹配rand得到的数字进行获奖查询,并对相应数据库进行数量修改，保证抽奖结果所需奖品的数量不会超出提供的数量。

#### Copyright

浙江工业大学精弘网络