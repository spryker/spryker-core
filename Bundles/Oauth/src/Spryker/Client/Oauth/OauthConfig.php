<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Oauth\OauthConfig getSharedConfig()
 */
class OauthConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->getSharedConfig()->getPublicKeyPath();
    }
}
