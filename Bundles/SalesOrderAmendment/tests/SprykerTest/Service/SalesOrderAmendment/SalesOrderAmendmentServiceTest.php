<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group SalesOrderAmendment
 * @group SalesOrderAmendmentServiceTest
 * Add your own group annotations below this line
 */
class SalesOrderAmendmentServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\SalesOrderAmendment\SalesOrderAmendmentTester
     */
    protected SalesOrderAmendmentTester $tester;

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenItemSkuIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "sku" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.');

        // Act
        $this->tester->getService()->buildOriginalSalesOrderItemGroupKey(new ItemTransfer());
    }

    /**
     * @return void
     */
    public function testShouldUseItemSkuToToBuildTheKey(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setSku('sku');

        // Act
        $groupKey = $this->tester->getService()->buildOriginalSalesOrderItemGroupKey($itemTransfer);

        // Assert
        $this->assertSame('sku', $groupKey);
    }

    /**
     * @return void
     */
    public function testShouldExecuteOriginalSalesOrderItemsGroupKeyExpanderPlugins(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setSku('sku');
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY_EXPANDER,
            [$this->createOriginalSalesOrderItemGroupKeyExpanderPluginMock()],
        );

        // Act
        $this->tester->getService()->buildOriginalSalesOrderItemGroupKey($itemTransfer);
    }

    /**
     * @return \Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOriginalSalesOrderItemGroupKeyExpanderPluginMock(): OriginalSalesOrderItemGroupKeyExpanderPluginInterface
    {
        $originalSalesOrderItemsGroupKeyExpanderPluginMock = $this->getMockBuilder(OriginalSalesOrderItemGroupKeyExpanderPluginInterface::class)
            ->getMock();
        $originalSalesOrderItemsGroupKeyExpanderPluginMock->expects($this->once())->method('expandGroupKey');

        return $originalSalesOrderItemsGroupKeyExpanderPluginMock;
    }
}
