<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Product;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Product\ProductAbstractTypeProductAbstractPostCreatePlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ProductAbstractTypeProductAbstractPostCreatePluginTest
 */
class ProductAbstractTypeProductAbstractPostCreatePluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->ensureProductAbstractTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testPostCreateShouldSaveProductAbstractTypesForProductAbstract(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);

        // Act
        $updatedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractPostCreatePlugin())
            ->postCreate($productAbstractTransfer);

        // Assert
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $updatedProductAbstractTransfer->getIdProductAbstract(),
        );

        $loadedProductAbstractTypeIds = $this->tester->getProductAbstractTypeIdsForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );

        $this->assertCount(1, $loadedProductAbstractTypeIds);
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $loadedProductAbstractTypeIds[0],
        );
    }

    /**
     * @return void
     */
    public function testPostCreateShouldHandleMultipleProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $firstProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $secondProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $productAbstractTransfer->addProductAbstractType($firstProductAbstractTypeTransfer);
        $productAbstractTransfer->addProductAbstractType($secondProductAbstractTypeTransfer);

        // Act
        $updatedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractPostCreatePlugin())
            ->postCreate($productAbstractTransfer);

        // Assert
        $loadedProductAbstractTypeIds = $this->tester->getProductAbstractTypeIdsForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );

        $this->assertCount(2, $loadedProductAbstractTypeIds);

        $this->assertContains($firstProductAbstractTypeTransfer->getIdProductAbstractType(), $loadedProductAbstractTypeIds);
        $this->assertContains($secondProductAbstractTypeTransfer->getIdProductAbstractType(), $loadedProductAbstractTypeIds);
    }

    /**
     * @return void
     */
    public function testPostCreateShouldHandleProductAbstractWithoutTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        // Act
        $updatedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractPostCreatePlugin())
            ->postCreate($productAbstractTransfer);

        // Assert
        $loadedProductAbstractTypeIds = $this->tester->getProductAbstractTypeIdsForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );

        $this->assertCount(0, $loadedProductAbstractTypeIds);
    }
}
