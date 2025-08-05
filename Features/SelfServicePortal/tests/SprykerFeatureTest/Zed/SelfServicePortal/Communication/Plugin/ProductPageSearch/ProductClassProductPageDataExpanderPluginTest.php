<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch\ProductClassProductPageDataExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductClassProductPageDataExpanderPluginTest
 */
class ProductClassProductPageDataExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandProductPageDataAddsProductClassNames(): void
    {
        // Arrange
        $productClassNames = ['Test Product Class 1', 'Test Product Class 2'];

        $productPayloadTransfer = (new ProductPayloadTransfer())
            ->setProductClassNames($productClassNames);

        $productData = [
            ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => $productPayloadTransfer,
        ];

        $productPageSearchTransfer = new ProductPageSearchTransfer();
        $plugin = new ProductClassProductPageDataExpanderPlugin();

        // Act
        $plugin->expandProductPageData($productData, $productPageSearchTransfer);

        // Assert
        $this->assertSame($productClassNames, $productPageSearchTransfer->getProductClassNames());
    }

    public function testExpandProductPageDataWithEmptyProductClassNames(): void
    {
        // Arrange
        $productClassNames = [];

        $productPayloadTransfer = (new ProductPayloadTransfer())
            ->setProductClassNames($productClassNames);

        $productData = [
            ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => $productPayloadTransfer,
        ];

        $productPageSearchTransfer = new ProductPageSearchTransfer();
        $plugin = new ProductClassProductPageDataExpanderPlugin();

        // Act
        $plugin->expandProductPageData($productData, $productPageSearchTransfer);

        // Assert
        $this->assertEmpty($productPageSearchTransfer->getProductClassNames());
    }
}
