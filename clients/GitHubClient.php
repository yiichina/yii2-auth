<?php

namespace frontend\widgets;

use yii\authclient\clients\GitHub;

class GitHubClient extends GitHub
{
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('user', 'GET');
		$attributes['openid'] = $attributes['id'];
		$attributes['client'] = 'github';

        if (empty($attributes['email'])) {
            // in case user set 'Keep my email address private' in GitHub profile, email should be retrieved via extra API request
            $scopes = explode(' ', $this->scope);
            if (in_array('user:email', $scopes, true) || in_array('user', $scopes, true)) {
                $emails = $this->api('user/emails', 'GET');
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email['primary'] == 1 && $email['verified'] == 1) {
                            $attributes['email'] = $email['email'];
                            break;
                        }
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'fa fa-github fa-2x';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'GitHub 登录';
    }
}
