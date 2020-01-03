<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Installer;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;

class OauthCustomerScopeInstaller implements OauthCustomerScopeInstallerInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig
     */
    protected $oauthCustomerConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
     */
    public function __construct(
        OauthCustomerConnectorToOauthFacadeInterface $oauthFacade,
        OauthCustomerConnectorConfig $oauthCustomerConnectorConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthCustomerConnectorConfig = $oauthCustomerConnectorConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $customerScopes = $this->oauthCustomerConnectorConfig->getCustomerScopes();

        $oauthScopeTransfers = $this->oauthFacade->getScopesByIdentifiers($customerScopes);
        $oauthScopeTransferMap = $this->mapScopesByIdentifiers($oauthScopeTransfers);

        foreach ($customerScopes as $customerScope) {
            if ($this->isOauthScopeExist($customerScope, $oauthScopeTransferMap)) {
                continue;
            }

            $oauthScopeTransfer = (new OauthScopeTransfer())
                ->setIdentifier($customerScope);

            $oauthScopeTransferMap[$customerScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransfers
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    protected function mapScopesByIdentifiers(array $oauthScopeTransfers): array
    {
        $oauthScopeTransferMap = [];

        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            $oauthScopeIdentifier = $oauthScopeTransfer->getIdentifier();
            $oauthScopeTransferMap[$oauthScopeIdentifier] = $oauthScopeTransfer;
        }

        return $oauthScopeTransferMap;
    }

    /**
     * @param string $oauthScopeIdentifier
     * @param \Generated\Shared\Transfer\OauthScopeTransfer[] $oauthScopeTransferMap
     *
     * @return bool
     */
    protected function isOauthScopeExist(string $oauthScopeIdentifier, array $oauthScopeTransferMap): bool
    {
        if (isset($oauthScopeTransferMap[$oauthScopeIdentifier])) {
            return true;
        }

        return false;
    }
}
