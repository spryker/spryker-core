<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantOrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrderByIdSalesOrderAndMerchantReference(int $idSalesOrder, string $merchantReference): ?MerchantOrderTransfer;

    /**
     * @param int $idMerchantOrder
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrderByIdMerchantOrder(int $idMerchantOrder): ?MerchantOrderTransfer;
}
