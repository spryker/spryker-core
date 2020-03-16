<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;

interface ProductTableDataHydratorInterface
{
    /**
     * Hydrates concrete product transfers from the collection with additional data.
     *
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function hydrateProductTableData(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): ProductTableDataTransfer;
}
