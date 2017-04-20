<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroup\Business\ProductGroupFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;

class CreateProductGroupTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductGroup\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateProductGroupPersistNewEntitiesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        // Act
        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);
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
