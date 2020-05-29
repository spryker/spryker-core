<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientInterface;
use Spryker\Client\Oauth\ResourceServer\AccessTokenValidator;
use Spryker\Client\Oauth\ResourceServer\AccessTokenValidatorInterface;
use Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoader;
use Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoaderInterface;
use Spryker\Client\Oauth\ResourceServer\OauthAccessTokenValidator;
use Spryker\Client\Oauth\ResourceServer\Repository\AccessTokenRepository;
use Spryker\Client\Oauth\ResourceServer\ResourceServer;
use Spryker\Client\Oauth\ResourceServer\ResourceServerBuilder;
use Spryker\Client\Oauth\ResourceServer\ResourceServerBuilderInterface;
use Spryker\Client\Oauth\Zed\OauthStub;
use Spryker\Client\Oauth\Zed\OauthStubInterface;

/**
 * @method \Spryker\Client\Oauth\OauthConfig getConfig()
 */
class OauthFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Oauth\Zed\OauthStubInterface
     */
    public function createOauthStub(): OauthStubInterface
    {
        return new OauthStub($this->getZedRequestClient());
    }

    /**
     * @deprecated
     *
     * @return \Spryker\Client\Oauth\ResourceServer\AccessTokenValidatorInterface
     */
    public function createAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->createResourceServerBuilder()->create());
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\AccessTokenValidatorInterface
     */
    public function createOauthAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new OauthAccessTokenValidator($this->createResourceServer());
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServerBuilderInterface
     */
    public function createResourceServerBuilder(): ResourceServerBuilderInterface
    {
        return new ResourceServerBuilder($this->getConfig(), $this->createAccessTokenRepository());
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServer
     */
    public function createResourceServer(): ResourceServer
    {
        return new ResourceServer(
            $this->createKeyLoader()->loadKeys(),
            $this->createAccessTokenRepository(),
            $this->getAuthorizationValidatorPlugins()
        );
    }

    /**
     * @return \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected function createAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return new AccessTokenRepository();
    }

    /**
     * @return \Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientInterface
     */
    public function getZedRequestClient(): OauthToZedRequestClientInterface
    {
        return $this->getProvidedDependency(OauthDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoaderInterface
     */
    protected function createKeyLoader(): KeyLoaderInterface
    {
        return new KeyLoader($this->getKeyLoaderPlugins());
    }

    /**
     * @return \Spryker\Client\OauthExtention\Dependency\Plugin\KeyLoaderPluginInterface[]
     */
    public function getKeyLoaderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_KEY_LOADER);
    }

    /**
     * @return \Spryker\Client\OauthExtention\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    protected function getAuthorizationValidatorPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_AUTHORIZATION_VALIDATOR);
    }
}
