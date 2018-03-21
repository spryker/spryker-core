<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Installer;

use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
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
     * @param \Spryker\Zed\SharedCart\SharedCartConfig $sharedCartConfig
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface $sharedCartEntityManager
     */
    public function __construct(SharedCartConfig $sharedCartConfig, SharedCartEntityManagerInterface $sharedCartEntityManager)
    {
        $this->sharedCartConfig = $sharedCartConfig;
        $this->sharedCartEntityManager = $sharedCartEntityManager;
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

        $permissionEntityTransfers = [];
        foreach ($quotePermissionGroupTransfers as $quotePermissionGroupTransfer) {
            $quotePermissionGroupEntityTransfer = new SpyQuotePermissionGroupEntityTransfer();
            $quotePermissionGroupEntityTransfer->fromArray($quotePermissionGroupTransfer->modifiedToArray(), true);

            $quotePermissionGroupEntityTransfer = $this->sharedCartEntityManager->saveQuotePermissionGroupEntity($quotePermissionGroupEntityTransfer);

            foreach ($quotePermissionGroupTransfer->getPermissions() as $permissionTransfer) {
                if (!isset($permissionEntityTransfers[$permissionTransfer->getKey()])) {
                    $permissionEntityTransfer = new SpyPermissionEntityTransfer();
                    $permissionEntityTransfer->fromArray($permissionTransfer->modifiedToArray(), true);

                    $permissionEntityTransfers[$permissionTransfer->getKey()] = $this->sharedCartEntityManager->savePermissionEntity($permissionEntityTransfer);
                }

                $this->sharedCartEntityManager->saveQuotePermissionGroupToPermissionEntity(
                    $quotePermissionGroupEntityTransfer,
                    $permissionEntityTransfers[$permissionTransfer->getKey()]
                );
            }
        }
    }
}
