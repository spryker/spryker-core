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
 * @group UpdateProductGroupTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductGroup\ProductGroupBusinessTester $tester
 */
class UpdateProductGroupTest extends Unit
{
    /**
     * @return void
     */
    public function testUpdateProductGroupPersistChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();

        $productGroupTransfer = (new ProductGroupBuilder([
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]))->build();

        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->updateProductGroup($productGroupTransfer);

        // Assert
        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);
        $this->assertCount(2, $actualProductGroupTransfer->getIdProductAbstracts(), 'Product group should have expected number of products.');

        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $productGroupTransfer->getIdProductGroup(), 'Product group should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer1->getIdProductAbstract(), 'Product #1 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer3->getIdProductAbstract(), 'Product #3 should have been touched as active.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #2 should have been touched as deleted.');
    }

    /**
     * @return void
     */
    public function testAddProductsToGroupPersistsChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();

        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->extendProductGroup($productGroupTransfer);

        // Assert
        $this->assertCount(3, $productGroupTransfer->getIdProductAbstracts(), 'Returned product group should have expected number of products.');

        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $productGroupTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer2->getIdProductAbstract(), $productGroupTransfer->getIdProductAbstracts()[1], 'Product #2 should be in position 2.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $productGroupTransfer->getIdProductAbstracts()[2], 'Product #3 should be in position 3.');

        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);
        $this->assertEquals($productGroupTransfer, $actualProductGroupTransfer, 'Persisted product group should match with returned values.');

        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $productGroupTransfer->getIdProductGroup(), 'Product group should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #1 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer3->getIdProductAbstract(), 'Product #3 should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testRemoveProductsFromGroupPersistsChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();

        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->removeFromProductGroup($productGroupTransfer);

        // Assert
        $this->assertCount(2, $productGroupTransfer->getIdProductAbstracts(), 'Returned product group should have expected number of products.');

        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $productGroupTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $productGroupTransfer->getIdProductAbstracts()[1], 'Product #3 should be in position 2.');

        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);
        $this->assertEquals($productGroupTransfer, $actualProductGroupTransfer, 'Persisted product group should match with returned values.');

        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $productGroupTransfer->getIdProductGroup(), 'Product group should have been touched as active.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #1 should have been touched as deleted.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer4->getIdProductAbstract(), 'Product #3 should have been touched as deleted.');
    }

    /**
     * @return void
     */
    public function testUpdateProductGroupTouchesProductAbstractGroupsAccordingToTheirState()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();

        $productGroupTransfer1 = new ProductGroupTransfer();
        $productGroupTransfer1->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);
        $productGroupTransfer1 = $this->tester->getFacade()->createProductGroup($productGroupTransfer1);

        $productGroupTransfer2 = new ProductGroupTransfer();
        $productGroupTransfer2->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
        ]);
        $this->tester->getFacade()->createProductGroup($productGroupTransfer2);

        // Act
        $productGroupTransfer1->setIdProductAbstracts([
            $productAbstractTransfer3->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $this->tester->getFacade()->updateProductGroup($productGroupTransfer1);

        // Assert
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer1->getIdProductAbstract(), 'Product #1 should have been touched as deleted.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #2 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer3->getIdProductAbstract(), 'Product #3 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer4->getIdProductAbstract(), 'Product #4 should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testRemoveProductsFromGroupTouchesProductAbstractGroupsAccordingToTheirState()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();

        $productGroupTransfer1 = new ProductGroupTransfer();
        $productGroupTransfer1->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $productGroupTransfer1 = $this->tester->getFacade()->createProductGroup($productGroupTransfer1);

        $productGroupTransfer2 = new ProductGroupTransfer();
        $productGroupTransfer2->setIdProductAbstracts([
            $productAbstractTransfer3->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $this->tester->getFacade()->createProductGroup($productGroupTransfer2);

        // Act
        $productGroupTransfer1->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
        ]);
        $this->tester->getFacade()->removeFromProductGroup($productGroupTransfer1);

        // Assert
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer1->getIdProductAbstract(), 'Product #1 should have been touched as active.');
        $this->tester->assertTouchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer2->getIdProductAbstract(), 'Product #2 should have been touched as deleted.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer3->getIdProductAbstract(), 'Product #3 should have been touched as active.');
        $this->tester->assertTouchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $productAbstractTransfer4->getIdProductAbstract(), 'Product #4 should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testUpdateProductGroupMultipleTimesIsIdempotent()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();

        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductAbstracts([
            $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productGroupTransfer = $this->tester->getFacade()->createProductGroup($productGroupTransfer);

        // Act
        $this->tester->getFacade()->updateProductGroup($productGroupTransfer);
        $this->tester->getFacade()->updateProductGroup($productGroupTransfer);

        // Assert
        $actualProductGroupTransfer = $this->tester->getFacade()->findProductGroup($productGroupTransfer);
        $this->assertCount(1, $actualProductGroupTransfer->getIdProductAbstracts(), 'Product group should have expected number of products.');
    }
}
