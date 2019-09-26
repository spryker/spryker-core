<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroup\Business\ProductGroupFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductGroupBuilder;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductGroup
 * @group Business
 * @group ProductGroupFacade
 * @group DeleteProductGroupTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductGroup\ProductGroupBusinessTester $tester
 */
class DeleteProductGroupTest extends Unit
{
    /**
     * @return void
     */
    public function testDeleteProductGroupRemovesEntitiesFromDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productGroupTransfer = (new ProductGroupBuilder([
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]))->build();

        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $this->tester->getFacade()->deleteProductGroup($productGroupTransfer);

        // Assert
        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);
        $this->assertNull($actualProductGroupTransfer, 'Product group should have been deleted from database.');

        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $productGroupTransfer->getIdProductGroup(), 'Product group should have been touched as deleted.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer1->getIdProductAbstract(), 'Product #1 should have been touched as deleted.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #2 should have been touched as deleted.');
    }
}
