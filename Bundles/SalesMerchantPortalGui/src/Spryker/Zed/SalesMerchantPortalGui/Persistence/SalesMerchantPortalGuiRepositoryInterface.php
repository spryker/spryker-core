<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;

interface SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderTableData(
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): MerchantOrderCollectionTransfer;
}
