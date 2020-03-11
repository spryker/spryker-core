<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;

interface ProductOfferGuiPageFacadeInterface
{
    /**
     * Specification:
     * - Gets the list of concrete products and pagination data for product list table.
     * - Returns ProductConcreteCollectionTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductConcreteCollectionTransfer;
}
