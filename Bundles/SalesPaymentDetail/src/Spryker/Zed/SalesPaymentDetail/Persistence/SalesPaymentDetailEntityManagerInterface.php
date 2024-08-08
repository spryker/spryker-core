<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence;

use Generated\Shared\Transfer\SalesPaymentDetailTransfer;

interface SalesPaymentDetailEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function createSalesPaymentDetails(SalesPaymentDetailTransfer $salesPaymentDetailTransfer): SalesPaymentDetailTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function updateSalesPaymentDetails(SalesPaymentDetailTransfer $salesPaymentDetailTransfer): SalesPaymentDetailTransfer;
}
