<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ProductClassesProductConcreteExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ProductClassesProductConcreteExpanderPluginTest
 */
class ProductClassesProductConcreteExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandProductConcrete(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productClassTransfer = $this->tester->haveProductClass();

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $plugin = new ProductClassesProductConcreteExpanderPlugin();

        // Act
        $expandedProductConcreteTransfers = $plugin->expand([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteTransfers);
        $this->assertSame($productConcreteTransfer, $expandedProductConcreteTransfers[0]);
        $this->assertCount(1, $expandedProductConcreteTransfers[0]->getProductClasses());
        $this->assertSame(
            $productClassTransfer->getIdProductClassOrFail(),
            $expandedProductConcreteTransfers[0]->getProductClasses()[0]->getIdProductClassOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithMultipleProductClasses(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $firstProductClassTransfer = $this->tester->haveProductClass();
        $secondProductClassTransfer = $this->tester->haveProductClass();

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $firstProductClassTransfer->getIdProductClassOrFail(),
        );
        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $secondProductClassTransfer->getIdProductClassOrFail(),
        );

        $plugin = new ProductClassesProductConcreteExpanderPlugin();

        // Act
        $expandedProductConcreteTransfers = $plugin->expand([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteTransfers);
        $this->assertSame($productConcreteTransfer, $expandedProductConcreteTransfers[0]);
        $this->assertCount(2, $expandedProductConcreteTransfers[0]->getProductClasses());
    }

    /**
     * @return void
     */
    public function testExpandMultipleProductConcretes(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $productClassTransfer = $this->tester->haveProductClass();

        $this->tester->haveProductToProductClass(
            $firstProductConcreteTransfer->getIdProductConcreteOrFail(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $plugin = new ProductClassesProductConcreteExpanderPlugin();

        // Act
        $expandedProductConcreteTransfers = $plugin->expand([
            $firstProductConcreteTransfer,
            $secondProductConcreteTransfer,
        ]);

        // Assert
        $this->assertCount(2, $expandedProductConcreteTransfers);
        $this->assertCount(1, $expandedProductConcreteTransfers[0]->getProductClasses());
        $this->assertSame(
            $productClassTransfer->getIdProductClassOrFail(),
            $expandedProductConcreteTransfers[0]->getProductClasses()[0]->getIdProductClassOrFail(),
        );
        $this->assertCount(0, $expandedProductConcreteTransfers[1]->getProductClasses());
    }
}
