<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Deleter;

use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;
use Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface;

class PaymentGiftCardDeleter implements PaymentGiftCardDeleterInterface
{
    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface $giftCardEntityManager
     */
    public function __construct(protected GiftCardEntityManagerInterface $giftCardEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePaymentGiftCardCollection(
        PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
    ): void {
        $salesPaymentIds = $paymentGiftCardCollectionDeleteCriteriaTransfer->getSalesPaymentIds();
        if ($salesPaymentIds) {
            $this->giftCardEntityManager->deletePaymentGiftCardsBySalesPaymentIds($salesPaymentIds);
        }
    }
}
