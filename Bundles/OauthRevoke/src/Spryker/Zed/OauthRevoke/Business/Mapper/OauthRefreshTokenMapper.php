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
    protected const CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthRevokeExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected $oauthUserIdentifierFilterPlugins;

    /**
     * @param \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthRevokeExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[] $oauthUserIdentifierFilterPlugins
     */
    public function __construct(
        OauthRevokeToUtilEncodingServiceInterface $utilEncodingService,
        array $oauthUserIdentifierFilterPlugins = []
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->oauthUserIdentifierFilterPlugins = $oauthUserIdentifierFilterPlugins;
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
        $decodedUserIdentifier = $this->utilEncodingService->decodeJson($userIdentifier, true);
        $customerReference = $decodedUserIdentifier[static::CUSTOMER_REFERENCE] ?? null;
        $filteredUserIdentifier = $this->filterUserIdentifier($decodedUserIdentifier);

        $oauthRefreshTokenTransfer
            ->setIdentifier($refreshTokenEntity->getIdentifier())
            ->setCustomerReference($customerReference)
            ->setUserIdentifier($filteredUserIdentifier)
            ->setExpiresAt($refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'))
            ->setIdOauthClient($refreshTokenEntity->getAccessToken()->getClient()->getIdentifier())
            ->setScopes($this->utilEncodingService->encodeJson($refreshTokenEntity->getAccessToken()->getScopes()));

        return $oauthRefreshTokenTransfer;
    }

    /**
     * @param array $decodedUserIdentifier
     *
     * @return string
     */
    protected function filterUserIdentifier(array $decodedUserIdentifier): string
    {
        if (!$decodedUserIdentifier) {
            return $this->encodeUserIdentifier($decodedUserIdentifier);
        }

        foreach ($this->oauthUserIdentifierFilterPlugins as $oauthUserIdentifierFilterPlugin) {
            $decodedUserIdentifier = $oauthUserIdentifierFilterPlugin->filter($decodedUserIdentifier);
        }

        return $this->encodeUserIdentifier($decodedUserIdentifier);
    }

    /**
     * @param array $decodedUserIdentifier
     *
     * @return string
     */
    protected function encodeUserIdentifier(array $decodedUserIdentifier): string
    {
        return (string)$this->utilEncodingService->encodeJson($decodedUserIdentifier);
    }
}
