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
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected $oauthUserIdentifierFilterPlugins;

    /**
     * @var array
     */
    protected $decodedUserIdentifier;

    /**
     * @param \Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[] $oauthUserIdentifierFilterPlugins
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
        $userIdentifier = $this->filterUserIdentifier($userIdentifier);

        $customerReference = $this->getCustomerReference();

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
     * @return string|null
     */
    protected function getCustomerReference(): ?string
    {
        return $this->decodedUserIdentifier['customer_reference'] ?? null;
    }

    /**
     * @param string $userIdentifier
     *
     * @return string
     */
    protected function filterUserIdentifier(string $userIdentifier): string
    {
        $this->decodedUserIdentifier = $this->utilEncodingService->decodeJson($userIdentifier, true);

        if ($this->decodedUserIdentifier) {
            foreach ($this->oauthUserIdentifierFilterPlugins as $oauthUserIdentifierFilterPlugin) {
                $this->decodedUserIdentifier = $oauthUserIdentifierFilterPlugin->filter($this->decodedUserIdentifier);
            }
        }

        return (string)$this->utilEncodingService->encodeJson($this->decodedUserIdentifier);
    }
}
