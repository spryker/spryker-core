<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

interface ProductImageFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function persistProductImage(ProductImageTransfer $productImageTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function runProductAbstractCreatePlugin(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function runProductAbstractUpdatePlugin(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function runProductAbstractReadPlugin(ProductAbstractTransfer $productAbstractTransfer);

}
