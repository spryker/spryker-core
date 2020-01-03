<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth;

use Spryker\Shared\Oauth\OauthConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Oauth\OauthConfig getSharedConfig()
 */
class OauthConfig extends AbstractBundleConfig
{
    public const GRANT_TYPE_PASSWORD = 'password';
    public const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->getSharedConfig()->getPublicKeyPath();
    }

    /**
     * @return string
     */
    public function getPrivateKeyPath(): string
    {
        return $this->getSharedConfig()->getPrivateKeyPath();
    }

    /**
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->getSharedConfig()->getEncryptionKey();
    }

    /**
     * @return string
     */
    public function getRefreshTokenTTL(): string
    {
        return $this->getSharedConfig()->getRefreshTokenTTL();
    }

    /**
     * @return string
     */
    public function getAccessTokenTTL(): string
    {
        return $this->getSharedConfig()->getAccessTokenTTL();
    }

    /**
     * The client secret used to authenticate Oauth client requests, to create use "password_hash('your password', PASSWORD_BCRYPT)".
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(OauthConstants::OAUTH_CLIENT_SECRET);
    }

    /**
     * The client id as is store in spy_oauth_client database table
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(OauthConstants::OAUTH_CLIENT_IDENTIFIER);
    }
}
