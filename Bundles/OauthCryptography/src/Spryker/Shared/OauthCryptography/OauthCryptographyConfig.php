<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthCryptography;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class OauthCryptographyConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Path to public key location.
     *
     * @api
     *
     * @see https://oauth2.thephpleague.com/installation/
     *
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->get(OauthCryptographyConstants::PUBLIC_KEY_PATH);
    }
}
