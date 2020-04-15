<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;

interface ProductOfferMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableDataTransfer;
}
