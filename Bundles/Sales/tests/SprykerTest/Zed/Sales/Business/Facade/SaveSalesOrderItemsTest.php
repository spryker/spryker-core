<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SaveSalesOrderItemsTest
 * Add your own group annotations below this line
 */
class SaveSalesOrderItemsTest extends Unit
{
    /**
     * @var int
     */
    protected const TAX_RATE_DEFAULT = 10;

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected SalesFacadeInterface $salesFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->salesFacade = $this->tester->getFacade();
        $this->mockSalesConfig();
    }

    /**
     * @return void
     */
    public function testReturnsItemsWithTaxRateAsFloat(): void
    {
        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setTaxRate(static::TAX_RATE_DEFAULT);
        }

        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertIsFloat($itemTransfer->getTaxRate());
        }
    }

    /**
     * @return void
     */
    public function testReturnsItemsWithoutTaxRate(): void
    {
        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getTaxRate());
        }
    }

    /**
     * @dataProvider saveSalesOrderItemsPluginProvider
     *
     * @param string $dependencyKey
     * @param \PHPUnit\Framework\MockObject\MockObject $plugin
     *
     * @return void
     */
    public function testShouldExecute(string $dependencyKey, MockObject $plugin): void
    {
        // Assert
        $this->tester->setDependency($dependencyKey, [$plugin]);

        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return array<string, array<string, \PHPUnit\Framework\MockObject\MockObject>>
     */
    public function saveSalesOrderItemsPluginProvider(): array
    {
        return [
            'order item initial state provider plugin stack' => [
                SalesDependencyProvider::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER,
                $this->getOrderItemInitialStateProviderPluginMock(),
            ],
            'order item expander pre save plugin stack' => [
                SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS,
                $this->getOrderItemExpanderPreSavePluginMock(),
            ],
            'order items post save plugin stack' => [
                SalesDependencyProvider::PLUGINS_ORDER_ITEMS_POST_SAVE,
                $this->getOrderItemsPostSavePluginMock(),
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface
     */
    protected function getOrderItemInitialStateProviderPluginMock(): OrderItemInitialStateProviderPluginInterface
    {
        $orderItemInitialStateProviderPluginMock = $this
            ->getMockBuilder(OrderItemInitialStateProviderPluginInterface::class)
            ->getMock();

        $orderItemInitialStateProviderPluginMock
            ->expects($this->once())
            ->method('getInitialItemState');

        return $orderItemInitialStateProviderPluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface
     */
    protected function getOrderItemExpanderPreSavePluginMock(): OrderItemExpanderPreSavePluginInterface
    {
        $orderItemExpanderPreSavePluginMock = $this
            ->getMockBuilder(OrderItemExpanderPreSavePluginInterface::class)
            ->getMock();

        $orderItemExpanderPreSavePluginMock
            ->expects($this->once())
            ->method('expandOrderItem')
            ->willReturnCallback(function (
                QuoteTransfer $quoteTransfer,
                ItemTransfer $itemTransfer,
                SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
            ) {
                return $salesOrderItemEntityTransfer;
            });

        return $orderItemExpanderPreSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface
     */
    protected function getOrderItemsPostSavePluginMock(): OrderItemsPostSavePluginInterface
    {
        $orderItemsPostSavePluginMock = $this
            ->getMockBuilder(OrderItemsPostSavePluginInterface::class)
            ->getMock();

        $orderItemsPostSavePluginMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function (SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer) {
                return $saveOrderTransfer;
            });

        return $orderItemsPostSavePluginMock;
    }

    /**
     * @return void
     */
    protected function mockSalesConfig(): void
    {
        $businessFactory = new SalesBusinessFactory();

        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->onlyMethods(['determineProcessForOrderItem'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn(BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $businessFactory->setConfig($salesConfigMock);
        $this->salesFacade->setFactory($businessFactory);
    }
}
