<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Oauth\Business\Model\League\Entities\RefreshTokenEntity;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;
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
     * @var \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthEntityManagerInterface $oauthEntityManager,
        OauthRepositoryInterface $oauthRepository,
        OauthToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthRepository = $oauthRepository;
        $this->utilEncodingService = $utilEncodingService;
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
        $userIdentifier = $refreshTokenEntity->getAccessToken()->getUserIdentifier();
        $customerReference = $this->getCustomerReference($userIdentifier);

        $oauthRefreshTokenTransfer = new OauthRefreshTokenTransfer();
        $oauthRefreshTokenTransfer
            ->setIdentifier($refreshTokenEntity->getIdentifier())
            ->setCustomerReference($customerReference)
            ->setUserIdentifier($userIdentifier)
            ->setExpiresAt($refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'))
            ->setIdOauthClient($refreshTokenEntity->getAccessToken()->getClient()->getIdentifier())
            ->setScopes($this->utilEncodingService->encodeJson($refreshTokenEntity->getAccessToken()->getScopes()));

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
            ->setIdentifier($tokenId);

        $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
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
        $this->oauthEntityManager->revokeAllRefreshTokens($oauthRefreshTokenTransfers);
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

    /**
     * @param string|null $userIdentifier
     *
     * @return string|null
     */
    protected function getCustomerReference(?string $userIdentifier): ?string
    {
        $encodedUserIdentifier = $this->utilEncodingService
            ->decodeJson($userIdentifier);

        return $encodedUserIdentifier->customer_reference ?? null;
    }
}
