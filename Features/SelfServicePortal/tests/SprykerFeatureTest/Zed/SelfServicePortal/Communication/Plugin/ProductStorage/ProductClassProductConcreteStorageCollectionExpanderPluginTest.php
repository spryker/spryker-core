<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductStorage\ProductClassProductConcreteStorageCollectionExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductStorage
 * @group ProductClassProductConcreteStorageCollectionExpanderPluginTest
 */
class ProductClassProductConcreteStorageCollectionExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandShouldExpandProductConcreteWithProductClassNames(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productClassTransfer = $this->tester->haveProductClass([
            'name' => 'Test Product Class',
        ]);

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcrete(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail())
            ->setAttributes([]);

        // Act
        $expandedProductConcreteStorageTransfers = (new ProductClassProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$productConcreteStorageTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteStorageTransfers);

        $productClassNames = $expandedProductConcreteStorageTransfers[0]->getProductClassNames();
        $this->assertNotEmpty($productClassNames);
        $this->assertContains($productClassTransfer->getName(), $productClassNames);
    }

    public function testExpandShouldNotExpandProductConcreteWithoutProductClasses(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail())
            ->setAttributes([]);

        // Act
        $expandedProductConcreteStorageTransfers = (new ProductClassProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$productConcreteStorageTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteStorageTransfers);

        $this->assertEmpty($expandedProductConcreteStorageTransfers[0]->getProductClassNames());
    }

    public function testExpandShouldExpandOnlyWithRelatedProductClasses(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveFullProduct();
        $secondProductConcreteTransfer = $this->tester->haveFullProduct();

        $productClassTransfer = $this->tester->haveProductClass([
            'name' => 'Test Product Class',
        ]);

        $this->tester->haveProductToProductClass(
            $firstProductConcreteTransfer->getIdProductConcrete(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $firstProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($firstProductConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($firstProductConcreteTransfer->getFkProductAbstractOrFail())
            ->setAttributes([]);

        $secondProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($secondProductConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($secondProductConcreteTransfer->getFkProductAbstractOrFail())
            ->setAttributes([]);

        // Act
        $expandedProductConcreteStorageTransfers = (new ProductClassProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$firstProductConcreteStorageTransfer, $secondProductConcreteStorageTransfer]);

        // Assert
        $this->assertCount(2, $expandedProductConcreteStorageTransfers);

        $firstProductClassNames = $expandedProductConcreteStorageTransfers[0]->getProductClassNames();
        $this->assertNotEmpty($firstProductClassNames);
        $this->assertContains($productClassTransfer->getName(), $firstProductClassNames);

        $this->assertEmpty($expandedProductConcreteStorageTransfers[1]->getProductClassNames());
    }

    public function testExpandShouldHandleEmptyCollection(): void
    {
        // Act
        $expandedProductConcreteStorageTransfers = (new ProductClassProductConcreteStorageCollectionExpanderPlugin())
            ->expand([]);

        // Assert
        $this->assertEmpty($expandedProductConcreteStorageTransfers);
    }
}
