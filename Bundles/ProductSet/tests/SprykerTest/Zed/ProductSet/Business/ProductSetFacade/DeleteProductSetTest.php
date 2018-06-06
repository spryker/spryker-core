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
 * @group DeleteProductSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class DeleteProductSetTest extends Unit
{
    /**
     * @return void
     */
    public function testDeleteProductSetRemovesEntitiesFromDatabase()
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
        $this->tester->getFacade()->deleteProductSet($productSetTransfer);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertNull($actualProductSetTransfer, 'Product set should have been deleted from database.');

        $this->tester->assertTouchDeleted(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as deleted.');
    }
}
