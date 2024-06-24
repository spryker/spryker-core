<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Persistence;

use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;

interface SalesMerchantCommissionRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionCollection(
        SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
    ): SalesMerchantCommissionCollectionTransfer;
}
