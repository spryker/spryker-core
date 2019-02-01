<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserConnector\Business\Scope;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig
     */
    protected $oauthCompanyUserConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig $oauthCompanyUserConnectorConfig
     */
    public function __construct(OauthCompanyUserConnectorConfig $oauthCompanyUserConnectorConfig)
    {
        $this->oauthCompanyUserConnectorConfig = $oauthCompanyUserConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = (array)$oauthScopeRequestTransfer->getDefaultScopes();
        foreach ($this->oauthCompanyUserConnectorConfig->getCompanyUserScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);
            $scopes[] = $oauthScopeTransfer;
        }

        return $scopes;
    }
}
