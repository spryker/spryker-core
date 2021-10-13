<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\Installer;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig;

class AgentOauthScopeInstaller implements AgentOauthScopeInstallerInterface
{
    /**
     * @var \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig
     */
    protected $oauthAgentConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig $oauthAgentConnectorConfig
     */
    public function __construct(
        OauthAgentConnectorToOauthFacadeInterface $oauthFacade,
        OauthAgentConnectorConfig $oauthAgentConnectorConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthAgentConnectorConfig = $oauthAgentConnectorConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $agentScopes = $this->oauthAgentConnectorConfig->getAgentScopes();

        $existingOauthScopeTransfers = $this->oauthFacade->getScopesByIdentifiers($agentScopes);
        $indexedOauthScopeTransfers = $this->indexScopesByIdentifier($existingOauthScopeTransfers);

        foreach ($agentScopes as $agentScope) {
            if (isset($indexedOauthScopeTransfers[$agentScope])) {
                continue;
            }

            $oauthScopeTransfer = (new OauthScopeTransfer())
                ->setIdentifier($agentScope);

            $indexedOauthScopeTransfers[$agentScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function indexScopesByIdentifier(array $oauthScopeTransfers): array
    {
        $indexedOauthScopes = [];

        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            $indexedOauthScopes[$oauthScopeTransfer->getIdentifier()] = $oauthScopeTransfer;
        }

        return $indexedOauthScopes;
    }
}
