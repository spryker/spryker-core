<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Oms\Persistence\Base\SpyOmsOrderItemState;
use ReflectionClass;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\Persistence\SalesPersistenceFactory;
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
     * @var float
     */
    protected const TAX_RATE_DEFAULT = 10.0;

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
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testReturnsItemsWithTaxRateAsFloat(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);

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
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testReturnsItemsWithoutTaxRate(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);

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
     * @param array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface> $plugins
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldExecute(string $dependencyKey, array $plugins, string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->tester->setDependency($dependencyKey, $plugins);

        // Arrange
        $this->tester->createInitialState();
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        // Act
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldExecuteOrderItemInitialStateProviderPluginStack(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $omsOrderItemStateEntity = $this->tester->createInitialState();
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER,
            [$this->getOrderItemInitialStateProviderPluginMock($omsOrderItemStateEntity)],
        );

        // Arrange
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
            'order item expander pre save plugin stack' => [
                SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS,
                [
                    $this->getOrderItemExpanderPreSavePluginMock(),
                ],
                'hashColumn' => '',
            ],
            'order items post save plugin stack (hash column)' => [
                SalesDependencyProvider::PLUGINS_ORDER_ITEMS_POST_SAVE,
                [$this->getOrderItemsPostSavePluginMock()],
                'hashColumn' => 'OrderItemReference',
            ],
            'order items post save plugin stack' => [
                SalesDependencyProvider::PLUGINS_ORDER_ITEMS_POST_SAVE,
                [$this->getOrderItemsPostSavePluginMock()],
                'hashColumn' => '',
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getHashColumnDataProvider(): array
    {
        return [
            'test with a hash column' => [
                'hashColumn' => 'OrderItemReference',
            ],
            'test without a hash column' => [
                'hashColumn' => '',
            ],
        ];
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\Base\SpyOmsOrderItemState $omsOrderItemStateEntity
     *
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface
     */
    protected function getOrderItemInitialStateProviderPluginMock(
        SpyOmsOrderItemState $omsOrderItemStateEntity
    ): OrderItemInitialStateProviderPluginInterface {
        $orderItemInitialStateProviderPluginMock = $this
            ->getMockBuilder(OrderItemInitialStateProviderPluginInterface::class)
            ->getMock();

        $orderItemInitialStateProviderPluginMock
            ->expects($this->once())
            ->method('getInitialItemState')
            ->willReturn((new OmsOrderItemStateTransfer())->fromArray($omsOrderItemStateEntity->toArray(), true));

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
     * @param string $hashColumn
     *
     * @return void
     */
    protected function mockSalesConfig(string $hashColumn): void
    {
        $businessFactory = new SalesBusinessFactory();
        $persistenceFactory = new SalesPersistenceFactory();

        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->onlyMethods([
            'determineProcessForOrderItem',
            'getItemHashColumn',
        ])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn(BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $salesConfigMock->method('getItemHashColumn')->willReturn($hashColumn);

        $reflection = new ReflectionClass($this->salesFacade);
        $method = $reflection->getMethod('getEntityManager');
        $method->setAccessible(true);

        $entityManager = $method->invoke($this->salesFacade, 'getEntityManager');
        $persistenceFactory->setConfig($salesConfigMock);
        $entityManager->setFactory($persistenceFactory);

        $businessFactory->setConfig($salesConfigMock);
        $businessFactory->setEntityManager($entityManager);
        $this->salesFacade->setFactory($businessFactory);
    }

    /**
     * @param string $hashColumn
     *
     * @return void
     */
    protected function addOrderItemExpanderPreSavePlugins(string $hashColumn): void
    {
        if ($hashColumn === '') {
            return;
        }
        $this->tester->setDependency(
            SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS,
            [
                $this->tester->createHashGeneratorExpanderPlugin($hashColumn),
            ],
        );
    }
}
