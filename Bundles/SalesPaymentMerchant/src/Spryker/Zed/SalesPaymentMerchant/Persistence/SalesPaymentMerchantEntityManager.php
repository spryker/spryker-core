<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use Generated\Shared\Transfer\PaymentTransmissionResponseTransfer;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantPersistenceFactory getFactory()
 */
class SalesPaymentMerchantEntityManager extends AbstractEntityManager implements SalesPaymentMerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayout(PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer): void
    {
        $salesPaymentMerchantPayoutEntity = new SpySalesPaymentMerchantPayout();
        $salesPaymentMerchantPayoutEntity->fromArray($paymentTransmissionResponseTransfer->toArray());
        $salesPaymentMerchantPayoutEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayoutReversal(
        PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
    ): void {
        $salesPaymentMerchantPayoutReversalEntity = new SpySalesPaymentMerchantPayoutReversal();
        $salesPaymentMerchantPayoutReversalEntity->fromArray($paymentTransmissionResponseTransfer->toArray());
        $salesPaymentMerchantPayoutReversalEntity->save();
    }
}
