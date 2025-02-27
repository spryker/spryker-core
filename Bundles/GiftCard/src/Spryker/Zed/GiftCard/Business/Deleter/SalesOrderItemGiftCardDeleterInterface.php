<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer;

interface SalesOrderItemGiftCardDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer
     */
    public function deleteSalesOrderItemGiftCardCollection(
        SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
    ): SalesOrderItemGiftCardCollectionResponseTransfer;
}
