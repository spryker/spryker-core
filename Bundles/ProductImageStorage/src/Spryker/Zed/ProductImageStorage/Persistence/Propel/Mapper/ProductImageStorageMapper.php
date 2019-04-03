<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\SpyProductImageSetEntityTransfer;

class ProductImageStorageMapper implements ProductImageStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function mapProductImageSetEntityToProductImageSetTransfer(
        SpyProductImageSetEntityTransfer $productImageSetEntity,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetTransfer {
        $productImageSetTransfer->fromArray($productImageSetEntity->toArray(), true);

        return $productImageSetTransfer;
    }
}
