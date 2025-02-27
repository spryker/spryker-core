<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface;

class SalesPaymentDeleter implements SalesPaymentDeleterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface $salesPaymentEntityManager
     * @param list<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface> $salesPaymentPreDeletePlugins
     */
    public function __construct(protected SalesPaymentEntityManagerInterface $salesPaymentEntityManager, protected array $salesPaymentPreDeletePlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    public function deleteSalesPayments(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($salesPaymentCollectionTransfer): void {
            $this->executeDeleteSalesPaymentsTransaction($salesPaymentCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    protected function executeDeleteSalesPaymentsTransaction(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void
    {
        $salesPaymentIds = $this->extractSalesPaymentIds($salesPaymentCollectionTransfer->getSalesPayments());

        if ($salesPaymentIds === []) {
            return;
        }

        $this->executeSalesPaymentPreDeletePlugins($salesPaymentCollectionTransfer);
        $this->salesPaymentEntityManager->deleteSalesPayments($salesPaymentIds);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SalesPaymentTransfer> $salesPaymentTransfers
     *
     * @return list<int>
     */
    protected function extractSalesPaymentIds(ArrayObject $salesPaymentTransfers): array
    {
        $salesPaymentIds = [];
        foreach ($salesPaymentTransfers as $salesPaymentTransfer) {
            $salesPaymentIds[] = $salesPaymentTransfer->getIdSalesPaymentOrFail();
        }

        return $salesPaymentIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    protected function executeSalesPaymentPreDeletePlugins(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void
    {
        foreach ($this->salesPaymentPreDeletePlugins as $salesPaymentPreDeletePlugin) {
            $salesPaymentPreDeletePlugin->preDelete($salesPaymentCollectionTransfer);
        }
    }
}
