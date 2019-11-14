<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

interface ProductPageSearchToProductImageFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct);

    /**
     * @param int[] $productImageIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageIds(array $productImageIds): array;

    /**
     * @param int[] $productImageSetIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageSetIds(array $productImageSetIds): array;
}
