<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Persistence;

use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;

interface SalesMerchantCommissionEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionTransfer
     */
    public function createSalesMerchantCommission(
        SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
    ): SalesMerchantCommissionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionTransfer
     */
    public function updateSalesMerchantCommission(
        SalesMerchantCommissionTransfer $salesMerchantCommissionTransfer
    ): SalesMerchantCommissionTransfer;
}
