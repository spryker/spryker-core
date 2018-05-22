<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Installer;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToPermissionFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface;
use Spryker\Zed\SharedCart\SharedCartConfig;

class QuotePermissionGroupInstaller implements QuotePermissionGroupInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SharedCart\SharedCartConfig
     */
    protected $sharedCartConfig;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface
     */
    protected $sharedCartEntityManager;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\SharedCart\SharedCartConfig $sharedCartConfig
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface $sharedCartEntityManager
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        SharedCartConfig $sharedCartConfig,
        SharedCartEntityManagerInterface $sharedCartEntityManager,
        SharedCartToPermissionFacadeInterface $permissionFacade
    ) {
        $this->sharedCartConfig = $sharedCartConfig;
        $this->sharedCartEntityManager = $sharedCartEntityManager;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallTransaction(): void
    {
        $quotePermissionGroupTransfers = $this->sharedCartConfig->getQuotePermissionGroups();
        $this->permissionFacade->syncPermissionPlugins();

        $permissionTransferIndex = $this->preparePermissionIndex();
        foreach ($quotePermissionGroupTransfers as $quotePermissionGroupTransfer) {
            $quotePermissionGroupEntityTransfer = $this->sharedCartEntityManager->saveQuotePermissionGroup($quotePermissionGroupTransfer);
            foreach ($quotePermissionGroupTransfer->getPermissions() as $permissionTransfer) {
                $this->sharedCartEntityManager->saveQuotePermissionGroupToPermission(
                    $quotePermissionGroupEntityTransfer,
                    $permissionTransferIndex[$permissionTransfer->getKey()]
                );
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function preparePermissionIndex(): array
    {
        $permissionTransferIndex = [];
        foreach ($this->permissionFacade->findAll()->getPermissions() as $permissionTransfer) {
            $permissionTransferIndex[$permissionTransfer->getKey()] = $permissionTransfer;
        }

        return $permissionTransferIndex;
    }
}
