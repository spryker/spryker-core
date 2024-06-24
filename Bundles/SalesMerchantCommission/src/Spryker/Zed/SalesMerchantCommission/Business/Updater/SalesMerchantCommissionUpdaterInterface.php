<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Updater;

interface SalesMerchantCommissionUpdaterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer> $salesMerchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function updateSalesMerchantCommissions(array $salesMerchantCommissionTransfers): array;
}
