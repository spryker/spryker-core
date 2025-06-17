<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class ServiceDateTimeEnabledSaver implements ServiceDateTimeEnabledSaverInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $entityManager
     */
    public function __construct(protected SelfServicePortalEntityManagerInterface $entityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveServiceDateTimeEnabledForOrderItems(QuoteTransfer $quoteTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer): void {
            $this->executeServiceDateTimeEnabledForOrderItemsSave($quoteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function executeServiceDateTimeEnabledForOrderItemsSave(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getIdSalesOrderItem()) {
                continue;
            }

            $this->entityManager->saveIsServiceDateTimeEnabledForSalesOrderItem(
                $itemTransfer->getIdSalesOrderItem(),
                (bool)($itemTransfer->getIsServiceDateTimeEnabled() ?? false),
            );
        }
    }
}
