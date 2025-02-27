<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionTransfer;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class SalesExpenseDeleter implements SalesExpenseDeleterInterface
{
    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesExpensePreDeletePluginInterface> $salesExpensePreDeletePlugins
     */
    public function __construct(
        protected SalesRepositoryInterface $salesRepository,
        protected SalesEntityManagerInterface $salesEntityManager,
        protected array $salesExpensePreDeletePlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer
     */
    public function deleteSalesExpenseCollection(
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): SalesExpenseCollectionResponseTransfer {
        $expenseTransfers = $this->salesRepository->getSalesExpensesBySalesExpenseCollectionDeleteCriteria(
            $salesExpenseCollectionDeleteCriteriaTransfer,
        );

        if ($expenseTransfers === []) {
            return new SalesExpenseCollectionResponseTransfer();
        }

        $this->executeSalesExpensePreDeletePlugins((new SalesExpenseCollectionTransfer())->setExpenses(new ArrayObject($expenseTransfers)));
        $this->salesEntityManager->deleteSalesExpensesBySalesExpenseIds($this->extractSalesExpenseIds($expenseTransfers));

        return (new SalesExpenseCollectionResponseTransfer())->setSalesExpenses(new ArrayObject($expenseTransfers));
    }

    /**
     * @param list<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return list<int>
     */
    protected function extractSalesExpenseIds(array $expenseTransfers): array
    {
        $salesExpenseIds = [];
        foreach ($expenseTransfers as $expenseTransfer) {
            $salesExpenseIds[] = $expenseTransfer->getIdSalesExpenseOrFail();
        }

        return $salesExpenseIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer
     *
     * @return void
     */
    protected function executeSalesExpensePreDeletePlugins(SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer): void
    {
        foreach ($this->salesExpensePreDeletePlugins as $salesExpensePreDeletePlugin) {
            $salesExpensePreDeletePlugin->preDelete($salesExpenseCollectionTransfer);
        }
    }
}
