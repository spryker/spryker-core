<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business;

use Spryker\Zed\AuthRestApi\AuthRestApiDependencyProvider;
use Spryker\Zed\AuthRestApi\Business\AccessToken\AccessTokenProcessor;
use Spryker\Zed\AuthRestApi\Business\AccessToken\AccessTokenProcessorInterface;
use Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface;
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
        return new AccessTokenProcessor(
            $this->getOauthFacade(),
            $this->getPostAuthPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface
     */
    public function getOauthFacade(): AuthRestApiToOauthFacadeInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Zed\AuthRestApiExtension\Dependency\Plugin\PostAuthPluginInterface[]
     */
    protected function getPostAuthPlugins(): array
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::PLUGINS_POST_AUTH);
    }
}
