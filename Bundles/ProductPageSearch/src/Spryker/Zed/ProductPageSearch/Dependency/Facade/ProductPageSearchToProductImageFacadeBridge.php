<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer;

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
     * @param \Generated\Shared\Transfer\ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer
     *
     * @return int[]
     */
    public function getProductConcreteIds(ProductImageCriteriaFilterTransfer $productImageCriteriaFilterTransfer): array
    {
        return $this->productImageFacade->getProductConcreteIds($productImageCriteriaFilterTransfer);
    }
}
