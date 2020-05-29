<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Oauth\OauthConstants;

class OauthCryptographyConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        // Todo: move OauthConstants::PUBLIC_KEY_PATH to OauthCryptography.
        // Todo: place this path to shared config, proxy here.
        // @see \Spryker\Zed\Oauth\OauthConfig::getPublicKeyPath()
        return Config::getInstance()->get(OauthConstants::PUBLIC_KEY_PATH);
    }
}
