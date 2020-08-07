<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\OauthCryptography\OauthCryptographyConfig getSharedConfig()
 */
class OauthCryptographyConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns path to public key location.
     *
     * @api
     *
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->getSharedConfig()->getPublicKeyPath();
    }
}
