<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Mapper;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;

class OauthRefreshTokenMapper implements OauthRefreshTokenMapperInterface
{
    protected const CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected $oauthUserIdentifierFilterPlugins;

    /**
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[] $oauthUserIdentifierFilterPlugins
     */
    public function __construct(
        OauthToUtilEncodingServiceInterface $utilEncodingService,
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
        $encodedUserIdentifier = $refreshTokenEntity->getAccessToken()->getUserIdentifier();
        $userIdentifier = $this->utilEncodingService->decodeJson($encodedUserIdentifier, true);
        $filteredUserIdentifier = $this->filterUserIdentifier($userIdentifier);
        $encodedUserIdentifier = (string)$this->utilEncodingService->encodeJson($filteredUserIdentifier);

        $oauthRefreshTokenTransfer
            ->setIdentifier($refreshTokenEntity->getIdentifier())
            ->setCustomerReference($userIdentifier[static::CUSTOMER_REFERENCE] ?? null)
            ->setUserIdentifier($encodedUserIdentifier)
            ->setExpiresAt($refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'))
            ->setIdOauthClient($refreshTokenEntity->getAccessToken()->getClient()->getIdentifier())
            ->setScopes($this->utilEncodingService->encodeJson($refreshTokenEntity->getAccessToken()->getScopes()));

        return $oauthRefreshTokenTransfer;
    }

    /**
     * @param array $userIdentifier
     *
     * @return array
     */
    protected function filterUserIdentifier(array $userIdentifier): array
    {
        foreach ($this->oauthUserIdentifierFilterPlugins as $oauthUserIdentifierFilterPlugin) {
            $userIdentifier = $oauthUserIdentifierFilterPlugin->filter($userIdentifier);
        }

        return $userIdentifier;
    }
}
