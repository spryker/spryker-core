<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\RefreshTokenEntity;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface
     */
    protected $oauthRefreshTokenMapper;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface>
     */
    protected $refreshTokenRevokerPlugins;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface>
     */
    protected $refreshTokensRevokerPlugins;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface>
     */
    protected $refreshTokenCheckerPlugins;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface>
     */
    protected $refreshTokenSaverPlugins;

    /**
     * @var array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface>
     */
    protected $oauthRefreshTokenPersistencePlugins;

    /**
     * @param \Spryker\Zed\Oauth\Business\Mapper\OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface> $refreshTokenRevokerPlugins
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface> $refreshTokensRevokerPlugins
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface> $refreshTokenCheckerPlugins
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface> $refreshTokenSaverPlugins
     * @param array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface> $oauthRefreshTokenPersistencePlugins
     */
    public function __construct(
        OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper,
        array $refreshTokenRevokerPlugins,
        array $refreshTokensRevokerPlugins,
        array $refreshTokenCheckerPlugins,
        array $refreshTokenSaverPlugins,
        array $oauthRefreshTokenPersistencePlugins
    ) {
        $this->oauthRefreshTokenMapper = $oauthRefreshTokenMapper;
        $this->refreshTokenRevokerPlugins = $refreshTokenRevokerPlugins;
        $this->refreshTokensRevokerPlugins = $refreshTokensRevokerPlugins;
        $this->refreshTokenCheckerPlugins = $refreshTokenCheckerPlugins;
        $this->refreshTokenSaverPlugins = $refreshTokenSaverPlugins;
        $this->oauthRefreshTokenPersistencePlugins = $oauthRefreshTokenPersistencePlugins;
    }

    /**
     * Creates a new refresh token.
     *
     * @return \League\OAuth2\Server\Entities\RefreshTokenEntityInterface
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }

    /**
     * Persists a new refresh token to permanent storage.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->executeRefreshTokenSaverPlugins($refreshTokenEntity);

        $oauthRefreshTokenTransfer = $this->oauthRefreshTokenMapper
            ->mapRefreshTokenEntityToOauthRefreshTokenTransfer($refreshTokenEntity, new OauthRefreshTokenTransfer());

        foreach ($this->oauthRefreshTokenPersistencePlugins as $oauthRefreshTokenPersistencePlugin) {
            $oauthRefreshTokenPersistencePlugin->saveRefreshToken($oauthRefreshTokenTransfer);
        }
    }

    /**
     * @deprecated Added for BC reasons.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    protected function executeRefreshTokenSaverPlugins(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        foreach ($this->refreshTokenSaverPlugins as $refreshTokenSaverPlugin) {
            $refreshTokenSaverPlugin->saveRefreshToken($refreshTokenEntity);
        }
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     *
     * @return void
     */
    public function revokeRefreshToken($tokenId)
    {
        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($tokenId);

        foreach ($this->refreshTokenRevokerPlugins as $refreshTokenRevokePlugin) {
            $refreshTokenRevokePlugin->revokeRefreshToken($oauthRefreshTokenTransfer);
        }
    }

    /**
     * @inheritDoc
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OauthRefreshTokenTransfer> $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void
    {
        foreach ($this->refreshTokensRevokerPlugins as $refreshTokensRevokerPlugin) {
            $refreshTokensRevokerPlugin->revokeAllRefreshTokens($oauthRefreshTokenTransfers);
        }
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($tokenId);

        foreach ($this->refreshTokenCheckerPlugins as $refreshTokenCheckerPlugin) {
            if ($refreshTokenCheckerPlugin->isApplicable($tokenId)) {
                return $refreshTokenCheckerPlugin->isRefreshTokenRevoked($oauthRefreshTokenTransfer);
            }
        }

        return false;
    }
}
