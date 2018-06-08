<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Business\ProductSetFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Shared\ProductSet\ProductSetConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductSet
 * @group Business
 * @group ProductSetFacade
 * @group CreateProductSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class CreateProductSetTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateProductSetPersistNewEntitiesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);

        // Act
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Assert
        $this->assertGreaterThan(0, $productSetTransfer->getIdProductSet(), 'ProductSet should have ID after creation.');
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);

        $this->assertCount(2, $actualProductSetTransfer->getIdProductAbstracts(), 'ProductSet should have expected number of products.');
        $this->assertCount(1, $actualProductSetTransfer->getLocalizedData(), 'ProductSet should have expected number of localized data.');
        $this->assertCount(1, $actualProductSetTransfer->getImageSets()[0]->getProductImages(), 'ProductImageSet should have expected number of images.');

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }
}
