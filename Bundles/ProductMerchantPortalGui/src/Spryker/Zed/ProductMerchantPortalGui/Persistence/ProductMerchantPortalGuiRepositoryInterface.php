<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;

interface ProductMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractTableData(
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): ProductAbstractCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function getPriceProductTableData(
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): PriceProductTableViewCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductConcreteCollectionTransfer;
}
