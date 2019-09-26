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
 * @group CreateProductGroupTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductGroup\ProductGroupBusinessTester $tester
 */
class CreateProductGroupTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateProductGroupPersistNewEntitiesToDatabase()
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

        // Act
        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Assert
        $this->assertGreaterThan(0, $productGroupTransfer->getIdProductGroup(), 'Product group should have ID after creation.');
        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);

        $this->assertCount(2, $actualProductGroupTransfer->getIdProductAbstracts(), 'Product group should have expected number of products.');

        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $productGroupTransfer->getIdProductGroup(), 'Product group should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer1->getIdProductAbstract(), 'Product #1 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #2 should have been touched as active.');
    }
}
