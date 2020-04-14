<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Mapper;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface;

class OauthRefreshTokenMapper implements OauthRefreshTokenMapperInterface
{
    /**
     * @var \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(OauthRevokeToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer
     */
    public function mapRefreshTokenEntityToOauthRefreshTokenTransfer(
        RefreshTokenEntityInterface $refreshTokenEntity,
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
    ): OauthRefreshTokenTransfer {
        $userIdentifier = $refreshTokenEntity->getAccessToken()->getUserIdentifier();
        $customerReference = $this->getCustomerReference($userIdentifier);

        $oauthRefreshTokenTransfer
            ->setIdentifier($refreshTokenEntity->getIdentifier())
            ->setCustomerReference($customerReference)
            ->setUserIdentifier($userIdentifier)
            ->setExpiresAt($refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'))
            ->setIdOauthClient($refreshTokenEntity->getAccessToken()->getClient()->getIdentifier())
            ->setScopes($this->utilEncodingService->encodeJson($refreshTokenEntity->getAccessToken()->getScopes()));

        return $oauthRefreshTokenTransfer;
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
