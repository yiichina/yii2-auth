<?php

namespace yiichina\auth\clients;

use yii\authclient\OAuth2;
use yiichina\auth\AuthChoiceAsset;
use Yii;

/**
 * Weixin allows authentication via Weixin OAuth.
 *
 * In order to use Weixin OAuth you must register your application at <https://open.weixin.qq.com/>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'weixin' => [
 *                 'class' => 'yiichina\auth\clients\Weixin',
 *                 'clientId' => 'weixin_app_id',
 *                 'clientSecret' => 'weixin_app_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 * 
 * Views:
 *
 * ```php
 * <?= yii\authclient\widgets\AuthChoice::widget([
 *      'baseAuthUrl' => ['site/auth'],
 *      'popupMode' => false]) ?>
 * ```
 *
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&lang=zh_CN
 *
 */
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
