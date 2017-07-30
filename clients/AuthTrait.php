<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiichina\auth\clients;

use Yii;
use yiichina\auth\AuthChoiceAsset;

trait AuthTrait
{
    protected function registerAsset()
    {
        AuthChoiceAsset::register(Yii::$app->view);
    }

    protected function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['yiichina/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/yiichina/yii2-auth/messages',
            'fileMap' => [
                'yiichina/auth' => 'auth.php',
            ],
        ];
    }
}
