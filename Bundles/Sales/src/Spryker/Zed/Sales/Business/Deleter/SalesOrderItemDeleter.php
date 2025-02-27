<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;

class SalesOrderItemDeleter implements SalesOrderItemDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface> $salesOrderItemCollectionPreDeletePlugins
     */
    public function __construct(protected SalesEntityManagerInterface $salesEntityManager, protected array $salesOrderItemCollectionPreDeletePlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function deleteSalesOrderItemCollection(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $salesOrderItemIds = $this->getSalesOrderItemIds($salesOrderItemCollectionDeleteCriteriaTransfer);
        $salesOrderItemCollectionDeleteCriteriaTransfer->setSalesOrderItemIds($salesOrderItemIds);

        if ($salesOrderItemIds !== []) {
            $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemCollectionDeleteCriteriaTransfer, $salesOrderItemIds) {
                $this->executeSalesOrderItemCollectionPreDeletePlugins($salesOrderItemCollectionDeleteCriteriaTransfer);

                return $this->deleteSalesOrderItemsBySalesOrderItemIds(
                    $salesOrderItemIds,
                    $salesOrderItemCollectionDeleteCriteriaTransfer,
                );
            });
        }

        return new SalesOrderItemCollectionResponseTransfer();
    }

    /**
     * @param list<int> $salesOrderItemIds
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function deleteSalesOrderItemsBySalesOrderItemIds(
        array $salesOrderItemIds,
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $this->salesEntityManager->deleteSalesOrderItemsBySalesOrderItemIds($salesOrderItemIds);

        return (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($salesOrderItemCollectionDeleteCriteriaTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return list<int>
     */
    protected function getSalesOrderItemIds(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): array {
        $salesOrderItemIds = [];

        foreach ($salesOrderItemCollectionDeleteCriteriaTransfer->getItems() as $itemTransfers) {
            $salesOrderItemIds[] = $itemTransfers->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    protected function executeSalesOrderItemCollectionPreDeletePlugins(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): void {
        foreach ($this->salesOrderItemCollectionPreDeletePlugins as $salesOrderItemCollectionPreDeletePlugin) {
            $salesOrderItemCollectionPreDeletePlugin->preDelete($salesOrderItemCollectionDeleteCriteriaTransfer);
        }
    }
}
