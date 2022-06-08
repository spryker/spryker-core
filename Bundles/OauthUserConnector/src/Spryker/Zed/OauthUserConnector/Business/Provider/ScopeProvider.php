<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Provider;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig
     */
    protected $oauthUserConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig $oauthUserConnectorConfig
     */
    public function __construct(OauthUserConnectorConfig $oauthUserConnectorConfig)
    {
        $this->oauthUserConnectorConfig = $oauthUserConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = $oauthScopeRequestTransfer->getDefaultScopes()->getArrayCopy();
        foreach ($this->oauthUserConnectorConfig->getUserScopes() as $scope) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfer->setIdentifier($scope);
            $scopes[] = $oauthScopeTransfer;
        }

        return $scopes;
    }
}
