<?php

namespace yiichina\auth\clients;

use yii\authclient\OAuth2;
use yiichina\auth\AuthChoiceAsset;
use Yii;

class QQ extends OAuth2
{
    use AuthTrait;

    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';

    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';

    public $apiBaseUrl = 'https://graph.qq.com';

    public function init()
    {
        $this->registerAsset();
        $this->registerTranslations();
    }

    protected function initUserAttributes()
    {
        return array_merge(['id' => $this->user->openid], $this->api('user/get_user_info', 'GET', ['oauth_consumer_key' => $this->user->client_id, 'openid' => $this->user->openid]);
    }

    /**
     * @inheritdoc
     */
    protected function getUser()
    {
    	$str = file_get_contents('https://graph.qq.com/oauth2.0/me?access_token=' . $this->accessToken->token);

        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos -1);
        }
        return json_decode($str);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'qq';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yiichina/auth', 'QQ');
    }
}
