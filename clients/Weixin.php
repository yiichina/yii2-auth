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

    public $scope = 'snsapi_login';

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

    /**
     * @inheritdoc
     */
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }

        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $this->setState('authState', $authState);
            $defaultParams['state'] = $authState;
        }

        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }
}
