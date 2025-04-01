<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductPageSearch\ProductAbstractTypeProductPageDataExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductAbstractTypeProductPageDataExpanderPluginTest
 */
class ProductAbstractTypeProductPageDataExpanderPluginTest extends Unit
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
    public function testExpandProductPageDataShouldExpandProductPageSearchTransferWithProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTypeTransfer = new ProductAbstractTypeTransfer();
        $productAbstractTypeTransfer->setIdProductAbstractType(1);
        $productAbstractTypeTransfer->setName('Type 1');
        $productAbstractTypeTransfer->setKey('type-1');

        $productPayloadTransfer = new ProductPayloadTransfer();
        $productPayloadTransfer->addProductAbstractType($productAbstractTypeTransfer);

        $productData = [
            ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => $productPayloadTransfer,
        ];

        $productPageSearchTransfer = new ProductPageSearchTransfer();

        // Act
        (new ProductAbstractTypeProductPageDataExpanderPlugin())
            ->expandProductPageData($productData, $productPageSearchTransfer);

        // Assert
        $this->assertCount(1, $productPageSearchTransfer->getProductAbstractTypes());
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $productPageSearchTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
        );
        $this->assertSame(
            $productAbstractTypeTransfer->getName(),
            $productPageSearchTransfer->getProductAbstractTypes()[0]->getName(),
        );
        $this->assertSame(
            $productAbstractTypeTransfer->getKey(),
            $productPageSearchTransfer->getProductAbstractTypes()[0]->getKey(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataShouldExpandProductPageSearchTransferWithMultipleProductAbstractTypes(): void
    {
        // Arrange
        $firstProductAbstractTypeTransfer = new ProductAbstractTypeTransfer();
        $firstProductAbstractTypeTransfer->setIdProductAbstractType(1);
        $firstProductAbstractTypeTransfer->setName('Type 1');
        $firstProductAbstractTypeTransfer->setKey('type-1');

        $secondProductAbstractTypeTransfer = new ProductAbstractTypeTransfer();
        $secondProductAbstractTypeTransfer->setIdProductAbstractType(2);
        $secondProductAbstractTypeTransfer->setName('Type 2');
        $secondProductAbstractTypeTransfer->setKey('type-2');

        $productPayloadTransfer = new ProductPayloadTransfer();
        $productPayloadTransfer->addProductAbstractType($firstProductAbstractTypeTransfer);
        $productPayloadTransfer->addProductAbstractType($secondProductAbstractTypeTransfer);

        $productData = [
            ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => $productPayloadTransfer,
        ];

        $productPageSearchTransfer = new ProductPageSearchTransfer();

        // Act
        (new ProductAbstractTypeProductPageDataExpanderPlugin())
            ->expandProductPageData($productData, $productPageSearchTransfer);

        // Assert
        $this->assertCount(2, $productPageSearchTransfer->getProductAbstractTypes());

        $productAbstractTypeIds = [];
        foreach ($productPageSearchTransfer->getProductAbstractTypes() as $productAbstractTypeTransfer) {
            $productAbstractTypeIds[] = $productAbstractTypeTransfer->getIdProductAbstractType();
        }

        $this->assertContains($firstProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
        $this->assertContains($secondProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
    }
}
