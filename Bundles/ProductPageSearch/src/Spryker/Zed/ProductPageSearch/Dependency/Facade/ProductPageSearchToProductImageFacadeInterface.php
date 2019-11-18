<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer;

interface ProductPageSearchToProductImageFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct);

    /**
     * @param \Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer
     *
     * @return int[]
     */
    public function getProductConcreteIds(ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer): array;
}
