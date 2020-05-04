<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\ProductCriteriaFilterTransfer;

interface ProductTableDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaFilterTransfer $productCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    public function getProductTableData(ProductCriteriaFilterTransfer $productCriteriaFilterTransfer): GuiTableDataTransfer;
}
