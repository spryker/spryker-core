<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductSalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentDependencyProvider;
use Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductSalesOrderAmendment
 * @group PriceProductSalesOrderAmendmentServiceTest
 * Add your own group annotations below this line
 */
class PriceProductSalesOrderAmendmentServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentTester
     */
    protected PriceProductSalesOrderAmendmentTester $tester;

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenItemSkuIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "sku" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.');

        // Act
        $this->tester->getService()->buildOriginalSalesOrderItemPriceGroupKey(new ItemTransfer());
    }

    /**
     * @return void
     */
    public function testShouldUseItemSkuToToBuildTheKey(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setSku('sku');

        // Act
        $groupKey = $this->tester->getService()->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer);

        // Assert
        $this->assertSame('sku', $groupKey);
    }

    /**
     * @return void
     */
    public function testShouldExecuteOriginalSalesOrderItemPriceGroupKeyExpanderPlugins(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setSku('sku');
        $this->tester->setDependency(
            PriceProductSalesOrderAmendmentDependencyProvider::PLUGINS_ORIGINAL_SALES_ORDER_ITEM_PRICE_GROUP_KEY_EXPANDER,
            [$this->createOriginalSalesOrderItemPriceGroupKeyExpanderPluginMock()],
        );

        // Act
        $this->tester->getService()->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnOriginalSalesOrderItemPriceBasedOnOriginalSalesOrderItemBestPriceConfig(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('useBestPriceBetweenOriginalAndSalesOrderItemPrice', true);

        // Act
        $unitPrice = $this->tester->getService()->resolveOriginalSalesOrderItemPrice(200, 100);

        // Assert
        $this->assertSame(100, $unitPrice);
    }

    /**
     * @return void
     */
    public function testShouldReturnDefaultPriceBasedOnOriginalSalesOrderItemBestPriceConfig(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('useBestPriceBetweenOriginalAndSalesOrderItemPrice', true);

        // Act
        $unitPrice = $this->tester->getService()->resolveOriginalSalesOrderItemPrice(100, 200);

        // Assert
        $this->assertSame(100, $unitPrice);
    }

    /**
     * @return void
     */
    public function testShouldReturnOriginalSalesOrderItemEvenIfDefaultPriceIsLess(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('useBestPriceBetweenOriginalAndSalesOrderItemPrice', false);

        // Act
        $unitPrice = $this->tester->getService()->resolveOriginalSalesOrderItemPrice(100, 200);

        // Assert
        $this->assertSame(200, $unitPrice);
    }

    /**
     * @return void
     */
    public function testShouldReturnOriginalSalesOrderItemEvenIfDefaultPriceIsBigger(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('useBestPriceBetweenOriginalAndSalesOrderItemPrice', false);

        // Act
        $unitPrice = $this->tester->getService()->resolveOriginalSalesOrderItemPrice(300, 200);

        // Assert
        $this->assertSame(200, $unitPrice);
    }

    /**
     * @return \Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOriginalSalesOrderItemPriceGroupKeyExpanderPluginMock(): OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface
    {
        $originalSalesOrderItemPriceGroupKeyExpanderPluginMock = $this->getMockBuilder(OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface::class)
            ->getMock();
        $originalSalesOrderItemPriceGroupKeyExpanderPluginMock->expects($this->once())->method('expandGroupKey');

        return $originalSalesOrderItemPriceGroupKeyExpanderPluginMock;
    }
}
