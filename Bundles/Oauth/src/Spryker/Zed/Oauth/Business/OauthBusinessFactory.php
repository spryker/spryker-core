<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business;

use DateTime;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oauth\Business\Installer\OauthClientInstaller;
use Spryker\Zed\Oauth\Business\Installer\OauthClientInstallerInterface;
use Spryker\Zed\Oauth\Business\Model\League\AccessGrantExecutor;
use Spryker\Zed\Oauth\Business\Model\League\AccessGrantExecutorInterface;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenRequestExecutor;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenRequestExecutorInterface;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidator;
use Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidatorInterface;
use Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilder;
use Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\BearerTokenResponse;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeBuilder;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoader;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutor;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\PasswordGrant;
use Spryker\Zed\Oauth\Business\Model\League\Grant\RefreshTokenGrant;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilder;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\ResourceServerBuilder;
use Spryker\Zed\Oauth\Business\Model\League\ResourceServerBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthClientReader;
use Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriter;
use Spryker\Zed\Oauth\Business\Model\OauthClientWriterInterface;
use Spryker\Zed\Oauth\Business\Model\OauthExpiredRefreshTokenRemover;
use Spryker\Zed\Oauth\Business\Model\OauthExpiredRefreshTokenRemoverInterface;
use Spryker\Zed\Oauth\Business\Model\OauthRefreshTokenRevoker;
use Spryker\Zed\Oauth\Business\Model\OauthRefreshTokenRevokerInterface;
use Spryker\Zed\Oauth\Business\Model\OauthScopeReader;
use Spryker\Zed\Oauth\Business\Model\OauthScopeReaderInterface;
use Spryker\Zed\Oauth\Business\Model\OauthScopeWriter;
use Spryker\Zed\Oauth\Business\Model\OauthScopeWriterInterface;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\OauthDependencyProvider;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 * @method \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface getRepository()
 * @method \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface getEntityManager()
 */
class OauthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @deprecated Use {@link createAccessTokenRequestExecutor()} instead.
     *
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
     * @deprecated Will be removed with next major release.
     *
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
     * @return \Spryker\Zed\Oauth\Business\Model\League\AccessTokenRequestExecutorInterface
     */
    public function createAccessTokenRequestExecutor(): AccessTokenRequestExecutorInterface
    {
        return new AccessTokenRequestExecutor(
            $this->createGrantTypeConfigurationLoader(),
            $this->createGrantTypeBuilder(),
            $this->createGrantTypeExecutor(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantBuilderInterface
     */
    public function createGrantTypeBuilder(): GrantBuilderInterface
    {
        return new GrantTypeBuilder(
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
     * @deprecated Will be removed with next major release.
     *
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
     * @return \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface
     */
    protected function createRepositoryBuilder(): RepositoryBuilderInterface
    {
        return new RepositoryBuilder(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getUtilEncodingService(),
            $this->getUserProviderPlugins(),
            $this->getScopeProviderPlugins(),
            $this->getOauthUserIdentifierFilterPlugins(),
            $this->getOauthRefreshTokenRevokerPlugins(),
            $this->getOauthRefreshTokensRevokerPlugins(),
            $this->getOauthRefreshTokenCheckerPlugins(),
            $this->getOauthRefreshTokenSaverPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\AuthorizationServerBuilderInterface
     */
    public function createAuthorizationServerBuilder(): AuthorizationServerBuilderInterface
    {
        return new AuthorizationServerBuilder(
            $this->getConfig(),
            $this->createRepositoryBuilder(),
            $this->createBearerTokenResponse()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\AccessTokenValidatorInterface
     */
    public function createAccessTokenReader(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->createResourceServerBuilder()->build());
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeExecutorInterface
     */
    public function createGrantTypeExecutor(): GrantTypeExecutorInterface
    {
        return new GrantTypeExecutor(
            $this->createAuthorizationServerBuilder()->build(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeConfigurationLoaderInterface
     */
    public function createGrantTypeConfigurationLoader(): GrantTypeConfigurationLoaderInterface
    {
        return new GrantTypeConfigurationLoader(
            $this->getGrantTypeConfigurationProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthScopeReaderInterface
     */
    public function createOauthScopeReader(): OauthScopeReaderInterface
    {
        return new OauthScopeReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Installer\OauthClientInstallerInterface
     */
    public function createOauthClientInstaller(): OauthClientInstallerInterface
    {
        return new OauthClientInstaller(
            $this->getConfig(),
            $this->createOauthClientWriter(),
            $this->createOauthClientReader()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthRefreshTokenRevokerInterface
     */
    public function createOauthRefreshTokenRevoker(): OauthRefreshTokenRevokerInterface
    {
        return new OauthRefreshTokenRevoker(
            $this->createRepositoryBuilder()->createRefreshTokenRepository(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
            $this->getOauthRefreshTokenReaderPlugins(),
            $this->getOauthRefreshTokensReaderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthExpiredRefreshTokenRemoverInterface
     */
    public function createOauthExpiredRefreshTokenRemover(): OauthExpiredRefreshTokenRemoverInterface
    {
        return new OauthExpiredRefreshTokenRemover(
            $this->getConfig(),
            $this->createPresentDateTime(),
            $this->getOauthExpiredRefreshTokenRemoverPlugins()
        );
    }

    /**
     * @return \DateTime
     */
    protected function createPresentDateTime(): DateTime
    {
        return new DateTime();
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

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\OauthClientReaderInterface
     */
    public function createOauthClientReader(): OauthClientReaderInterface
    {
        return new OauthClientReader(
            $this->getRepository()
        );
    }

    /**
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function createBearerTokenResponse(): ResponseTypeInterface
    {
        return new BearerTokenResponse();
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface[]
     */
    public function getGrantTypeConfigurationProviderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    public function getOauthUserIdentifierFilterPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_USER_IDENTIFIER_FILTER);
    }

    /**
     * @return \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface[]
     */
    public function getOauthExpiredRefreshTokenRemoverPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface[]
     */
    public function getOauthRefreshTokenReaderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKEN_READER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface[]
     */
    public function getOauthRefreshTokensReaderPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKENS_READER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[]
     */
    public function getOauthRefreshTokenRevokerPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[]
     */
    public function getOauthRefreshTokensRevokerPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[]
     */
    public function getOauthRefreshTokenSaverPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKEN_SAVER);
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[]
     */
    public function getOauthRefreshTokenCheckerPlugins(): array
    {
        return $this->getProvidedDependency(OauthDependencyProvider::PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER);
    }
}
