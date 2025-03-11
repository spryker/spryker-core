<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business\Reader;

use Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusCriteriaTransfer;

interface PaymentAppPaymentStatusReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusCriteriaTransfer $paymentAppPaymentStatusCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer
     */
    public function getPaymentAppPaymentStatusCollection(
        PaymentAppPaymentStatusCriteriaTransfer $paymentAppPaymentStatusCriteriaTransfer
    ): PaymentAppPaymentStatusCollectionTransfer;
}
