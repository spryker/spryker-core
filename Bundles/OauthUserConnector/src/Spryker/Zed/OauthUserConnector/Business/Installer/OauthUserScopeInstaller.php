<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Installer;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

class OauthUserScopeInstaller implements OauthUserScopeInstallerInterface
{
    /**
     * @var \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig
     */
    protected $oauthUserConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig $oauthUserConnectorConfig
     */
    public function __construct(
        OauthUserConnectorToOauthFacadeInterface $oauthFacade,
        OauthUserConnectorConfig $oauthUserConnectorConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthUserConnectorConfig = $oauthUserConnectorConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $userScopes = $this->oauthUserConnectorConfig->getUserScopes();

        $oauthScopeTransfers = $this->oauthFacade->getScopesByIdentifiers($userScopes);
        $oauthScopeTransferMap = $this->mapScopesByIdentifiers($oauthScopeTransfers);

        foreach ($userScopes as $userScope) {
            if (!isset($oauthScopeTransferMap[$userScope])) {
                $oauthScopeTransfer = (new OauthScopeTransfer())->setIdentifier($userScope);
                $oauthScopeTransferMap[$userScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function mapScopesByIdentifiers(array $oauthScopeTransfers): array
    {
        $oauthScopeTransferMap = [];

        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            $oauthScopeTransferMap[$oauthScopeTransfer->getIdentifier()] = $oauthScopeTransfer;
        }

        return $oauthScopeTransferMap;
    }
}
