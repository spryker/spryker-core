<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Reader;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesOrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\SalesPayment\Business\Exception\OrderNotFoundException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer;
}
