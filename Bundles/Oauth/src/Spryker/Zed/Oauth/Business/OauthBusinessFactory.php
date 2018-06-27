<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oauth\Business\Model\League\AccessGrantExecutor;
use Spryker\Zed\Oauth\Business\Model\League\AccessGrantExecutorInterface;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidator;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidatorInterface;
use Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilder;
use Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\PasswordGrant;
use Spryker\Zed\Oauth\Business\Model\League\Grant\RefreshTokenGrant;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilder;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\ResourceServerBuilder;
use Spryker\Zed\Oauth\Business\Model\League\ResourceServerBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriter;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface;
use Spryker\Zed\Oauth\Business\Model\OauthScopeWriter;
use Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\OauthDependencyProvider;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 * @method \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface getRepository()
 * @method \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface getEntityManager()()
 */
class OauthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\AccessGrantExecutorInterface
     */
    public function createAccessGrantExecutor(): AccessGrantExecutorInterface
    {
        return new AccessGrantExecutor([
            OauthConfig::GRANT_TYPE_PASSWORD => $this->createPasswordGrant(),
            OauthConfig::GRANT_TYPE_REFRESH_TOKEN => $this->createRefreshTokenGrant(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    public function createPasswordGrant(): GrantInterface
    {
        return new PasswordGrant(
            $this->createAuthorizationServerBuilder()->build(),
            $this->createRepositoryBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    protected function createRefreshTokenGrant(): GrantInterface
    {
        return new RefreshTokenGrant(
            $this->createAuthorizationServerBuilder()->build(),
            $this->createRepositoryBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\ResourceServerBuilderInterface
     */
    public function createResourceServerBuilder(): ResourceServerBuilderInterface
    {
        return new ResourceServerBuilder($this->getConfig(), $this->createRepositoryBuilder());
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface
     */
    public function createOauthScopeWriter(): OauthScopeWriterInterface
    {
        return new OauthScopeWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface
     */
    public function createOauthClientWriter(): OauthClientWriterInterface
    {
        return new OauthClientWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface
     */
    protected function createRepositoryBuilder(): RepositoryBuilderInterface
    {
        return new RepositoryBuilder(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getUserProviderPlugins(),
            $this->getScopeProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilderInterface
     */
    public function createAuthorizationServerBuilder(): AuthorizationServerBuilderInterface
    {
        return new AuthorizationServerBuilder($this->getConfig(), $this->createRepositoryBuilder());
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidatorInterface
     */
    public function createAccessTokenReader(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->createResourceServerBuilder()->build());
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface[]
     */
    public function getUserProviderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGIN_USER_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[]
     */
    public function getScopeProviderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGIN_SCOPE_PROVIDER);
    }
}
