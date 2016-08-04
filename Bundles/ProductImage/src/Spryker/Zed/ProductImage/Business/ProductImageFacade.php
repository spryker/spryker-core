<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getFactory()
 */
class ProductImageFacade extends AbstractFacade implements ProductImageFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function persistProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->persistProductImage($productImageTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->persistProductImageSet($productImageSetTransfer);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductAbstractId($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductId($idProduct);
    }

}
