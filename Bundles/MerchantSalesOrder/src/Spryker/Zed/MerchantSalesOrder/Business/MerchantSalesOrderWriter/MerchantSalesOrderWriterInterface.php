<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderWriter;

use Generated\Shared\Transfer\MerchantSalesOrderTransfer;

interface MerchantSalesOrderWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    public function createMerchantSalesOrder(MerchantSalesOrderTransfer $merchantSalesOrderTransfer): MerchantSalesOrderTransfer;
}
