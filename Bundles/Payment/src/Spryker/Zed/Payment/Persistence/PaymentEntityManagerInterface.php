<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;

interface PaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMethodTypeByPaymentProviderAndMethod(
        SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
    ): void;
}
