<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group DeleteProductImageSetTest
 * Add your own group annotations below this line
 */
class DeleteProductImageSetTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testDeleteProductImageSet(): void
    {
        // Arrange
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $imageSet = new SpyProductImageSet();
        $imageSet
            ->setName(static::SET_NAME)
            ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($imageSet->getIdProductImageSet());

        // Act
        $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);

        // Assert
        $this->assertProductImageSetNotExists($imageSet->getIdProductImageSet());
    }

    /**
     * @param int $idProductImageSet
     *
     * @return void
     */
    protected function assertProductImageSetNotExists(int $idProductImageSet): void
    {
        $exists = (new SpyProductImageSetQuery())
            ->filterByIdProductImageSet($idProductImageSet)
            ->exists();

        $this->assertFalse($exists);
    }
}
