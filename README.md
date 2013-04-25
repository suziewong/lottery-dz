Discuz! 抽奖程序
=====================


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

### 关于抽奖策略

1-6 种中奖码，关于概率问题，抽奖图片（可以换）可以继续讨论
目前的概率和精弘币总数，时间等有关系

充分测试一下吧

