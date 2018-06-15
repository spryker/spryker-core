<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence\Mapper;

use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SpyProductListEntityTransfer;

class ProductListMapper implements ProductListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\SpyProductListEntityTransfer $spyProductListEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductListEntityTransfer
     */
    public function mapProductListTransferToEntityTransfer(
        ProductListTransfer $productListTransfer,
        SpyProductListEntityTransfer $spyProductListEntityTransfer
    ): SpyProductListEntityTransfer {
        return $spyProductListEntityTransfer->fromArray($productListTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductListEntityTransfer $spyProductListEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function mapEntityTransferToProductListTransfer(
        SpyProductListEntityTransfer $spyProductListEntityTransfer,
        ProductListTransfer $productListTransfer
    ): ProductListTransfer {
        return $productListTransfer->fromArray($spyProductListEntityTransfer->toArray(), true);
    }
}
