<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleItemDeleter implements SalesOrderConfiguredBundleItemDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
     */
    public function __construct(
        protected SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager,
        protected SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer
     */
    public function deleteSalesOrderConfiguredBundleItemCollection(
        SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
    ): SalesOrderConfiguredBundleItemCollectionResponseTransfer {
        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer): void {
            $this->executeDeleteSalesOrderConfiguredBundleItemCollectionTransaction($salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer);
        });

        return new SalesOrderConfiguredBundleItemCollectionResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    protected function executeDeleteSalesOrderConfiguredBundleItemCollectionTransaction(
        SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
    ): void {
        $salesOrderItemIds = $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds();
        if (!$salesOrderItemIds) {
            return;
        }

        $salesOrderConfiguredBundleIdsToDelete = $this->salesConfigurableBundleRepository
            ->getSalesOrderConfiguredBundleIdsBySalesOrderItemIds($salesOrderItemIds);

        $this->salesConfigurableBundleEntityManager->deleteSalesOrderConfiguredBundleItemsBySalesOrderItemIds(
            $salesOrderItemIds,
        );

        $this->salesConfigurableBundleEntityManager->deleteSalesOrderConfiguredBundlesByIds(
            $salesOrderConfiguredBundleIdsToDelete,
        );
    }
}
