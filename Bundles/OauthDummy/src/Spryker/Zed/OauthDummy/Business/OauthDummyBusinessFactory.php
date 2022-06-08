<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthDummy\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthDummy\Business\Generator\AccessTokenGenerator;
use Spryker\Zed\OauthDummy\Business\Generator\AccessTokenGeneratorInterface;

/**
 * @method \Spryker\Zed\OauthDummy\OauthDummyConfig getConfig()
 */
class OauthDummyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthDummy\Business\Generator\AccessTokenGeneratorInterface
     */
    public function createAccessTokenGenerator(): AccessTokenGeneratorInterface
    {
        return new AccessTokenGenerator($this->getConfig());
    }
}
