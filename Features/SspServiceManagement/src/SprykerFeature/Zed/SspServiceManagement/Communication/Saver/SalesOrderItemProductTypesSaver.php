<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Saver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface;

class SalesOrderItemProductTypesSaver implements SalesOrderItemProductTypesSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface $entityManager
     */
    public function __construct(SspServiceManagementEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemProductTypesFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeSaveSalesOrderItemProductTypesTransaction($quoteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeSaveSalesOrderItemProductTypesTransaction(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductTypes()) === 0) {
                continue;
            }

            $this->saveProductTypesForSalesOrderItem($itemTransfer);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function saveProductTypesForSalesOrderItem(ItemTransfer $itemTransfer): void
    {
        if (!$itemTransfer->getIdSalesOrderItem()) {
            return;
        }

        foreach ($itemTransfer->getProductTypes() as $productType) {
            $this->entityManager->saveSalesOrderItemProductType(
                $itemTransfer->getIdSalesOrderItemOrFail(),
                $productType,
            );
        }
    }
}
