<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Generated\Shared\Transfer\SalesPaymentTransfer;

interface SalesPaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    public function createSalesPayment(SalesPaymentTransfer $salesPaymentTransfer): SalesPaymentTransfer;
}
