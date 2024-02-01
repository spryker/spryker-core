<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Installer;

use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeInterface;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

class OauthScopeInstaller implements OauthScopeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig
     */
    protected OauthWarehouseConfig $oauthWarehouseConfig;

    /**
     * @var \Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeInterface
     */
    protected OauthWarehouseToOauthFacadeInterface $oauthFacade;

    /**
     * @param \Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig $oauthWarehouseConfig
     */
    public function __construct(
        OauthWarehouseToOauthFacadeInterface $oauthFacade,
        OauthWarehouseConfig $oauthWarehouseConfig
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->oauthWarehouseConfig = $oauthWarehouseConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $warehouseScopes = $this->oauthWarehouseConfig->getWarehouseScopes();
        $oauthScopeTransfers = $this->getOauthScopeTransfersIndexedByOauthScopeIdentifier($warehouseScopes);

        $this->getTransactionHandler()->handleTransaction(function () use ($warehouseScopes, $oauthScopeTransfers): void {
            $this->executeTransaction($warehouseScopes, $oauthScopeTransfers);
        });
    }

    /**
     * @param list<string> $warehouseScopes
     *
     * @return array<string, \Generated\Shared\Transfer\OauthScopeTransfer>
     */
    protected function getOauthScopeTransfersIndexedByOauthScopeIdentifier(array $warehouseScopes): array
    {
        $oauthScopeTransfersIndexedByOauthScopeIdentifier = [];
        $oauthScopeTransfers = $this->oauthFacade->getScopesByIdentifiers($warehouseScopes);

        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            /** @var string $oauthScopeIdentifier */
            $oauthScopeIdentifier = $oauthScopeTransfer->getIdentifierOrFail();
            $oauthScopeTransfersIndexedByOauthScopeIdentifier[$oauthScopeIdentifier] = $oauthScopeTransfer;
        }

        return $oauthScopeTransfersIndexedByOauthScopeIdentifier;
    }

    /**
     * @param list<string> $warehouseScopes
     * @param array<string, \Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers
     *
     * @return void
     */
    protected function executeTransaction(array $warehouseScopes, array $oauthScopeTransfers): void
    {
        foreach ($warehouseScopes as $warehouseScope) {
            if (isset($oauthScopeTransfers[$warehouseScope])) {
                continue;
            }

            $oauthScopeTransfer = (new OauthScopeTransfer())
                ->setIdentifier($warehouseScope);

            $oauthScopeTransfers[$warehouseScope] = $this->oauthFacade->saveScope($oauthScopeTransfer);
        }
    }
}
