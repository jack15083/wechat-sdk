<?php
/**
 * Wechat oauth 2.0 login
 * User: zengfanwei
 * Date: 2017/6/15
 * Time: 11:20
 */
namespace wechat;

class Oauth
{
    public $appId;
    public $accessToken;
    public $code;
    public $secret;
    public $openId;
    public $userInfo;

    public function __construct()
    {
        $this->appId = Gconst::APP_ID;
        $this->secret = Gconst::SECRET;
    }

    /**
     * Get auth redirect url
     * @param $redirectUri
     * @return string
     */
    public function getAuthorizeUrl($redirectUri)
    {
        $params['appid'] = $this->appId;
        $params['redirect_uri'] = urldecode($redirectUri);
        $params['response_type'] = 'code';
        $params['scope'] = 'snsapi_userinfo';
        $params['state'] = 'STATE';
        $url = Gconst::WECHAT_AUTH_URL . '?' . http_build_query($params) . '#wechat_redirect';

        return $url;
    }

    /**
     * Get oauth access token
     * @param $code
     */
    public function getAccessToken($code)
    {
        $params['$appid'] = $this->appId;
        $params['code'] = $code;
        $params['secret'] = $this->secret;
        $params['grant_type'] = 'authorization_code';
        $url = Gconst::WECHAT_API_URL . '/sns/oauth2/access_token?' . http_build_query($params);

        $res = Http::instance($url)->get();
        $data = json_decode($res, true);
        if(!empty($data['errcode']))
        {
            throw new \Exception($data['errmsg'], $data['errcode']);
        }


        $this->openId = $data['openid'];
        $this->accessToken = $data['access_token'];
    }

    public function refreshToken()
    {

    }

    /**
     * Get user info
     */
    public function getUserInfo()
    {
        $params['access_token'] = $this->accessToken;
        $params['openid'] = $this->openId;
        $params['lang'] = 'zh_CN';
        $url = Gconst::WECHAT_API_URL . '/sns/userinfo?' . http_build_query($params);

        $res = Http::instance($url)->get();
        $data = json_decode($res, true);
        if(!empty($data['errcode']))
        {
            throw new \Exception($data['errmsg'], $data['errcode']);
        }
        $this->userInfo = $data;
    }
}