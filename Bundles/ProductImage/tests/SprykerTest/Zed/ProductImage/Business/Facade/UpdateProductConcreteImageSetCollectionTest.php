<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group UpdateProductConcreteImageSetCollectionTest
 * Add your own group annotations below this line
 */
class UpdateProductConcreteImageSetCollectionTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testUpdateProductConcreteImageSetCollection(): void
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setIdProductImage($this->image->getIdProductImage())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($this->imageSetConcrete->getIdProductImageSet())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        $this->productImageFacade->updateProductConcreteImageSetCollection(
            $productConcreteTransfer,
        );

        $this->assertConcreteCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testRemovalProductImageSetFromProductConcrete(): void
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();
        $productImageSetTransfers = new ArrayObject($this->productImageFacade->getProductImagesSetCollectionByProductId(
            $productConcreteTransfer->getIdProductConcrete(),
        ));

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->append($productImageSetTransfer);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalProductImageFromProductConcrete(): void
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();
        $productImageSetTransfers = new ArrayObject();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productImageSetTransfers->append($productImageSetTransfer);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImage($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImage($productImageSetTransfers->count());
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertConcreteHasNumberOfProductImageSet(int $expectedCount): void
    {
        $imageSetCollection = $this->queryContainer->queryImageSetByProductId(
            $this->productConcreteEntity->getIdProduct(),
        );

        $this->assertCount($expectedCount, $imageSetCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertConcreteHasNumberOfProductImage(int $expectedCount): void
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductId(
            $this->productConcreteEntity->getIdProduct(),
        );

        $this->assertCount($expectedCount, $imageCollection);
    }
}
