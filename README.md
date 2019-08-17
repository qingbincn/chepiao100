### 车票100在线订票接口(V30_Beat版)

#### 1. 概述
> 火车票在线订票接口(API),是为了第三方网站快速接入官网而开发的接口；客户端与接口使用简单的封装协议通讯，大大减化了第三方在线订票的接入。

#### 2.接口介绍
>  接口对外数据全部以http协议发布，请求全部以POST方式，返回数据全部是JSON格式, 除非有“选填”说明，全部请求参数都是必填。

##### 2.1 用户授权
*测试期暂时免费，合作联系QQ：89914505 *

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
