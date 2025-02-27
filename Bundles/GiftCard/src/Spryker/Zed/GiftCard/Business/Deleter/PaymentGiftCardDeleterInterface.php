<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Deleter;

use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;

interface PaymentGiftCardDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePaymentGiftCardCollection(
        PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
    ): void;
}
