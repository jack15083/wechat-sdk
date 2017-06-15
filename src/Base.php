<?php
/**
 * Base Api class
 * User: zengfanwei
 * Date: 2017/6/15
 * Time: 15:03
 */
namespace wechat;

class Base
{
    public $redis;
    public $accessToken;

    public function  __construct(\Redis $redis)
    {
        $this->redis = $redis;
        if($this->accessToken)
            return;

        $accessToken = $this->redis->get(Gconst::ACCESS_TOKEN_CACHE_KEY);
        if(!empty($accessToken))
            $this->accessToken = $accessToken;

        $this->getAccessToken();
    }

    public function getAccessToken()
    {
        $params['$appid'] = Gconst::APP_ID;
        $params['secret'] = Gconst::SECRET;
        $params['grant_type'] = 'authorization_code';
        $url = Gconst::WECHAT_API_URL . '/cgi-bin/token?' . http_build_query($params);

        $res = Http::instance($url)->get();
        $data = json_decode($res);
        if(!empty($data['errcode']))
        {
            throw new \Exception($data['errmsg'], $data['errcode']);
        }

        $this->accessToken = $data['access_token'];
        $this->redis->set(Gconst::ACCESS_TOKEN_CACHE_KEY, $this->accessToken, $data['expires_in']);
    }
}