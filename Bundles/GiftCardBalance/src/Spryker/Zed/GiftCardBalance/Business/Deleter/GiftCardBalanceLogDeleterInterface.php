<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business\Deleter;

use Generated\Shared\Transfer\GiftCardBalanceLogCollectionDeleteCriteriaTransfer;

interface GiftCardBalanceLogDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GiftCardBalanceLogCollectionDeleteCriteriaTransfer $giftCardBalanceLogCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteGiftCardBalanceLogCollection(
        GiftCardBalanceLogCollectionDeleteCriteriaTransfer $giftCardBalanceLogCollectionDeleteCriteriaTransfer
    ): void;
}
