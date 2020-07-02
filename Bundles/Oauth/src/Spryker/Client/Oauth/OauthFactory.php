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
use Spryker\Client\Oauth\ResourceServer\ResourceServerBuilder;
use Spryker\Client\Oauth\ResourceServer\ResourceServerBuilderInterface;
use Spryker\Client\Oauth\ResourceServer\ResourceServerCreator;
use Spryker\Client\Oauth\ResourceServer\ResourceServerCreatorInterface;
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
     * @deprecated Use {@link \Spryker\Client\Oauth\OauthFactory::createOauthAccessTokenValidator()} instead.
     *
     * @return \Spryker\Client\Oauth\ResourceServer\AccessTokenValidatorInterface
     */
    public function createAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->createResourceServerBuilder()->create());
    }

    /**
     * @deprecated Use {@link \Spryker\Client\Oauth\OauthFactory::createResourceServerCreator()} instead.
     *
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServerBuilderInterface
     */
    public function createResourceServerBuilder(): ResourceServerBuilderInterface
    {
        return new ResourceServerBuilder($this->getConfig(), $this->createAccessTokenRepository());
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\AccessTokenValidatorInterface
     */
    public function createOauthAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new OauthAccessTokenValidator($this->createResourceServerCreator()->create());
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServerCreatorInterface
     */
    public function createResourceServerCreator(): ResourceServerCreatorInterface
    {
        return new ResourceServerCreator(
            $this->createKeyLoader(),
            $this->createAccessTokenRepository(),
            $this->getAuthorizationValidatorPlugins()
        );
    }

    /**
     * @return \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    public function createAccessTokenRepository(): AccessTokenRepositoryInterface
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
    public function createKeyLoader(): KeyLoaderInterface
    {
        return new KeyLoader($this->getKeyLoaderPlugins());
    }

    /**
     * @return \Spryker\Client\OauthExtension\Dependency\Plugin\KeyLoaderPluginInterface[]
     */
    public function getKeyLoaderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_KEY_LOADER);
    }

    /**
     * @return \Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    public function getAuthorizationValidatorPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_AUTHORIZATION_VALIDATOR);
    }
}
