<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Deleter;

use Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class SalesDiscountDeleter implements SalesDiscountDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     */
    public function __construct(protected DiscountEntityManagerInterface $discountEntityManager, protected DiscountRepositoryInterface $discountRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer $salesDiscountCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteSalesDiscounts(
        SalesDiscountCollectionDeleteCriteriaTransfer $salesDiscountCollectionDeleteCriteriaTransfer
    ): void {
        if ($salesDiscountCollectionDeleteCriteriaTransfer->getSalesExpenseIds()) {
            $this->deleteSalesDiscountsBySalesExpenseIds(
                $salesDiscountCollectionDeleteCriteriaTransfer->getSalesExpenseIds(),
            );
        }

        if ($salesDiscountCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->deleteSalesDiscountsBySalesOrderItemIds(
                $salesDiscountCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }
    }

    /**
     * @param list<int> $salesOrderIds
     *
     * @return void
     */
    public function deleteSalesDiscountsBySalesOrderIds(array $salesOrderIds): void
    {
        $salesDiscountIds = $this->discountRepository->getSalesDiscountIds($salesOrderIds);
        $this->deleteSalesDiscountsBySalesDiscountIds($salesDiscountIds);
    }

    /**
     * @param list<int> $salesExpenseIds
     *
     * @return void
     */
    protected function deleteSalesDiscountsBySalesExpenseIds(array $salesExpenseIds): void
    {
        $salesDiscountIds = $this->discountRepository->getSalesDiscountIds([], $salesExpenseIds);
        $this->deleteSalesDiscountsBySalesDiscountIds($salesDiscountIds);
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    protected function deleteSalesDiscountsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $salesDiscountIds = $this->discountRepository->getSalesDiscountIds([], [], $salesOrderItemIds);
        $this->deleteSalesDiscountsBySalesDiscountIds($salesDiscountIds);
    }

    /**
     * @param list<int> $salesDiscountIds
     *
     * @return void
     */
    protected function deleteSalesDiscountsBySalesDiscountIds(array $salesDiscountIds): void
    {
        if ($salesDiscountIds) {
            $this->getTransactionHandler()->handleTransaction(function () use ($salesDiscountIds): void {
                $this->executeDeleteSalesDiscountsBySalesDiscountIdsTransaction($salesDiscountIds);
            });
        }
    }

    /**
     * @param list<int> $salesDiscountIds
     *
     * @return void
     */
    protected function executeDeleteSalesDiscountsBySalesDiscountIdsTransaction(array $salesDiscountIds): void
    {
        $this->discountEntityManager->deleteSalesDiscountCodesBySalesDiscountIds($salesDiscountIds);
        $this->discountEntityManager->deleteSalesDiscountsBySalesDiscountIds($salesDiscountIds);
    }
}
