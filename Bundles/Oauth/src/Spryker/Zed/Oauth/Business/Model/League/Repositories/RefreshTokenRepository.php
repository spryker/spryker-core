<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use DateTime;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\RefreshTokenEntity;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     */
    public function __construct(OauthEntityManagerInterface $oauthEntityManager, OauthRepositoryInterface $oauthRepository)
    {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthRepository = $oauthRepository;
    }

    /**
     * Creates a new refresh token
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
        $userIdentifier = $refreshTokenEntity->getAccessToken()->getUserIdentifier();

        $oauthRefreshTokenTransfer = new OauthRefreshTokenTransfer();
        $oauthRefreshTokenTransfer
            ->setIdentifier($refreshTokenEntity->getIdentifier())
            ->setUserIdentifier($userIdentifier)
            ->setExpiresAt($refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'))
            ->setIdOauthClient($refreshTokenEntity->getAccessToken()->getClient()->getIdentifier())
            ->setScopes(json_encode($refreshTokenEntity->getAccessToken()->getScopes()));

        $this->oauthEntityManager->saveRefreshToken($oauthRefreshTokenTransfer);
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
        if ($this->isRefreshTokenRevoked($tokenId)) {
            return;
        }

        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($tokenId)
            ->setRevokedAt((new DateTime())->format('Y-m-d H:i:s'));

        $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
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

        return $this->oauthRepository->isRefreshTokenRevoked($oauthRefreshTokenTransfer);
    }
}
