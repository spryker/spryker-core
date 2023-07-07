<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OmsEventTriggerResponseTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group CancelOrderTest
 * Add your own group annotations below this line
 */
class CancelOrderTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS = 'Test05';

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 6666;

    /**
     * @var string
     */
    protected const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';

    /**
     * @var string
     */
    protected const CANCELLED_STATE_NAME = 'cancelled';

    /**
     * @see \Spryker\Zed\Sales\Business\Writer\OrderWriter::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND = 'sales.error.customer_order_not_found';

    /**
     * @see \Spryker\Zed\Sales\Business\Writer\OrderWriter::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED = 'sales.error.order_cannot_be_canceled_due_to_wrong_item_state';

    /**
     * @uses \Spryker\Zed\Sales\Business\Writer\OrderWriter::OMS_EVENT_TRIGGER_RESPONSE
     *
     * @var string
     */
    protected const OMS_EVENT_TRIGGER_RESPONSE = 'oms_event_trigger_response';

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    protected const COL_GRAND_TOTAL = 'grand_total';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([
            static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS,
            static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS,
        ]);
    }

    /**
     * @return void
     */
    public function testCancelOrderWithCancellableOrderItems(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()],
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertTrue($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::CANCELLED_STATE_NAME,
            $orderCancelResponseTransfer->getOrder()->getItems()->getIterator()->current()->getState()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithCancellableOrderItemsForAnotherCustomer(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()],
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($this->tester->haveCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithFakeOrderReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder(static::FAKE_ID_SALES_ORDER)
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithFakeCustomerReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer((new CustomerTransfer())->setCustomerReference(static::FAKE_CUSTOMER_REFERENCE));

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND,
            $orderCancelResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testCancelOrderWithoutRequiredOrderReferenceField(): void
    {
        // Arrange
        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setCustomer((new CustomerTransfer())->setCustomerReference(static::FAKE_CUSTOMER_REFERENCE));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCancelOrderReturnsErrorMessageInCaseOfNotSuccessfulEventTriggering()
    {
        // Arrange
        if (!method_exists($this->tester, 'getOmsEventTriggerResponseTransfer')) {
            $this->markTestSkipped('Sales does not support current OMS version.');
        }

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(
            static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS,
        );
        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($orderTransfer->getCustomer());

        $messageText = 'test message';
        $omsFacadeMock = $this->createMock(SalesToOmsInterface::class);
        $omsFacadeMock->expects($this->once())
            ->method('triggerEventForOrderItems')
            ->willReturn([
                static::OMS_EVENT_TRIGGER_RESPONSE => $this->tester->getOmsEventTriggerResponseTransfer([
                    OmsEventTriggerResponseTransfer::MESSAGES => [
                        [MessageTransfer::VALUE => $messageText],
                    ],
                ]),
            ]);
        $this->tester->setDependency(SalesDependencyProvider::FACADE_OMS, $omsFacadeMock);
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()],
        );

        // Act
        $orderCancelResponseTransfer = $this->tester->getFacade()->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $orderCancelResponseTransfer->getMessages());
        $this->assertEquals($messageText, $orderCancelResponseTransfer->getMessages()[0]->getValue());
    }

    /**
     * @return void
     */
    public function testCancelOrderWithNonCancellableOrderItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setCustomer($orderTransfer->getCustomer());

        // Act
        $orderCancelResponseTransfer = $this->tester
            ->getFacade()
            ->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertFalse($orderCancelResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED,
            $orderCancelResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsOrderExpandedWithLastGrandTotal(): void
    {
        // Arrange
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [$this->getOrderExpanderPluginMock()],
        );

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $dateTime = new DateTime();
        $this->tester->createSalesOrderTotals($orderTransfer->getIdSalesOrderOrFail(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 500,
        ]);
        $salesOrderTotalsLastEntity = $this->tester->createSalesOrderTotals($orderTransfer->getIdSalesOrderOrFail(), [
            static::COL_CREATED_AT => $dateTime,
            static::COL_GRAND_TOTAL => 600,
        ]);

        $orderCancelRequestTransfer = (new OrderCancelRequestTransfer())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrderOrFail())
            ->setCustomer($orderTransfer->getCustomerOrFail());

        // Act
        $orderCancelResponseTransfer = $this->tester->getFacade()->cancelOrder($orderCancelRequestTransfer);

        // Assert
        $this->assertSame(
            $salesOrderTotalsLastEntity->getGrandTotal(),
            $orderCancelResponseTransfer->getOrder()->getTotals()->getGrandTotal(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface
     */
    protected function getOrderExpanderPluginMock(): OrderExpanderPluginInterface
    {
        $orderExpanderPluginMock = $this
            ->getMockBuilder(OrderExpanderPluginInterface::class)
            ->getMock();

        $orderExpanderPluginMock
            ->method('hydrate')
            ->willReturnCallback(function (OrderTransfer $orderTransfer) {
                $orderTransfer->setIsCancellable(true);

                return $orderTransfer;
            });

        return $orderExpanderPluginMock;
    }
}
