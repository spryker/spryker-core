<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig
     */
    protected $oauthCustomerConnectorConfig;

    /**
     * @var \Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerScopeProviderPluginInterface[]
     */
    protected $oauthCustomerScopeProviderPlugins;

    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface $utilEncodingService
     * @param array $oauthCustomerScopeProviderPlugins
     */
    public function __construct(
        OauthCustomerConnectorConfig $oauthCustomerConnectorConfig,
        OauthCustomerConnectorToUtilEncodingServiceInterface $utilEncodingService,
        array $oauthCustomerScopeProviderPlugins
    ) {
        $this->oauthCustomerConnectorConfig = $oauthCustomerConnectorConfig;
        $this->utilEncodingService = $utilEncodingService;
        $this->oauthCustomerScopeProviderPlugins = $oauthCustomerScopeProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = (array)$oauthScopeRequestTransfer->getDefaultScopes();
        foreach ($this->oauthCustomerConnectorConfig->getCustomerScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);
            $scopes[] = $oauthScopeTransfer;
        }

        $customerIdentifier = (new CustomerIdentifierTransfer())->fromArray(
            $this->utilEncodingService->decodeJson($oauthScopeRequestTransfer->getUserIdentifier(), true)
        );

        foreach ($this->oauthCustomerScopeProviderPlugins as $oauthCustomerScopeProviderPlugin) {
            $scopes = array_merge(
                $scopes,
                $oauthCustomerScopeProviderPlugin->provideScopes($customerIdentifier)
            );
        }

        return $scopes;
    }
}
