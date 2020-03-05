<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;

interface ProductListTableDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductListTableData(ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): ProductConcreteCollectionTransfer;
}
