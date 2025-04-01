<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Product;

use ArrayObject;
use Codeception\Test\Unit;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Product\ProductAbstractTypeProductAbstractAfterUpdatePlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ProductAbstractTypeProductAbstractAfterUpdatePluginTest
 */
class ProductAbstractTypeProductAbstractAfterUpdatePluginTest extends Unit
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
    public function testUpdateShouldUpdateProductAbstractTypesForProductAbstract(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);
        (new ProductAbstractTypeProductAbstractAfterUpdatePlugin())
            ->update($productAbstractTransfer);

        // Assert
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
    public function testUpdateShouldHandleMultipleProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        if ($productAbstractTransfer->getProductAbstractTypes() === null) {
            $productAbstractTransfer->setProductAbstractTypes(new ArrayObject());
        }

        $firstProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $secondProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $productAbstractTransfer->addProductAbstractType($firstProductAbstractTypeTransfer);
        $productAbstractTransfer->addProductAbstractType($secondProductAbstractTypeTransfer);

        // Act
        (new ProductAbstractTypeProductAbstractAfterUpdatePlugin())
            ->update($productAbstractTransfer);

        // Assert
        $productAbstractTypeIds = $this->tester->getProductAbstractTypeIdsForProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );

        $this->assertCount(2, $productAbstractTypeIds);

        $this->assertContains($firstProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
        $this->assertContains($secondProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
    }
}
