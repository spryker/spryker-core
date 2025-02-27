<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer;
use Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface;

class SalesOrderItemGiftCardDeleter implements SalesOrderItemGiftCardDeleterInterface
{
    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface $giftCardEntityManager
     */
    public function __construct(protected GiftCardEntityManagerInterface $giftCardEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer
     */
    public function deleteSalesOrderItemGiftCardCollection(
        SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
    ): SalesOrderItemGiftCardCollectionResponseTransfer {
        if ($salesOrderItemGiftCardCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->giftCardEntityManager->deleteSalesOrderItemGiftCardsBySalesOrderItemIds(
                $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesOrderItemGiftCardCollectionResponseTransfer();
    }
}
