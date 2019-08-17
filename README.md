### 车票100在线订票接口(V30_Beat版)

#### 1 概述
> 火车票在线订票接口(API),是为了第三方网站快速接入官网而开发的接口；客户端与接口使用简单的封装协议通讯，大大减化了第三方在线订票的接入。

#### 2 接口介绍
>  接口对外数据全部以http协议发布，请求全部以POST方式，返回数据全部是JSON格式, 除非有“选填”说明，全部请求参数都是必填。

##### 2.1 用户授权
**测试期暂时免费，合作联系QQ：89914505 / 2994273988**
演示地址：http://v3.chepiao100.com/demo/bus.php

```php
// 测试账号
测试账号：
ACCESSID： 10000
ACCESSKEY：9f6e6800cfae7749eb6c486619254b9c
```

```php
//HTTP协议格式
POST  / HTTP/1.1
Host  openapi.chepiao100.com
ACCESSID 10000
ACCESSKEY 9f6e6800cfae7749eb6c486619254b9c
```

```php
// PHP CURL Demo
$header => [ 
     'ACCESSID: 10000',
    'ACCESSKEY: 9f6e6800cfae7749eb6c486619254b9c'
 ];
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

```

##### 2.2 字符编码
>  数据中心接口接收和返回统一以UTF-8字符集编码格式进行接收返回


#### 3 用户登录
> 使用12306.cn账号进行授权登录

##### 3.1 登录流程
  
![](https://github.com/phpbin/chepiao100/blob/master/css/images/img3.1.png)

##### 3.2 获取Token
地址：http://v3.chepiao100.com/v30.api?do=train.user.token

正确返回
```
array(3) {
  ["errMsg"]=>
  string(1) "Y"   // errMsg == Y 表示数据正常
  ["status"]=>
  int(1)
  ["datas"]=>
  array(1) {
    ["token"]=>
    string(32) "410c36e7a4da82fb4546611b3a9a0148"  // 生成Token值
  }
}
```

错误返回
```
  ["errMsg"]=>
  string(1) "生成Token失败!"
  ["errcode"]=>
  string() "-10000" // 错误代码
}
```

##### 3.3 生成验证码
地址：http://v3.chepiao100.com/v30.api?do=train.captcha.base64

|函数/参数|用途|
|---|---|
|token| Token值 |
|module | 登录时login, 提交订单时passenger |

返回数据
```
array(2) {
  ["errMsg"]=>
  string(1) "Y"
  ['datas'] => 
    ["base64"]=>
  	string(18856) ""  // 验证码Base64位编码
```

##### 3.4 验证码识别
> 暂时只支持  http://www.yundama.com/  
  
地址：http://v3.chepiao100.com/v30.api?do=train.ocr.yundama

|函数/参数|用途|
|---|---|
|token| Token值 |
|base64 | 图片Base64编码 |
| username | 打码平台账号 |
| password | 打码平台密码  |

返回数据
```
array(3) {
  ["errMsg"]=>
  string(1) "Y"
  ['datas'] =>
    ["result"]=>
  	string(6) "253,43|34,45|32,353"   //识别结果用于前端展示
  	["answer"]=>
  	string(6) "253,43,34,45,32,353"   //识别结果用于提交保存
}
```

##### 3.5 验证码校验
地址：http://v3.chepiao100.com/v30.api?do=train.captcha.check 

|函数/参数|用途|
|---|---|
|token| Token值 |
| module | 登录时login, 提交订单时passenger |
| answer | 验证码坐标 |

返回数据
```
array(2) {
  ["errMsg"]=>
  string(1) "Y"
  ["message"]=>
  string(21) "验证码校验成功"  
}
```

错误返回
```
array(2) {
  ["errMsg"]=>
  string(1) "验证失败!"
  ["errcode"]=>
  string(21) "-10006"  
}
```

##### 3.6 用户登录
地址：http://v3.chepiao100.com/v30.api?do=train.user.login  

|函数/参数|用途|
|---|---|
|token| Token值 |
| username | 用户名  |
| password | 密码 |
| answer | 验证码坐标 |

返回数据
```
array(4) {
  ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["user_name"]=>
    string(9) "马云"   // 用户姓名
    ["user_regard"]=>
    string(19) "先生,下午好！"
  }
  ["message"]=>
  string(12) "登录成功"
}
```

错误返回
```
array(2) {
  ["errMsg"]=>
  string(1) "登录失败"
  ["errcode"]=>
  string(21) "-10004"
}
```

##### 3.7 用户信息
> 判断当前token是否已过期  

地址：http://v3.chepiao100.com/v30.api?do=train.user.info

返回数据
```
array(4) {
  ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(3) {
    ["user_name"]=>
    string(9) "马云"
    ["email"]=>
    string(19) "mayun@alibaba.com"
    ["user_regard"]=>
    string(19) "先生,下午好！"
  }
}
```

##### 3.8 登录状态判断
> 判断当前token是否已过期  

地址：http://v3.chepiao100.com/v30.api?do=train.user.auth

|函数/参数|用途|
|---|---|
|token| Token值 |

返回数据
```
array(4) {
  ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["message"]=>
  string(12) "验证通过!"
}
```


#### 4 火车票查询
##### 4.1 余票查询
>  混合接口：余票使用官网，票价使用第三方数据  
  
地址：http://v3.chepiao100.com/v30.api?do=train.ticket.query

|函数/参数|用途|
|---|---|
| from_station | 出发站 UTF8  |
| to_station | 到达站 UTF8 |
| train_date | 乘车日期  YYYY-MM-DD|

返回数据
```
  ["errMsg"]=>
  string(1) "Y"   // 请求成功
  ["status"]=>
  int(1)
  ["datas"]=>
  array(22) {
    ....
    [0]=>
    array(18) {
      ["train_code"]=>
      string(5) "K8371"      // 车次
      ["start_station"]=>
      string(6) "徐州"       // 如发
      ["end_station"]=>   
      string(6) "江山"       // 终到
      ["from_station"]=>
      string(9) "上海南"     // 出发
      ["to_station"]=>
      string(6) "松江"       // 到站
      ["start_time"]=>
      string(5) "05:41"      // 发时
      ["arrive_time"]=>
      string(5) "06:02"      // 到时
      ["cost_time"]=>
      string(5) "00:21"      // 历时
      ["mileage"]=>
      int(42)                // 里程
      ["train_class"]=>
      string(6) "普快"       // 等级
      ["day_diff"]=>
      string(6) "当日"       // 到达日期
      ["can_buy"]=>
      string(1) "Y"         // 是否可以购买
      ["from_flag"]=>
      string(5) "cross"     // 发站标识 start 如,  cross过
      ["to_flag"]=>
      string(5) "cross"     // 到站标识 end 终, cross过
      ["train_date"]=>
      string(10) "2019-08-30"  //乘车时间
      ["yp_info"]=>   // 余票和票价
      array(4) {
        ...
        [0]=>
        array(4) {
          ["id"]=>
          string(1) "4"   // 席别ID
          ["na"]=>
          string(6) "软卧" // 席别名称
          ["yp"]=> 
          string(1) "3"   // 余票量
          ["pr"]=>
          int(80)         // 价格
        }
        ...
      }
      ["order_token"]=>
      string(456) ""     // 订票Token
      ["button_text"]=>  
      string(6) "预订"   // 订票Button显示文本
    }
    ....
  }
```


##### 4.2 车次查询
>  接口说明：使用第三方数据  
  
地址：http://v3.chepiao100.com/v30.api?do=train.ticket.code

|函数/参数|用途|
|---|---|
| train_code | 车次 (大写)  |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(3) {
    ["summ"]=>
    array(9) {               // 车次摘要数据
      ["train_code"]=>
      string(4) "K287"       // 车次
      ["train_class"]=>
      string(6) "快速"       // 等级
      ["start_station"]=>
      string(6) "南昌"       // 始发
      ["end_station"]=>
      string(9) "上海南"     // 终到
      ["start_time"]=>
      string(5) "06:24"      // 发时
      ["arrive_time"]=>
      string(5) "20:24"      // 到时
      ["cost_time"]=>
      string(15) "10小时0分钟"  // 历时
      ["mileage"]=>
      string(9) "786公里"      // 里程
      ["pr_info"]=>            // 票价信息
      array(4) {
        .....
        [0]=>  
        array(2) {
          ["na"]=>
          string(6) "硬座"    // 席别
          ["pr"]=>
          int(105)           // 票价
        }
        ....
      }
    }
    ["head"]=>
    array(10) {
      [0]=>
      string(6) "站次"
      [1]=>
      string(6) "站名"
      [2]=>
      string(6) "日期"
      [3]=>
      string(6) "到达"
      [4]=>
      string(6) "开车"
      [5]=>
      string(6) "停车"
      [6]=>
      string(6) "里程"
      [7]=>
      string(6) "硬座"
      [8]=>
      string(9) "硬卧下"
      [9]=>
      string(9) "软卧下"
    }
    ["item"]=>
    array(7) {
      ...
      [0]=>
      array(10) {
        [0]=>
        int(1)
        [1]=>
        string(9) "上海南"
        [2]=>
        string(1) "-"
        [3]=>
        string(9) "起点站"
        [4]=>
        string(5) "20:24"
        [5]=>
        string(1) "-"
        [6]=>
        int(0)
        [7]=>
        int(0)
        [8]=>
        string(1) "0"
        [9]=>
        string(1) "0"
      }
      ...
    }
  }
}
```

##### 4.3 车站查询
>  接口说明：使用第三方数据  
  
地址：http://v3.chepiao100.com/v30.api?do=train.ticket.station

|函数/参数|用途|
|---|---|
| station_name | 车站名称  |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(79) {
    [0]=>
    array(10) {
      ["train_code"]=>
      string(5) "G1355"          // 车次
      ["start_station"]=>
      string(12) "上海虹桥"      // 如发
      ["end_station"]=>
      string(9) "长沙南"         // 终到
      ["start_time"]=>
      string(5) "13:47"         // 发时
      ["arrive_time"]=>
      string(5) "19:25"         // 到时
      ["cost_time"]=>
      string(12) "5小时38分"    // 历时
      ["train_class"]=>
      string(12) "高速动车"     // 等级
      ["start_flag"]=>
      string(5) "cross"         // start 始发 cross 过路
      ["end_flag"]=>
      string(5) "cross"         // end 终到 cross 过路
      ....
      ["pr_info"]=>
      array(4) {                // 票价信息
        ....
        [0]=>
        array(2) {
          ["pr"]=>
          float(798.5)         // 票价
          ["na"]=>
          string(9) "一等座"    // 席别
        }
        .....
      }
    }
    ....
  }
}
```

##### 4.4 正晚点查询
  
地址：http://v3.chepiao100.com/v30.api?do=train.ticket.dynamic

|函数/参数|用途|
|---|---|
| station_type | 类型:  0到达 1出发  |
| train_code | 车次  |
|station_name | 车站 |

返回数据
```
  ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["message"]=>
    string(54) "预计Z282次列车，上海南站到达时间为19:02"   // 正晚点信息
    ["attention"]=>
    string(63) "查询结果仅作为参考，准确信息以车站公告为准" // 注意
  }
```

#### 5 在线订票
##### 5.1 订票流程图
  
![](https://github.com/phpbin/chepiao100/blob/master/css/images/img5.1.png)

##### 5.2 订单信息
地址：http://v3.chepiao100.com/v30.api?do=train.order.submit

|函数/参数|用途|
|---|---|
| token | Token |
| from_station | 出发站 UTF8  |
| to_station | 到达站 UTF8 |
| train_date | 乘车日期  YYYY-MM-DD|
| order_token | order_token 余票查询中获取  |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(10) {
    ["train_date"]=>
    string(10) "2019-08-31"   // 乘车日期
    ["train_week"]=>
    string(6) "周六"          // 星期几
    ["train_code"]=>
    string(4) "G102"          // 车次
    ["from_station"]=>
    string(12) "上海虹桥"     // 出发
    ["to_station"]=>
    string(9) "北京南"        // 到达
    ["start_time"]=>
    string(5) "06:26"        // 发时
    ["arrive_time"]=>
    string(5) "12:29"        // 到时
    ["cost_time"]=>
    string(5) "06:03"        // 历时
    ["yp_info"]=> 
    array(3) {              // 余票信息
      ...
      [0]=>
      array(4) {
        ["id"]=>
        string(1) "9"       // 席别ID
        ["na"]=>
        string(9) "商务座"  // 席别名称
        ["yp"]=>
        string(2) "11"      // 余票量
        ["pr"]=>
        int(1748)           // 票价
      }
      ...
    }
    ["ticket_token"]=>
    string(752) ""  //  订票Token (很重要)
  }
}
```


##### 5.3 订单检查
地址：http://v3.chepiao100.com/v30.api?do=train.order.check

|函数/参数|用途|
|---|---|
| token | Token |
| seat_type | 席别， 多个人用逗号隔开|
| ticket_type | 票种, 多个人用逗号隔开  |
| passenger_name | 乘车人, 多个人用逗号隔开 |
| passenger_id_type | 乘车人证件类型， 多个人用逗号隔开  |
| passenger_id_no  | 乘车人证件号, 多个人用逗号隔开  |
| allEncStr| 乘车人密钥, 多个人用逗号隔开  |
| ticket_token | ticket_token订票Token | 

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["is_captcha"]=>
    string(1) "N"       // 是否需要验证码 Y表示需要 N表示不需要
    ["is_choose"]=>
    string(1) "Y"        // 是否可以选席别
  }
```

##### 5.4 提交队列
地址：http://v3.chepiao100.com/v30.api?do=train.order.queue

|函数/参数|用途|
|---|---|
| token | Token |
| seat_type | 席别， 多个人用逗号隔开|
| choose_seat | 位置(普通车为空),  多个人用逗号隔开|
| ticket_type | 票种, 多个人用逗号隔开  |
| passenger_name | 乘车人, 多个人用逗号隔开 |
| passenger_id_type | 乘车人证件类型， 多个人用逗号隔开  |
| passenger_id_no  | 乘车人证件号, 多个人用逗号隔开  |
| allEncStr| 乘车人密钥, 多个人用逗号隔开  |
| ticket_token | ticket_token订票Token | 

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["count"]=>
    string(1) "0"    // 排队人数
    ["ticket"]=>
    string(3) "850"  // 实际余票数
  }
```

##### 5.5 确认提交
> 如果 is_choose == Y可选席别位置，否则choose_seat必需为空
  
地址：http://v3.chepiao100.com/v30.api?do=train.order.confirm

|函数/参数|用途|
|--|--|
| token | Token |
| seat_type | 席别， 多个人用逗号隔开|
| choose_seat | 位置(普通车为空),  多个人用逗号隔开|
| ticket_type | 票种, 多个人用逗号隔开  |
| passenger_name | 乘车人, 多个人用逗号隔开 |
| passenger_id_type | 乘车人证件类型， 多个人用逗号隔开  |
| passenger_id_no  | 乘车人证件号, 多个人用逗号隔开  |
| allEncStr| 乘车人密钥, 多个人用逗号隔开  |
| ticket_token | ticket_token订票Token | 


返回数据
```
{
	"errMsg": "Y",
	"status": 1,    // status == 1表示已成功提交到队列
	"message": "订单提交成功，请刷新wait接口!"
}
```

##### 5.6 等待订单
> 提交confirm后，需要多次调用该接口获取订单的状态，如果出现订单号表示提交成功
  
地址：http://v3.chepiao100.com/v30.api?do=train.order.wait

|函数/参数|用途|
|--|--|
| token | Token |
| ticket_token | ticket_token订票Token | 

返回数据
```
{
	"errMsg": "Y",
	"status": 1,
	"datas": {
		"request_id": 6.568448843159e+18,  // 队列ID，不用处理
		"sequence_no": "E325390225" // 订单号
	},
	"message": "订单已提交成功!"
}
```

##### 5.7 订单确认
>  最后需认订单是否提交成功, errMsg==Y &&status==1 表示已经提交成功了
  
地址：http://v3.chepiao100.com/v30.api?do=train.order.result

|函数/参数|用途|
|--|--|
| token | Token |
| sequence_no | sequence_no 订单号 |

返回数据：
```
{
	"errMsg": "Y",
	"status": 1,    
	"datas": {
		"sequence_no": "E325390225" // 订单号
	},
	"message": "订单已确认提交成功!"
}
``` 


#### 6 订单支付
##### 6.1 支付流程
  
![](https://github.com/phpbin/chepiao100/blob/master/css/images/img6.1.png)

##### 6.2 支付方式
 地址：http://v3.chepiao100.com/v30.api?do=train.pay.gateway

|函数/参数|用途|
|--|--|
| token | Token |
| sequence_no | sequence_no 订单号 |

返回数据
```
  ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["banks"]=>
    // 支付ID => 支付(银行)名称 
    array(10) {
      ["01020000"]=>
      string(12) "工商银行"
      ["01030000"]=>
      string(12) "农业银行"
      ["01040000"]=>
      string(12) "中国银行"
      ["01050000"]=>
      string(12) "建设银行"
      ["03080000"]=>
      string(12) "招商银行"
      ["01009999"]=>
      string(12) "邮储银行"
      ["00011000"]=>
      string(12) "中国银联"
      ["00011001"]=>
      string(15) "中铁银通卡"
      [33000010]=>
      string(9) "支付宝"
      [33000020]=>
      string(12) "微信支付"
    }
    ["pay_token"]=>
    string(3088) "" // 支付token
  }
```

##### 6.3 支付跳转
 地址：http://v3.chepiao100.com/v30.api?do=train.pay.epay

|函数/参数|用途|
|--|--|
| token | Token |
| sequence_no | sequence_no 订单号 |
| bankId | 支付/银行ID  |
| pay_token | 支付Token |

> 根据返回的结果，组合成Form表单提交到支付页

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
    ["action"]=>
    string(37) "https://payapp.weixin.qq.com/t/webpay"   // 跳转地址
    ["formdata"]=>
    array(14) {
      .....
      //key值 => value值
      ["body"]=>
      string(1) "-"
      ["notify_url"]=>
      string(82) "http://epay.12306.cn/pay/payResponseC2?TRANS_ID=W20190817244386477&bankId=33000020"
      ["appid"]=>
      string(18) "wx5ef307634fd9d8b5"
      ....
    }
```


#### 8 订单管理
##### 8.1 未支付订单
 地址：http://v3.chepiao100.com/v30.api?do=train.order.unpaid

|函数/参数|用途|
|--|--|
| token | Token |

返回数据
```
 ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(13) {
    ["sequence_no"]=>
    string(10) "E325390225"        // 订单号
    ["order_date"]=>
    string(19) "2019-08-17 19:10:40"  // 订单创建时间
    ["total_price"]=>
    string(5) "558.0"          // 总价格
    ["totalnum"]=>
    int(1)                     // 票数
    ["train_code"]=>
    string(2) "G5"             // 车次
    ["from_station"]=>
    string(9) "北京南"          // 出发
    ["to_station"]=>
    string(6) "上海"            // 到达
    ["start_time"]=>
    string(5) "07:00"           // 发时
    ["arrive_time"]=>
    string(5) "11:40"           // 到时
    ["train_date"]=>
    string(10) "2019-08-31"     // 乘车日期
    ["lose_time"]=>
    string(19) "2019-08-17 19:40:40"  // 关闭时间
    ["count_down"]=>
    int(341)                    // 倒计时秒数
    ["tickets"]=>
    array(1) {
      [0]=>
      array(13) {
        ["ticket_no"]=>
        string(17) "E325390225107001B"  // 票号
        ["passenger_name"]=>
        string(9) "马云"                // 乘车人
        ["passenger_id_type_name"]=>
        string(21) "中国居民身份证"      // 证件类型
        ["passenger_id_no"]=>
        string(18) "9040***********920"  // 证件号码
        ["coach_name"]=>
        string(2) "07"               // 车箱
        ["seat_name"]=>
        string(6) "01B号"            // 位置
        ["seat_type_name"]=>
        string(9) "二等座"           // 席别
        ["ticket_type_name"]=>
        string(9) "成人票"           // 票种
        ["ticket_price"]=>
        string(5) "558.0"           // 票价
        ["status_code"]=>
        string(1) "i"               // 状态编码   i未支付  c已退票  a未出行 b已出票
        ["status_name"]=>
        string(9) "待支付"           // 状态名称
        ["lose_time"]=>
        string(19) "2019-08-17 19:40:40"  // 关闭时间
        ["cancel_token"]=>
        string(0) ""                 
      }
    }
  }
  ["total"]=>
  int(1)   // 票张数
```

##### 8.2 取消未支付订单
 地址：http://v3.chepiao100.com/v30.api?do=train.order.epay

|函数/参数|用途|
|--|--|
| token | Token |
| sequence_no | sequence_no 订单号 |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)        // status == 1 表示取消成功
  ["message"]=>
  string(27) "未支付订单取消成功"
```

##### 8.3 已完成订单管理
 地址：http://v3.chepiao100.com/v30.api?do=train.order.query

|函数/参数|用途|
|--|--|
| token | Token |
| where | 类型：G未出行订单， H历史订单 |
| start_date | 开始日期   |
| end_date | 结束日期 |
| keyword | 关键字：订单号/乘车人姓名  |

返回数据
```
 ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(13) {
    ["sequence_no"]=>
    string(10) "E325390225"        // 订单号
    ["order_date"]=>
    string(19) "2019-08-17 19:10:40"  // 订单创建时间
    ["total_price"]=>
    string(5) "558.0"          // 总价格
    ["totalnum"]=>
    int(1)                     // 票数
    ["train_code"]=>
    string(2) "G5"             // 车次
    ["from_station"]=>
    string(9) "北京南"          // 出发
    ["to_station"]=>
    string(6) "上海"            // 到达
    ["start_time"]=>
    string(5) "07:00"           // 发时
    ["arrive_time"]=>
    string(5) "11:40"           // 到时
    ["train_date"]=>
    string(10) "2019-08-31"     // 乘车日期
    ["lose_time"]=>
    string(19) "2019-08-17 19:40:40"  // 关闭时间
    ["count_down"]=>
    int(341)                    // 倒计时秒数
    ["tickets"]=>
    array(1) {
      [0]=>
      array(13) {
        ["ticket_no"]=>
        string(17) "E325390225107001B"  // 票号
        ["passenger_name"]=>
        string(9) "马云"                // 乘车人
        ["passenger_id_type_name"]=>
        string(21) "中国居民身份证"      // 证件类型
        ["passenger_id_no"]=>
        string(18) "9040***********920"  // 证件号码
        ["coach_name"]=>
        string(2) "07"               // 车箱
        ["seat_name"]=>
        string(6) "01B号"            // 位置
        ["seat_type_name"]=>
        string(9) "二等座"           // 席别
        ["ticket_type_name"]=>
        string(9) "成人票"           // 票种
        ["ticket_price"]=>
        string(5) "558.0"           // 票价
        ["status_code"]=>
        string(1) "i"               // 状态编码   i未支付  c已退票  a未出行 b已出票
        ["status_name"]=>
        string(9) "待支付"           // 状态名称
        ["lose_time"]=>
        string(19) "2019-08-17 19:40:40"  // 关闭时间
        ["cancel_token"]=>
        string(0) ""                /// 退票Token  (status_code == a 时才会有)
      }
    }
  }
  ["total"]=>
  int(1)   // 票张数
```

##### 8.4 退票提醒
> 说明：退票提醒之后，要紧接着 确认退票才能操作成功。
  
 地址：http://v3.chepiao100.com/v30.api?do=train.order.refund

|函数/参数|用途|
|--|--|
| token | Token |
| cancel_token | 退票 |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(2) {
        'sequence_no'      => "",   // 订单号
        'ticket_no'        => "",   // 票号
        'start_train_date' => "",   // 发车时间
        'from_station'     => "",   // 发站
        'to_station'       => "",   // 到站
        'passenger_name'   => "",   // 乘车人
        'passenger_id_no'  => "",   // 乘车人证件号
        'coach_name'       => "",   // 车箱
        'seat_name'        => "",   // 座号
        'seat_type_name'   => "",   // 车票类型
        'ticket_price'     => "",   // 原票价
        'return_cost'      => "",	// 退票费
        'return_rate'      => "",   // 退票费率
        'return_price'     => "",   // 实际退票
  }
}
```

#### 9 乘车人管理
   
##### 9.1 常用乘车人
>  场景：订票时选乘车人使用该接口
  
 地址：http://v3.chepiao100.com/v30.api?do=train.passenger.dtos

|函数/参数|用途|
|--|--|
| token | Token |

返回数据：
```
 ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(15) {
    ....
    [0]=>
    array(8) {
      ["country_code"]=>
      string(2) "CN"        // 国家编码（目前仅支持CN）
      ["passenger_name"]=>
      string(9) "马云"      // 乘车人
      ["passenger_id_type_code"]=>
      string(1) "1"         // 证件类型
      ["passenger_id_type_name"]=>
      string(21) "中国居民身份证"  // 证件名称
      ["passenger_id_no"]=>
      string(18) "82321***********698A" // 证件号码
      ["passenger_type"]=>
      string(1) "1"                     // 旅客类型ID
      ["passenger_type_name"]=>
      string(6) "成人"                  // 旅客类型名称
      ["allEncStr"]=>                   // 乘客密钥
      string(96) "7d45ec72d6ed364b5685d1defb79286a0eee4bcaf4283c36f4e9ae02b975425c976501bdd9512b0975f3627adc38d1da"
    }
    ....
  }
  
```
 
##### 9.2 乘车人列表
 地址：http://v3.chepiao100.com/v30.api?do=train.passenger.lists

|函数/参数|用途|
|--|--|
| token | Token |
  
返回数据：  
```
 ["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(15) {
    ....
    [0]=>
    array(8) {
      ["country_code"]=>
      string(2) "CN"        // 国家编码（目前仅支持CN）
      ["sex_code"]=> 
      string(1) "M"         // 性别编码
      ["sex_name"]=> 
      string(3) "男"        // 性别名称
      ["passenger_name"]=>
      string(9) "马云"      // 乘车人
      ["passenger_id_type_code"]=>
      string(1) "1"         // 证件类型
      ["passenger_id_type_name"]=>
      string(21) "中国居民身份证"  // 证件名称
      ["passenger_id_no"]=>
      string(18) "82321***********698A" // 证件号码
      ["passenger_type"]=>
      string(1) "1"                     // 旅客类型ID
      ["passenger_type_name"]=>
      string(6) "成人"                  // 旅客类型名称
      ["isUserSelf"]=>
      string(1) "N"                     // 是否是用户自己
      ["passenger_status"]=>
      string(9) "已通过"                // 验证状态
      ["allEncStr"]=>                   // 乘客密钥
      string(96) "7d45ec72d6ed364b5685d1defb79286a0eee4bcaf4283c36f4e9ae02b975425c976501bdd9512b0975f3627adc38d1da"
    }
    ....
  }
```

##### 9.3 添加乘车人
地址：http://v3.chepiao100.com/v30.api?do=train.passenger.lists
   
|函数/参数|用途|
|--|--|
|token | Token |
|passenger_name|姓名|
|sex_code | 性别编码 |
|country_code | 国家编码 |
|passenger_id_type_code|证件类型ID|
|passenger_id_no|证件号|
|passenger_type|旅客类型 |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)        // status == 1 表示成功
  ["message"]=>
  string(27) "新增成功"
```

##### 9.4 编辑乘车人
 地址：http://v3.chepiao100.com/v30.api?do=train.passenger.edit

|函数/参数|用途|
|--|--|
| token | Token |
| passenger_name | 姓名 |
| old_passenger_name | 原姓名 |
| sex_code | 性别编码 |
| country_code | 国家编码 |
| passenger_id_type_code | 证件类型ID  |
| old_passenger_id_type_code | 原证件类型ID |
| passenger_id_no | 证件号 |
| old_passenger_id_no | 原证件号 |
| passenger_type | 旅客类型  |
| allEncStr |乘车人密钥 |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)        // status == 1 表示成功
  ["message"]=>
  string(27) "保存成功"
```

##### 9.5 删除乘车人
 地址：http://v3.chepiao100.com/v30.api?do=train.passenger.delete

|函数/参数|用途|
|--|--|
| token | Token |
| passenger_name | 姓名 |
| passenger_id_type_code | 证件类型ID  |
| passenger_id_no | 证件号 |
| isUserSelf | 是否是自己 |
| allEncStr |乘车人密钥 |

返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)        // status == 1 表示成功
  ["message"]=>
  string(27) "删除成功"
```

#### 10 代售点查询

##### 10.1 省份列表
地址：http://v3.chepiao100.com/v30.api?do=train.agency.province

 返回数据
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(31) {
    .....
    [0]=>
    string(6) "安徽"   // 省名
    .....
  }
}
```

##### 10.2 城市查询
地址：http://v3.chepiao100.com/v30.api?do=train.agency.city

|函数/参数|用途|
|--|--|
|token|Token|
|province|省份|

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(31) {
    .....
    [0]=>
    string(6) "南雄"  // 城市名称
    .....
  }
}
```

##### 10.3 县区查询
地址：http://v3.chepiao100.com/v30.api?do=train.agency.county

|函数/参数|用途|
|--|--|
|token|Token|
|province|省份|
|city|城市|

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(31) {
    .....
    [0]=>
    string(6) "唐山"  // 县区名称
    .....
  }
}
```

##### 10.4 代售点查询
地址：http://v3.chepiao100.com/v30.api?do=train.agency.query

|函数/参数|用途|
|--|--|
|token|Token|
|province|省份|
|city|城市|
|county|县区|

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(1) {
    [0]=>
    array(6) {
      ["agency_name"]=>
      string(45) "唐山飞达票务有限责任公司门市部"  // 代售点名称
      ["address"]=>
      string(46) "唐山市路北区石油家园101楼底商16-7" // 代售点地址
      ["phone_no"]=>
      string(2) "--"         // 电话
      ["windows"]=>
      string(1) "1"          // 服务窗口
      ["morning"]=>
      string(11) "08:00-12:00"  // 早上营业时间
      ["afternoon"]=>
      string(11) "14:00-20:00"  // 晚上营业时间
    }
  }
```
 
#### 11 其他查询

##### 11.1 汽车票查询
地址：http://v3.chepiao100.com/v30.api?do=train.bus.query


|函数/参数|用途|
|--|--|
|start_city|出发|
|end_city|到达|
|start_date|日期|

返回数据：
```
["errMsg"]=>
  string(1) "Y"
  ["status"]=>
  int(1)
  ["datas"]=>
  array(31) {
    ....
    [0]=>
    array(7) {
      ["start_station"]=>
      string(6) "四惠"     // 发车站
      ["end_station"]=>
      string(24) "唐山汽车客运西站" // 到达站
      ["start_time"]=>   
      string(5) "07:10" // 发时
      ["bus_class"]=>
      string(6) "普通"  // 等级
      ["bus_price"]=>
      string(4) "52.0"  // 价格
      ["line_msg"]=>
      string(57) "该班次每隔一段时间发车，发车时间不定。"  // 线路说明
      ["overtime_msg"]=>
      string(36) "该班次为临时新增的班次。"              // 加班说明
    }
    ...
  }
}
```

#### 附表
##### 附表1 接口错误代码
|编码|说明|
|--|--|
|-10000| 无效的DeviceID |
|-10001| 无效的CDN |
|-10002 | 维护时间 |
|-10004 | 用户名或密码错误 |
|-10003 | 登录超时 |
|-10005 | 无效参数 |
|-10006 | 系统业务异常 |
|-11000 | 其他错误（服务器忙）|

##### 附表2 席别编码
|编码|席别|
|--|--|
|9 | 商务座 |
|P | 特等座 |
|M | 一等座 |
|O | 二等座 |
|6 | 高级软卧 |
|4 | 软卧  |
|3 | 硬卧  |
|2 | 软座  |
|1 | 硬座  |
|0 | 无座 |
|F | 动卧  |

##### 附表3 证件类型
|编码|类型|
|--|--|
|1|二代身份证|
|C|港澳通行证|
|G|台湾通行证|
|B|护照|

##### 附表4 乘客类型
|编码|类型|
|--|--|
|1|成人|
|2|儿童|
|3|学生|
|4|军疾|
