<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Creator;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface;
use Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface;

class OauthRefreshTokenCreator implements OauthRefreshTokenCreatorInterface
{
    /**
     * @var \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface
     */
    protected $oauthRevokeEntityManager;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface $oauthRevokeEntityManager
     * @param \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthRevokeEntityManagerInterface $oauthRevokeEntityManager,
        OauthRevokeToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->oauthRevokeEntityManager = $oauthRevokeEntityManager;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
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

        $this->oauthRevokeEntityManager->saveRefreshToken($oauthRefreshTokenTransfer);
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
