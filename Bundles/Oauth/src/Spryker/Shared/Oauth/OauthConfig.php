<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Oauth;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractSharedConfig;

class OauthConfig extends AbstractSharedConfig
{
    /**
     * Path to public key location
     *
     * @see https://oauth2.thephpleague.com/installation/
     *
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return Config::getInstance()->get(OauthConstants::PUBLIC_KEY_PATH);
    }

    /**
     * Path to private key location
     *
     * @see https://oauth2.thephpleague.com/installation/
     *
     * @return string
     */
    public function getPrivateKeyPath(): string
    {
        return Config::getInstance()->get(OauthConstants::PRIVATE_KEY_PATH);
    }

    /**
     * Encryption key used to encrypt data
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return Config::getInstance()->get(OauthConstants::ENCRYPTION_KEY);
    }

    /**
     * Timespan interval for how long is the refresh token is valid, this will be feed to \DateTime object
     *
     * @return string
     */
    public function getRefreshTokenTTL(): string
    {
        return 'P1M';
    }

    /**
     * Timespan interval for how long is the access token is valid, this will be feed to \DateTime object
     *
     * @return string
     */
    public function getAccessTokenTTL(): string
    {
        return 'PT8H';
    }
}
