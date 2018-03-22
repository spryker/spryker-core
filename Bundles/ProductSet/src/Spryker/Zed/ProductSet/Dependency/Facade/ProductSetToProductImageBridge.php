<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;

class ProductSetToProductImageBridge implements ProductSetToProductImageInterface
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
     * @param int $idProductImageSet
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    public function findProductImageSetById($idProductImageSet)
    {
        return $this->productImageFacade->findProductImageSetById($idProductImageSet);
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
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    public function deleteProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);
    }
}
