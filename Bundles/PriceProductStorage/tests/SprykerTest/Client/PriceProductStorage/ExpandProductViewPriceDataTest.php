<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface;
use Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpander;
use Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpanderInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductStorage
 * @group ExpandProductViewPriceDataTest
 * Add your own group annotations below this line
 */
class ExpandProductViewPriceDataTest extends Unit
{
    /**
     * @var \SprykerTest\Client\PriceProductStorage\PriceProductStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductViewPriceDataEnsureThatPriceProductExpanderPluginStackExecuted(): void
    {
        // Arrange
        $productViewTransfer = new ProductViewTransfer();

        // Assert
        $priceProductExpanderPluginMock = $this->getPriceProductExpanderPluginMock();

        // Act
        $this->createProductViewPriceExpanderMock([], [$priceProductExpanderPluginMock])
            ->expandProductViewPriceData($productViewTransfer);
    }

    /**
     * @return void
     */
    public function testExpandProductViewPriceDataEnsureThatPriceProductFilterExpanderPluginStackExecuted(): void
    {
        // Arrange
        $productViewTransfer = new ProductViewTransfer();

        // Assert
        $priceProductFilterExpanderPluginMock = $this->getPriceProductFilterExpanderPluginMock();

        // Act
        $this->createProductViewPriceExpanderMock([$priceProductFilterExpanderPluginMock])
            ->expandProductViewPriceData($productViewTransfer);
    }

    /**
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface>|null $priceProductFilterExpanderPlugins
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface>|null $priceProductExpanderPlugins
     *
     * @return \Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpanderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductViewPriceExpanderMock(
        ?array $priceProductFilterExpanderPlugins = [],
        ?array $priceProductExpanderPlugins = []
    ): ProductViewPriceExpanderInterface {
        return $this->getMockBuilder(ProductViewPriceExpander::class)
            ->onlyMethods(['expandProductViewPriceData'])
            ->setConstructorArgs([
                $this->getMockBuilder(PriceAbstractStorageReaderInterface::class)->getMock(),
                $this->getMockBuilder(PriceConcreteStorageReaderInterface::class)->getMock(),
                $this->getMockBuilder(PriceProductStorageToPriceProductClientInterface::class)->getMock(),
                $this->getMockBuilder(PriceProductStorageToPriceProductServiceInterface::class)->getMock(),
                $priceProductFilterExpanderPlugins,
                $priceProductExpanderPlugins,
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface
     */
    protected function getPriceProductExpanderPluginMock(): PriceProductExpanderPluginInterface
    {
        $priceProductExpanderPluginMock = $this
            ->getMockBuilder(PriceProductExpanderPluginInterface::class)
            ->onlyMethods(['expand'])
            ->getMock();

        $priceProductExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturn([]);

        return $priceProductExpanderPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface
     */
    protected function getPriceProductFilterExpanderPluginMock(): PriceProductFilterExpanderPluginInterface
    {
        $priceProductFilterExpanderPluginMock = $this
            ->getMockBuilder(PriceProductFilterExpanderPluginInterface::class)
            ->onlyMethods(['expand'])
            ->getMock();

        $priceProductFilterExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturn(new PriceProductFilterTransfer());

        return $priceProductFilterExpanderPluginMock;
    }
}
