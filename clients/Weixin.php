<?php

namespace yiichina\auth\clients;

use yii\authclient\OAuth2;
use yiichina\auth\AuthChoiceAsset;
use Yii;

class Weixin extends OAuth2
{
    use AuthTrait;

    public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';

    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    public $apiBaseUrl = 'https://api.weixin.qq.com';

    public function init()
    {
        $this->registerAsset();
        $this->registerTranslations();
    }

    protected function initUserAttributes()
    {
        return $this->api('sns/userinfo');
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weixin';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yiichina/auth', 'Wexin');
    }
}
