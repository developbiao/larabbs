<?php


namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    public function translate($text)
    {
        $http = new Client();
        // init config
        $api    = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid  = config('services.baidu_translate.appid');
        $key    = config('services.baidu_translate.key');
        $salt   = time();

        // if not config baidu translate automatic use pin yin
        // According the document generate sign
        // http://api.fanyi.baidu.com/api/trans/product/apidoc
        // md5(appid+q+salt+key)
        $sign = md5($appid. $text . $salt . $key);

        // construct request parameters
        $query = http_build_query([
            "q"     =>  $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);

        // 发送 HTTP Get 请求
        $response = $http->get($api.$query);

        $result = json_decode($response->getBody(), true);

        /**
        获取结果，如果请求成功，dd($result) 结果如下：

        array:3 [▼
        "from" => "zh"
        "to" => "en"
        "trans_result" => array:1 [▼
        0 => array:2 [▼
        "src" => "XSS 安全漏洞"
        "dst" => "XSS security vulnerability"
        ]
        ]
        ]

         **/

        // 尝试获取获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            return str_slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没有结果，使用拼音作为后备计划。
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
       return str_slug(app(Pinyin::class)->premalink($text));
    }
}