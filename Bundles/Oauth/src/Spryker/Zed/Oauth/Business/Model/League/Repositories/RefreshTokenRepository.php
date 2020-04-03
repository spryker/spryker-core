<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use ArrayObject;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\RefreshTokenEntity;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[]
     */
    protected $refreshTokenRevokerPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[]
     */
    protected $refreshTokensRevokerPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[]
     */
    protected $refreshTokenCheckerPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[]
     */
    protected $refreshTokenSaverPlugins;

    /**
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[] $refreshTokenRevokerPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[] $refreshTokensRevokerPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[] $refreshTokenCheckerPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[] $refreshTokenSaverPlugins
     */
    public function __construct(
        array $refreshTokenRevokerPlugins,
        array $refreshTokensRevokerPlugins,
        array $refreshTokenCheckerPlugins,
        array $refreshTokenSaverPlugins
    ) {
        $this->refreshTokenRevokerPlugins = $refreshTokenRevokerPlugins;
        $this->refreshTokensRevokerPlugins = $refreshTokensRevokerPlugins;
        $this->refreshTokenCheckerPlugins = $refreshTokenCheckerPlugins;
        $this->refreshTokenSaverPlugins = $refreshTokenSaverPlugins;
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
        foreach ($this->refreshTokenRevokerPlugins as $refreshTokenRevokePlugin) {
            $refreshTokenRevokePlugin->revokeRefreshToken($tokenId);
        }
    }

    /**
     * @inheritDoc
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
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
        foreach ($this->refreshTokenCheckerPlugins as $refreshTokenCheckerPlugin) {
            if ($refreshTokenCheckerPlugin->isApplicable($tokenId)) {
                return $refreshTokenCheckerPlugin->isRefreshTokenRevoked($tokenId);
            }
        }

        return false;
    }
}
