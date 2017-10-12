<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Image;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetImageSaver implements ProductSetImageSaverInterface
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
    public function __construct(
        ProductSetQueryContainerInterface $productSetQueryContainer,
        ProductSetToProductImageInterface $productImageFacade
    ) {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function saveImageSets(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();

        foreach ($productSetTransfer->getImageSets() as $productImageSetTransfer) {
            $productImageSetTransfer->setFkResourceProductSet($productSetTransfer->getIdProductSet());
            $this->productImageFacade->saveProductImageSet($productImageSetTransfer);
        }

        $this->deleteMissingProductImageSets($productSetTransfer);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function deleteMissingProductImageSets(ProductSetTransfer $productSetTransfer)
    {
        $excludeIdProductImageSet = [];

        foreach ($productSetTransfer->getImageSets() as $productImageSetTransfer) {
            $excludeIdProductImageSet[] = $productImageSetTransfer->getIdProductImageSet();
        }

        $missingProductImageSets = $this->productSetQueryContainer
            ->queryExcludedProductImageSet($productSetTransfer->getIdProductSet(), $excludeIdProductImageSet)
            ->find();

        foreach ($missingProductImageSets as $productImageSetEntity) {
            $this->deleteProductImageSet($productImageSetEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return void
     */
    protected function deleteProductImageSet(SpyProductImageSet $productImageSetEntity)
    {
        $productImageSetTransfer = new ProductImageSetTransfer();
        $productImageSetTransfer->setIdProductImageSet($productImageSetEntity->getIdProductImageSet());

        $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);
    }
}
