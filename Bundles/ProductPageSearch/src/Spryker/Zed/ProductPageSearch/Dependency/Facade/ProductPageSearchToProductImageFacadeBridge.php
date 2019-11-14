<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

class ProductPageSearchToProductImageFacadeBridge implements ProductPageSearchToProductImageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface $productImageFacade
     */
    public function __construct($productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        return $this->productImageFacade->getProductImagesSetCollectionByProductId($idProduct);
    }

    /**
     * @param int[] $productImageIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageIds(array $productImageIds): array
    {
        return $this->productImageFacade->getProductConcreteIdsByProductImageIds($productImageIds);
    }

    /**
     * @param int[] $productImageSetIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageSetIds(array $productImageSetIds): array
    {
        return $this->productImageFacade->getProductConcreteIdsByProductImageSetIds($productImageSetIds);
    }
}
