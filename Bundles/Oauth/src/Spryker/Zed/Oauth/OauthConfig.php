<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth;

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
}
