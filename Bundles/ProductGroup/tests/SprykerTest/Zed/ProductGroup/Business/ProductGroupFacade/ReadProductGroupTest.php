<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroup\Business\ProductGroupFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductGroupBuilder;
use Generated\Shared\Transfer\ProductGroupTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductGroup
 * @group Business
 * @group ProductGroupFacade
 * @group ReadProductGroupTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductGroup\ProductGroupBusinessTester $tester
 */
class ReadProductGroupTest extends Unit
{
    /**
     * @return void
     */
    public function testReadProductGroupHasProductIdsInCorrectOrder()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();
        $productAbstractTransfer5 = $this->tester->haveProductAbstract();

        $productGroupTransfer = (new ProductGroupBuilder([
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
                $productAbstractTransfer4->getIdProductAbstract(),
                $productAbstractTransfer5->getIdProductAbstract(),
                $productAbstractTransfer3->getIdProductAbstract(),
            ],
        ]))->build();

        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);

        // Assert
        $this->assertCount(5, $actualProductGroupTransfer->getIdProductAbstracts(), 'Product group should have expected number of products.');
        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $actualProductGroupTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer2->getIdProductAbstract(), $actualProductGroupTransfer->getIdProductAbstracts()[1], 'Product #2 should be in position 2.');
        $this->assertSame($productAbstractTransfer4->getIdProductAbstract(), $actualProductGroupTransfer->getIdProductAbstracts()[2], 'Product #4 should be in position 3.');
        $this->assertSame($productAbstractTransfer5->getIdProductAbstract(), $actualProductGroupTransfer->getIdProductAbstracts()[3], 'Product #5 should be in position 4.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $actualProductGroupTransfer->getIdProductAbstracts()[4], 'Product #3 should be in position 5.');
    }
}
