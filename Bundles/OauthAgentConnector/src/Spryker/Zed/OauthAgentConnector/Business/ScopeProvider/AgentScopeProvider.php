<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\ScopeProvider;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig;

class AgentScopeProvider implements AgentScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig
     */
    protected $oauthAgentConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig $oauthAgentConnectorConfig
     */
    public function __construct(OauthAgentConnectorConfig $oauthAgentConnectorConfig)
    {
        $this->oauthAgentConnectorConfig = $oauthAgentConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        $scopes = (array)$oauthScopeRequestTransfer->getDefaultScopes();
        foreach ($this->oauthAgentConnectorConfig->getAgentScopes() as $scope) {
            $scopes[] = (new OauthScopeTransfer())->setIdentifier($scope);
        }

        return $scopes;
    }
}
