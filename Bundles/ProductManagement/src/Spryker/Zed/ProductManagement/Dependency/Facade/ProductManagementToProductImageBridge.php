<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

class ProductManagementToProductImageBridge implements ProductManagementToProductImageInterface
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
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function saveProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->productImageFacade->saveProductImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function saveProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->productImageFacade->saveProductImageSet($productImageSetTransfer);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        return $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId($idProductAbstract);
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
}
