<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;

interface SalesPaymentReaderInterface
{

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $paymentTransfer
     *
     * @return int
     */
    public function getPaymentMethodPriceToPay(SalesPaymentTransfer $paymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithPayment(OrderTransfer $orderTransfer);

}
