<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroup\Business\ProductGroupFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;

/**
 *
 */
class DeleteProductGroupTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductGroup\ProductGroupBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteProductGroupRemovesEntitiesFromDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);
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
