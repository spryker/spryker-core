<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig
     */
    protected $oauthCustomerConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
     */
    public function __construct(OauthCustomerConnectorConfig $oauthCustomerConnectorConfig)
    {
        $this->oauthCustomerConnectorConfig = $oauthCustomerConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = $oauthScopeRequestTransfer->getDefaultScopes()->getArrayCopy();
        foreach ($this->oauthCustomerConnectorConfig->getCustomerScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);
            $scopes[] = $oauthScopeTransfer;
        }

        return $scopes;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getCustomerImpersonationScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = $oauthScopeRequestTransfer->getDefaultScopes()->getArrayCopy();
        foreach ($this->oauthCustomerConnectorConfig->getCustomerImpersonationScopes() as $scope) {
            $scopes[] = (new OauthScopeTransfer())->setIdentifier($scope);
        }

        return $scopes;
    }
}
