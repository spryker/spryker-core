<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Provider;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig
     */
    protected OauthUserConnectorConfig $oauthUserConnectorConfig;

    /**
     * @var list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface>
     */
    protected array $userTypeOauthScopeProviderPlugins;

    /**
     * @var \Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface
     */
    protected OauthUserConnectorToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig $oauthUserConnectorConfig
     * @param list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface> $userTypeOauthScopeProviderPlugins
     * @param \Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthUserConnectorConfig $oauthUserConnectorConfig,
        array $userTypeOauthScopeProviderPlugins,
        OauthUserConnectorToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->oauthUserConnectorConfig = $oauthUserConnectorConfig;
        $this->userTypeOauthScopeProviderPlugins = $userTypeOauthScopeProviderPlugins;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $userIdentifierTransfer = $this->createUserIdentifierTransfer($oauthScopeRequestTransfer);

        /** @var list<\Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers */
        $oauthScopeTransfers = $oauthScopeRequestTransfer->getDefaultScopes()->getArrayCopy();
        $oauthScopeTransfers = $this->expandWithOauthScopeTransfersProvidedByConfig($oauthScopeTransfers);
        $oauthScopeTransfers = $this->expandWithOuathScopeTransfersProviderdByPlugins($userIdentifierTransfer, $oauthScopeTransfers);

        return $oauthScopeTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function expandWithOauthScopeTransfersProvidedByConfig(array $oauthScopeTransfers): array
    {
        foreach ($this->oauthUserConnectorConfig->getUserScopes() as $identifier) {
            $oauthScopeTransfers[] = (new OauthScopeTransfer())->setIdentifier($identifier);
        }

        return $oauthScopeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     * @param list<\Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function expandWithOuathScopeTransfersProviderdByPlugins(
        UserIdentifierTransfer $userIdentifierTransfer,
        array $oauthScopeTransfers
    ): array {
        $oauthScopeTransfersFromPlugins = $this->getOauthScopeTransfersFromPlugins($userIdentifierTransfer);
        if ($oauthScopeTransfersFromPlugins === []) {
            $oauthScopeTransfersFromPlugins[] = $this->createBackOfficeUserOauthScopeTransfer();
        }

        return array_merge($oauthScopeTransfers, $oauthScopeTransfersFromPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function getOauthScopeTransfersFromPlugins(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        $oauthScopeTransfers = [];
        foreach ($this->userTypeOauthScopeProviderPlugins as $userTypeOauthScopeProviderPlugin) {
            $oauthScopeTransfers[] = $userTypeOauthScopeProviderPlugin->getScopes($userIdentifierTransfer);
        }

        return array_merge(...$oauthScopeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UserIdentifierTransfer
     */
    protected function createUserIdentifierTransfer(OauthScopeRequestTransfer $oauthScopeRequestTransfer): UserIdentifierTransfer
    {
        return (new UserIdentifierTransfer())->fromArray(
            (array)$this->utilEncodingService->decodeJson($oauthScopeRequestTransfer->getUserIdentifierOrFail(), true),
            true,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    protected function createBackOfficeUserOauthScopeTransfer(): OauthScopeTransfer
    {
        return (new OauthScopeTransfer())
            ->setIdentifier($this->oauthUserConnectorConfig->getBackOfficeUserScope());
    }
}
