<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductPageSearch\ProductClassProductAbstractMapExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductClassProductAbstractMapExpanderPluginTest
 */
class ProductClassProductAbstractMapExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandProductMapAddsProductClassDataToPageMap(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productClass = $this->tester->haveProductClass([
            'name' => 'Test Product Class',
        ]);

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcrete(),
            $productClass->getIdProductClassOrFail(),
        );

        $productData = [
            'id_product_abstract' => $productConcreteTransfer->getFkProductAbstractOrFail(),
            'product_class_names' => [
                $productClass->getName(),
            ],
        ];

        $pageMapTransfer = new PageMapTransfer();
        $pageMapBuilder = $this->createPageMapBuilderMock();

        $pageMapBuilder->expects($this->once())
            ->method('addSearchResultData')
            ->with(
                $this->anything(),
                'product-class-names',
                ['Test Product Class'],
            )
            ->willReturnSelf();

        $pageMapBuilder->expects($this->once())
            ->method('addStringFacet')
            ->with(
                $this->anything(),
                'product-class-names',
                'Test Product Class',
            )
            ->willReturnSelf();

        $localeTransfer = (new LocaleTransfer())->setIdLocale(66);

        $plugin = new ProductClassProductAbstractMapExpanderPlugin();

        // Act
        $resultPageMapTransfer = $plugin->expandProductMap(
            $pageMapTransfer,
            $pageMapBuilder,
            $productData,
            $localeTransfer,
        );

        // Assert
        $this->assertNotNull($resultPageMapTransfer);
    }

    public function testExpandProductMapHandlesEmptyProductClasses(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productData = [
            'id_product_abstract' => $productAbstractTransfer->getIdProductAbstractOrFail(),
            'product_classes' => [],
        ];

        $pageMapTransfer = new PageMapTransfer();
        $pageMapBuilder = $this->createPageMapBuilderMock();

        $pageMapBuilder->expects($this->never())
            ->method('addSearchResultData');

        $pageMapBuilder->expects($this->never())
            ->method('addStringFacet');

        $localeTransfer = (new LocaleTransfer())->setIdLocale(66);

        $plugin = new ProductClassProductAbstractMapExpanderPlugin();

        // Act
        $resultPageMapTransfer = $plugin->expandProductMap(
            $pageMapTransfer,
            $pageMapBuilder,
            $productData,
            $localeTransfer,
        );

        // Assert
        $this->assertNotNull($resultPageMapTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface
     */
    protected function createPageMapBuilderMock(): PageMapBuilderInterface
    {
        $pageMapBuilderMock = $this->getMockBuilder(PageMapBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $pageMapBuilderMock;
    }
}
