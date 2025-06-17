<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use ArrayObject;
use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ProductAbstractTypesProductAbstractExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ProductAbstractTypesProductAbstractExpanderPluginTest
 */
class ProductAbstractTypesProductAbstractExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

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
    public function testExpandShouldExpandProductAbstractWithExistingProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $productAbstractTransfer,
            [$productAbstractTypeTransfer],
        );
        $productAbstractTransfer->setProductAbstractTypes(new ArrayObject());

        // Act
        $expandedProductAbstractTransfer = (new ProductAbstractTypesProductAbstractExpanderPlugin())
            ->expand($productAbstractTransfer);

        // Assert
        $this->assertCount(1, $expandedProductAbstractTransfer->getProductAbstractTypes());
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $expandedProductAbstractTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldNotExpandProductAbstractWithoutProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        // Act
        $expandedProductAbstractTransfer = (new ProductAbstractTypesProductAbstractExpanderPlugin())
            ->expand($productAbstractTransfer);

        // Assert
        $this->assertCount(0, $expandedProductAbstractTransfer->getProductAbstractTypes());
    }

    /**
     * @return void
     */
    public function testExpandShouldExpandProductAbstractWithMultipleProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $firstProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $secondProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $productAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $productAbstractTransfer,
            [$firstProductAbstractTypeTransfer, $secondProductAbstractTypeTransfer],
        );
        $productAbstractTransfer->setProductAbstractTypes(new ArrayObject());

        // Act
        $expandedProductAbstractTransfer = (new ProductAbstractTypesProductAbstractExpanderPlugin())
            ->expand($productAbstractTransfer);

        // Assert
        $this->assertCount(2, $expandedProductAbstractTransfer->getProductAbstractTypes());

        $productAbstractTypeIds = [];
        foreach ($expandedProductAbstractTransfer->getProductAbstractTypes() as $productAbstractTypeTransfer) {
            $productAbstractTypeIds[] = $productAbstractTypeTransfer->getIdProductAbstractType();
        }

        $this->assertContains($firstProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
        $this->assertContains($secondProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
    }
}
