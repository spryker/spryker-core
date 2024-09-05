<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

interface PaymentTransmissionItemExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    public function expandPaymentTransmissionItemsWithTransferId(
        array $paymentTransmissionItemTransfers,
        OrderTransfer $orderTransfer
    ): array;
}
