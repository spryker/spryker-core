<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\AccessTokenRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\ClientRepository;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepository;
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
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     * @param array $userProviderPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[] $scopeProviderPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[] $oauthUserIdentifierFilterPlugins
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        OauthEntityManagerInterface $oauthEntityManager,
        OauthToUtilEncodingServiceInterface $utilEncodingService,
        array $userProviderPlugins = [],
        array $scopeProviderPlugins = [],
        array $oauthUserIdentifierFilterPlugins = []
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
        $this->utilEncodingService = $utilEncodingService;
        $this->userProviderPlugins = $userProviderPlugins;
        $this->scopeProviderPlugins = $scopeProviderPlugins;
        $this->oauthUserIdentifierFilterPlugins = $oauthUserIdentifierFilterPlugins;
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
     * @return \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface
     */
    public function createRefreshTokenRepository(): RefreshTokenRepositoryInterface
    {
        return new RefreshTokenRepository();
    }
}
