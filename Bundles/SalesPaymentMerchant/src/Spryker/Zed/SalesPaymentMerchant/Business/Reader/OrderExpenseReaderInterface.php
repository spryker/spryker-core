<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderExpenseReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    public function getOrderExpensesForTransfer(OrderTransfer $orderTransfer, array $orderItemPaymentTransmissionItemTransfers): array;
}
