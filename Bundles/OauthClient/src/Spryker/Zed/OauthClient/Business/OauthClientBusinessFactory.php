<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthClient\Business\Cache\AccessTokenCacheInterface;
use Spryker\Zed\OauthClient\Business\Cache\AccessTokenDatabaseCache;
use Spryker\Zed\OauthClient\Business\Expander\RequestAuthorizationDataExpander;
use Spryker\Zed\OauthClient\Business\Expander\RequestAuthorizationDataExpanderInterface;
use Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProvider;
use Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface;
use Spryker\Zed\OauthClient\OauthClientDependencyProvider;

/**
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface getRepository()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface getEntityManager()
 */
class OauthClientBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface
     */
    public function createOauthAccessTokenProvider(): OauthAccessTokenProviderInterface
    {
        return new OauthAccessTokenProvider(
            $this->getOauthAccessTokenProviderPlugins(),
            $this->createAccessTokenCache(),
            $this->getConfig(),
            $this->getAccessTokenRequestExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthClient\Business\Cache\AccessTokenCacheInterface
     */
    public function createAccessTokenCache(): AccessTokenCacheInterface
    {
        return new AccessTokenDatabaseCache(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface>
     */
    public function getOauthAccessTokenProviderPlugins(): array
    {
        return $this->getProvidedDependency(OauthClientDependencyProvider::PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER);
    }

    /**
     * @return array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface>
     */
    public function getAccessTokenRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(OauthClientDependencyProvider::PLUGINS_ACCESS_TOKEN_REQUEST_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\OauthClient\Business\Expander\RequestAuthorizationDataExpanderInterface
     */
    public function createRequestAuthorizationDataExpander(): RequestAuthorizationDataExpanderInterface
    {
        return new RequestAuthorizationDataExpander($this->createOauthAccessTokenProvider(), $this->getConfig());
    }
}
