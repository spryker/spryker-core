<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business\Saver;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLog;

class GiftCardBalanceSaver implements GiftCardBalanceSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveTransactionLog(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentTransfer->requireGiftCard();
        $paymentTransfer->getGiftCard()->requireIdGiftCard();

        $idSalesOrder = $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();
        $idGiftCard = $paymentTransfer->getGiftCard()->getIdGiftCard();

        $transactionLogEntity = new SpyGiftCardBalanceLog();
        $transactionLogEntity->setFkSalesOrder($idSalesOrder);
        $transactionLogEntity->setFkGiftCard($idGiftCard);
        $transactionLogEntity->setValue($paymentTransfer->getAmount());

        $transactionLogEntity->save();
    }
}
