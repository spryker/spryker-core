<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business;

use Spryker\Zed\AuthRestApi\Business\AccessToken\AccessTokenProcessor;
use Spryker\Zed\AuthRestApi\Business\AccessToken\AccessTokenProcessorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AuthRestApi\Business\AccessToken\AccessTokenProcessorInterface
     */
    public function createAccessTokenProcessor(): AccessTokenProcessorInterface
    {
        return new AccessTokenProcessor($this->getOauthFacade());
    }

    /**
     * @return \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface
     */
    public function getOauthFacade(): \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface
    {
        return $this->getProvidedDependency(\Spryker\Zed\AuthRestApi\AuthRestApiDependencyProvider::FACADE_OAUTH);
    }
}
