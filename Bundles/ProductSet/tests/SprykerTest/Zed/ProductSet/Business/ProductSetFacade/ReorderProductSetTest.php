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
 * @group ReorderProductSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class ReorderProductSetTest extends Unit
{
    /**
     * @return void
     */
    public function testUpdateProductSetAbstractProductsPersistChangesToDatabase()
    {
        $productSetTransfer1 = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::WEIGHT => 10,
        ]);
        $productSetTransfer2 = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::WEIGHT => 20,
        ]);
        $productSetTransfer3 = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::WEIGHT => 0,
        ]);

        $productSetTransfer1 = $this->tester->getFacade()->createProductSet($productSetTransfer1);
        $productSetTransfer2 = $this->tester->getFacade()->createProductSet($productSetTransfer2);
        $productSetTransfer3 = $this->tester->getFacade()->createProductSet($productSetTransfer3);

        $productSetTransfer1->setWeight(50);
        $productSetTransfer3->setWeight(10);

        // Act
        $this->tester->getFacade()->reorderProductSets([
            $productSetTransfer1,
            $productSetTransfer3,
        ]);

        // Assert
        $actualProductSetTransfer1 = $this->tester->getFacade()->findProductSet($productSetTransfer1);
        $this->assertSame($productSetTransfer1->getWeight(), $actualProductSetTransfer1->getWeight(), 'ProductSet 1/3 should have expected weight.');
        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer1->getIdProductSet(), 'ProductSet 1/3 should have been touched as active.');

        $actualProductSetTransfer2 = $this->tester->getFacade()->findProductSet($productSetTransfer2);
        $this->assertSame($productSetTransfer2->getWeight(), $actualProductSetTransfer2->getWeight(), 'ProductSet 2/3 should have expected weight.');
        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer2->getIdProductSet(), 'ProductSet 2/3 should have been touched as active.');

        $actualProductSetTransfer3 = $this->tester->getFacade()->findProductSet($productSetTransfer3);
        $this->assertSame($productSetTransfer3->getWeight(), $actualProductSetTransfer3->getWeight(), 'ProductSet 3/3 should have expected weight.');
        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer1->getIdProductSet(), 'ProductSet 3/3 should have been touched as active.');
    }
}
