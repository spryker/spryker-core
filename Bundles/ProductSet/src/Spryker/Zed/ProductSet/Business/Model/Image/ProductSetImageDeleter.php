<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Image;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetImageDeleter implements ProductSetImageDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface $productImageFacade
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer, ProductSetToProductImageInterface $productImageFacade)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param int $idProductSet
     *
     * @return void
     */
    public function deleteImageSets($idProductSet)
    {
        $productImageSetCollection = $this->productSetQueryContainer
            ->queryProductImageSet($idProductSet)
            ->find();

        foreach ($productImageSetCollection as $productImageSetEntity) {
            $productImageSetTransfer = new ProductImageSetTransfer();
            $productImageSetTransfer->setIdProductImageSet($productImageSetEntity->getIdProductImageSet());

            $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);
        }
    }
}
