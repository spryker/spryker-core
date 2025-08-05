<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch\ProductClassProductPageDataLoaderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductClassProductPageDataLoaderPluginTest
 */
class ProductClassProductPageDataLoaderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandProductPageDataTransferWithProductClasses(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productAbstractId = $productConcreteTransfer->getFkProductAbstractOrFail();

        $productClassTransfer = $this->tester->haveProductClass([
            'name' => 'Test Product Class',
        ]);

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcrete(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $productPayloadTransfer = new ProductPayloadTransfer();
        $productPayloadTransfer->setIdProductAbstract($productAbstractId);

        $productPageLoadTransfer = new ProductPageLoadTransfer();
        $productPageLoadTransfer->setPayloadTransfers([$productPayloadTransfer]);

        $plugin = new ProductClassProductPageDataLoaderPlugin();

        // Act
        $resultProductPageLoadTransfer = $plugin->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $resultPayloadTransfers = $resultProductPageLoadTransfer->getPayloadTransfers();
        $this->assertCount(1, $resultPayloadTransfers);

        $resultPayloadTransfer = $resultPayloadTransfers[0];
        $this->assertSame($productAbstractId, $resultPayloadTransfer->getIdProductAbstract());

        $productClassNames = $resultPayloadTransfer->getProductClassNames();
        $this->assertNotEmpty($productClassNames);
        $this->assertContains($productClassTransfer->getName(), $productClassNames);
    }

    public function testExpandProductPageDataTransferWithNoProductClasses(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productAbstractId = $productConcreteTransfer->getFkProductAbstractOrFail();

        $productPayloadTransfer = new ProductPayloadTransfer();
        $productPayloadTransfer->setIdProductAbstract($productAbstractId);

        $productPageLoadTransfer = new ProductPageLoadTransfer();
        $productPageLoadTransfer->setPayloadTransfers([$productPayloadTransfer]);

        $plugin = new ProductClassProductPageDataLoaderPlugin();

        // Act
        $resultProductPageLoadTransfer = $plugin->expandProductPageDataTransfer($productPageLoadTransfer);

        // Assert
        $resultPayloadTransfers = $resultProductPageLoadTransfer->getPayloadTransfers();
        $this->assertCount(1, $resultPayloadTransfers);

        $resultPayloadTransfer = $resultPayloadTransfers[0];
        $this->assertSame($productAbstractId, $resultPayloadTransfer->getIdProductAbstract());
        $this->assertEmpty($resultPayloadTransfer->getProductClassNames());
    }
}
