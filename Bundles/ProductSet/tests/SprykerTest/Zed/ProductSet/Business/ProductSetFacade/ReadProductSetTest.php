<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Business\ProductSetFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductSetTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSet
 * @group Business
 * @group ProductSetFacade
 * @group ReadProductSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class ReadProductSetTest extends Unit
{
    /**
     * @return void
     */
    public function testReadProductSetHasLocalizedData()
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

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);

        // Assert
        $this->assertCount(1, $actualProductSetTransfer->getLocalizedData(), 'ProductSet should have expected number of localized data.');
        $this->assertSame(
            $productSetTransfer->getLocalizedData()[0]->getProductSetData()->toArray(),
            $actualProductSetTransfer->getLocalizedData()[0]->getProductSetData()->toArray(),
            'ProductSet name should be the same as generated data.'
        );
    }

    /**
     * @return void
     */
    public function testReadProductSetHasUrl()
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

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);

        // Assert
        $this->assertCount(1, $actualProductSetTransfer->getLocalizedData(), 'ProductSet should have expected number of localized data.');
        $this->assertNotNull(
            $actualProductSetTransfer->getLocalizedData()[0]->getUrl(),
            'ProductSet should have URL.'
        );
        $this->assertSame(
            $productSetTransfer->getLocalizedData()[0]->getUrl(),
            $actualProductSetTransfer->getLocalizedData()[0]->getUrl(),
            'ProductSet URL should be the same as generated data.'
        );
    }

    /**
     * @return void
     */
    public function testReadProductSetHasImages()
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

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);

        // Assert
        $this->assertCount(1, $actualProductSetTransfer->getImageSets(), 'ProductSet should have expected number of ProductImageSets.');
        $this->assertEquals(
            $productSetTransfer->getImageSets()[0]->toArray(),
            $actualProductSetTransfer->getImageSets()[0]->toArray(),
            'ImageSet should have expected data.'
        );
    }

    /**
     * @return void
     */
    public function testReadProductSetHasProductIdsInCorrectOrder()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();
        $productAbstractTransfer5 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
                $productAbstractTransfer4->getIdProductAbstract(),
                $productAbstractTransfer5->getIdProductAbstract(),
                $productAbstractTransfer3->getIdProductAbstract(),
            ],
        ]);

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);

        // Assert
        $this->assertCount(5, $actualProductSetTransfer->getIdProductAbstracts(), 'Product set should have expected number of products.');
        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $actualProductSetTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer2->getIdProductAbstract(), $actualProductSetTransfer->getIdProductAbstracts()[1], 'Product #2 should be in position 2.');
        $this->assertSame($productAbstractTransfer4->getIdProductAbstract(), $actualProductSetTransfer->getIdProductAbstracts()[2], 'Product #4 should be in position 3.');
        $this->assertSame($productAbstractTransfer5->getIdProductAbstract(), $actualProductSetTransfer->getIdProductAbstracts()[3], 'Product #5 should be in position 4.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $actualProductSetTransfer->getIdProductAbstracts()[4], 'Product #3 should be in position 5.');
    }
}
