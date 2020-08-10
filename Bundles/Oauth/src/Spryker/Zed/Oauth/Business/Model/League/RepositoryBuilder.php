<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\AccessTokenRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\ClientRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\ScopeRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\UserRepository;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class RepositoryBuilder implements RepositoryBuilderInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface
     */
    protected $oauthRefreshTokenMapper;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface[]
     */
    protected $userProviderPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[]
     */
    protected $scopeProviderPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected $oauthUserIdentifierFilterPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[]
     */
    protected $oauthRefreshTokenRevokePlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[]
     */
    protected $oauthRefreshTokensRevokePlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[]
     */
    protected $oauthRefreshTokenCheckerPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[]
     */
    protected $oauthRefreshTokenSaverPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface[]
     */
    protected $oauthRefreshTokenPersistencePlugins;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper
     * @param array $userProviderPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[] $scopeProviderPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[] $oauthUserIdentifierFilterPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[] $oauthRefreshTokenRevokePlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[] $oauthRefreshTokensRevokePlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[] $oauthRefreshTokenCheckerPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[] $oauthRefreshTokenSaverPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface[] $oauthRefreshTokenPersistencePlugins
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        OauthEntityManagerInterface $oauthEntityManager,
        OauthToUtilEncodingServiceInterface $utilEncodingService,
        OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper,
        array $userProviderPlugins = [],
        array $scopeProviderPlugins = [],
        array $oauthUserIdentifierFilterPlugins = [],
        array $oauthRefreshTokenRevokePlugins = [],
        array $oauthRefreshTokensRevokePlugins = [],
        array $oauthRefreshTokenCheckerPlugins = [],
        array $oauthRefreshTokenSaverPlugins = [],
        array $oauthRefreshTokenPersistencePlugins = []
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
        $this->utilEncodingService = $utilEncodingService;
        $this->oauthRefreshTokenMapper = $oauthRefreshTokenMapper;
        $this->userProviderPlugins = $userProviderPlugins;
        $this->scopeProviderPlugins = $scopeProviderPlugins;
        $this->oauthUserIdentifierFilterPlugins = $oauthUserIdentifierFilterPlugins;
        $this->oauthRefreshTokenRevokePlugins = $oauthRefreshTokenRevokePlugins;
        $this->oauthRefreshTokensRevokePlugins = $oauthRefreshTokensRevokePlugins;
        $this->oauthRefreshTokenCheckerPlugins = $oauthRefreshTokenCheckerPlugins;
        $this->oauthRefreshTokenSaverPlugins = $oauthRefreshTokenSaverPlugins;
        $this->oauthRefreshTokenPersistencePlugins = $oauthRefreshTokenPersistencePlugins;
    }

    /**
     * @return \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    public function createClientRepository(): ClientRepositoryInterface
    {
        return new ClientRepository($this->oauthRepository);
    }

    /**
     * @return \League\OAuth2\Server\Repositories\ScopeRepositoryInterface
     */
    public function createScopeRepository(): ScopeRepositoryInterface
    {
        return new ScopeRepository($this->oauthRepository, $this->scopeProviderPlugins);
    }

    /**
     * @return \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    public function createAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return new AccessTokenRepository(
            $this->oauthRepository,
            $this->oauthEntityManager,
            $this->utilEncodingService,
            $this->oauthUserIdentifierFilterPlugins
        );
    }

    /**
     * @return \League\OAuth2\Server\Repositories\UserRepositoryInterface
     */
    public function createUserRepository(): UserRepositoryInterface
    {
        return new UserRepository($this->userProviderPlugins);
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface
     */
    public function createRefreshTokenRepository(): RefreshTokenRepositoryInterface
    {
        return new RefreshTokenRepository(
            $this->oauthRefreshTokenMapper,
            $this->oauthRefreshTokenRevokePlugins,
            $this->oauthRefreshTokensRevokePlugins,
            $this->oauthRefreshTokenCheckerPlugins,
            $this->oauthRefreshTokenSaverPlugins,
            $this->oauthRefreshTokenPersistencePlugins
        );
    }
}
