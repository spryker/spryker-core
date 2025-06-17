<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch\ProductAbstractTypeProductPageDataLoaderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductAbstractTypeProductPageDataLoaderPluginTest
 */
class ProductAbstractTypeProductPageDataLoaderPluginTest extends Unit
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
    public function testExpandProductPageDataTransferShouldExpandWithProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $productAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $productAbstractTransfer,
            [$productAbstractTypeTransfer],
        );

        $productPageLoadTransfer = new ProductPageLoadTransfer();
        $productAbstractPayloadTransfer = (new ProductPayloadTransfer())
            ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        $productPageLoadTransfer->addProductAbstractIds($productAbstractTransfer->getIdProductAbstract())
            ->addPayloadTransfers($productAbstractPayloadTransfer);

        // Act
        $expandedProductPageLoadTransfer = (new ProductAbstractTypeProductPageDataLoaderPlugin())
            ->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $this->assertCount(1, $expandedProductPageLoadTransfer->getPayloadTransfers());

        $payloadTransfer = $expandedProductPageLoadTransfer->getPayloadTransfers()[$productAbstractTransfer->getIdProductAbstract()];
        $this->assertInstanceOf(ProductPayloadTransfer::class, $payloadTransfer);
        $this->assertCount(1, $payloadTransfer->getProductAbstractTypes());
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $payloadTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferShouldHandleMultipleProductAbstractIds(): void
    {
        // Arrange
        $firstProductAbstractTransfer = $this->tester->haveProductAbstract();
        $secondProductAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $firstProductAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $firstProductAbstractTransfer,
            [$productAbstractTypeTransfer],
        );

        $secondProductAbstractTransfer = $this->tester->addProductAbstractTypesToProductAbstract(
            $secondProductAbstractTransfer,
            [$productAbstractTypeTransfer],
        );

        $firstProductAbstractPayloadTransfer = (new ProductPayloadTransfer())
            ->setIdProductAbstract($firstProductAbstractTransfer->getIdProductAbstract());
        $secondProductAbstractPayloadTransfer = (new ProductPayloadTransfer())
            ->setIdProductAbstract($secondProductAbstractTransfer->getIdProductAbstract());

        $productPageLoadTransfer = new ProductPageLoadTransfer();
        $productPageLoadTransfer->addProductAbstractIds($firstProductAbstractTransfer->getIdProductAbstract());
        $productPageLoadTransfer->addProductAbstractIds($secondProductAbstractTransfer->getIdProductAbstract());
        $productPageLoadTransfer->addPayloadTransfers($firstProductAbstractPayloadTransfer);
        $productPageLoadTransfer->addPayloadTransfers($secondProductAbstractPayloadTransfer);

        // Act
        $expandedProductPageLoadTransfer = (new ProductAbstractTypeProductPageDataLoaderPlugin())
            ->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $this->assertCount(2, $expandedProductPageLoadTransfer->getPayloadTransfers());

        foreach ($expandedProductPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $this->assertInstanceOf(ProductPayloadTransfer::class, $payloadTransfer);
            $this->assertCount(1, $payloadTransfer->getProductAbstractTypes());
            $this->assertSame(
                $productAbstractTypeTransfer->getIdProductAbstractType(),
                $payloadTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
            );
        }
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferShouldHandleEmptyProductAbstractIds(): void
    {
        // Arrange
        $productPageLoadTransfer = new ProductPageLoadTransfer();

        // Act
        $expandedProductPageLoadTransfer = (new ProductAbstractTypeProductPageDataLoaderPlugin())
            ->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $this->assertSame($productPageLoadTransfer, $expandedProductPageLoadTransfer);
        $this->assertEmpty($expandedProductPageLoadTransfer->getPayloadTransfers());
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferShouldHandleProductAbstractWithoutTypes(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $productAbstractPayloadTransfer = (new ProductPayloadTransfer())
            ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $productPageLoadTransfer = new ProductPageLoadTransfer();
        $productPageLoadTransfer->addProductAbstractIds($productAbstractTransfer->getIdProductAbstract());
        $productPageLoadTransfer->addPayloadTransfers($productAbstractPayloadTransfer);

        // Act
        $expandedProductPageLoadTransfer = (new ProductAbstractTypeProductPageDataLoaderPlugin())
            ->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $this->assertCount(1, $expandedProductPageLoadTransfer->getPayloadTransfers());

        $payloadTransfer = $expandedProductPageLoadTransfer->getPayloadTransfers()[$productAbstractTransfer->getIdProductAbstract()];
        $this->assertInstanceOf(ProductPayloadTransfer::class, $payloadTransfer);
        $this->assertEmpty($payloadTransfer->getProductAbstractTypes());
    }
}
