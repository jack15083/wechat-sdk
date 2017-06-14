<?php
/**
 * Created by PhpStorm.
 * User: zengfanwei
 * Date: 2017/6/14
 * Time: 17:44
 */
namespace wechat;
use GuzzleHttp;

class Http
{
    public $timeout;
    public $header;
    public $cookies;
    public $url;

    public function __construct($url, $timeout = 5, $header = [], $cookies = [])
    {
        $this->url = $url;
        $this->timeout = $timeout;
        $this->header = $header;
        $this->cookies = $cookies;
        $this->client = new GuzzleHttp\client(
            [
                // Base URI is used with relative requests
                'base_uri' => $this->url,
                // You can set any number of default request options.
            ]
        );
    }

    public function get()
    {
        $response = $this->client->request('GET', 'test');
    }

    public function curl($method = 'GET', $data = '', $cerpath)
    {
        if(!preg_match('/^http(s?):\/\//', $this->url))
            return ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(0 === stripos($this->url, 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, $cerpath);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }

        if('POST' === $method){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $data = curl_exec($ch);

        $res['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res['data'] = $data;
        if($res[0] != 200){
            $res['error'] = curl_error($ch);
        }
        curl_close($ch);

        return $res;
    }
}