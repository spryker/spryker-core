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
 * @group UpdateProductAbstractImageSetCollectionTest
 * Add your own group annotations below this line
 */
class UpdateProductAbstractImageSetCollectionTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testUpdateProductAbstractImageSetCollection(): void
    {
        // Arrange
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setIdProductImage($this->image->getIdProductImage())
            ->setExternalUrlSmall(static::URL_SMALL . 'foo')
            ->setExternalUrlLarge(static::URL_LARGE . 'foo');

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($this->imageSetAbstract->getIdProductImageSet())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productAbstractTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->updateProductAbstractImageSetCollection(
            $productAbstractTransfer,
        );

        // Assert
        $this->assertAbstractCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testRemovalProductImageSetFromProductAbstract(): void
    {
        // Arrange, Act
        $productAbstractTransfer = $this->createProductAbstractTransfer();
        $productImageSetTransfers = new ArrayObject($this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->getIdProductAbstract(),
        ));

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        // Assert
        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->append($productImageSetTransfer);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalProductImageFromProductAbstract(): void
    {
        // Arrange
        $productAbstractTransfer = $this->createProductAbstractTransfer();
        $productImageSetTransfers = new ArrayObject();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productImageSetTransfers->append($productImageSetTransfer);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);

        // Act
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        // Assert
        $this->assertAbstractHasNumberOfProductImage($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImage($productImageSetTransfers->count());
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertAbstractHasNumberOfProductImageSet(int $expectedCount): void
    {
        $imageSetCollection = $this->queryContainer->queryImageSetByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        $this->assertCount($expectedCount, $imageSetCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertAbstractHasNumberOfProductImage(int $expectedCount): void
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        $this->assertCount($expectedCount, $imageCollection);
    }
}
