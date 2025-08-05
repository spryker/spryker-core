<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class SalesOrderItemProductClassesSaver implements SalesOrderItemProductClassesSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface
     */
    protected $entityManager;

    public function __construct(SelfServicePortalEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveSalesOrderItemProductClassesFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeSaveSalesOrderItemProductClassesTransaction($quoteTransfer);
        });
    }

    protected function executeSaveSalesOrderItemProductClassesTransaction(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductClasses()) === 0) {
                continue;
            }

            $this->saveProductClassesForSalesOrderItem($itemTransfer);
        }

        return true;
    }

    protected function saveProductClassesForSalesOrderItem(ItemTransfer $itemTransfer): void
    {
        if (!$itemTransfer->getIdSalesOrderItem()) {
            return;
        }

        foreach ($itemTransfer->getProductClasses() as $productClass) {
            /** @var \Generated\Shared\Transfer\ProductClassTransfer $productClass */
            $this->entityManager->saveSalesOrderItemProductClass(
                $itemTransfer->getIdSalesOrderItemOrFail(),
                $productClass,
            );
        }
    }
}
