<?php
/**
 * Created by PhpStorm.
 * User: zengfanwei
 * Date: 2017/6/15
 * Time: 11:33
 */
namespace wechat;

class Gconst
{
    const WECHAT_AUTH_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const WECHAT_API_URL = 'https://api.weixin.qq.com';
    const APP_ID = 'wx4d5f39b910ed1b86';
    const SECRET = 'c33efd87960812fc0ad70a269763a42e';
    const ACCESS_TOKEN_CACHE_KEY = 'wechat_api_access_token';
}