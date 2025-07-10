<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderAmendmentExample\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\OrderAmendmentExample\Business\OrderAmendmentExampleBusinessFactory;
use Spryker\Zed\OrderAmendmentExample\Communication\Plugin\Oms\ApplyOrderAmendmentDraftCommandByOrderPlugin;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\OrderAmendmentExampleDependencyProvider;
use SprykerTest\Zed\OrderAmendmentExample\OrderAmendmentExampleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OrderAmendmentExample
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group ApplyOrderAmendmentDraftCommandByOrderPluginTest
 * Add your own group annotations below this line
 */
class ApplyOrderAmendmentDraftCommandByOrderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'test-order-reference';

    /**
     * @var int
     */
    protected const ID_SALES_ORDER = 1;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'error-message';

    /**
     * @uses \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor::RETURN_DATA_UPDATED_ORDER_ITEMS
     *
     * @var string
     */
    protected const RETURN_DATA_UPDATED_ORDER_ITEMS = 'updatedOrderItems';

    /**
     * @uses \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE
     *
     * @var string
     */
    protected const ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE = 'order amendment draft applied';

    /**
     * @uses \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor::GLOSSARY_KEY_ERROR_APPLY_ORDER_AMENDMENT_DRAFT_FAILED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_APPLY_ORDER_AMENDMENT_DRAFT_FAILED = 'sales_order_amendment_oms.error.apply_order_amendment_draft_failed';

    /**
     * @var \SprykerTest\Zed\OrderAmendmentExample\OrderAmendmentExampleCommunicationTester
     */
    protected OrderAmendmentExampleCommunicationTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface
     */
    protected MockObject $salesOrderAmendmentFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface
     */
    protected MockObject $salesFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface
     */
    protected MockObject $checkoutFacadeMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->salesOrderAmendmentFacadeMock = $this->getMockBuilder(OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface::class)->getMock();
        $this->salesFacadeMock = $this->getMockBuilder(OrderAmendmentExampleToSalesFacadeInterface::class)->getMock();
        $this->checkoutFacadeMock = $this->getMockBuilder(OrderAmendmentExampleToCheckoutFacadeInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testRunShouldReturnEmptyArrayWhenAmendmentQuoteNotFound(): void
    {
        // Arrange
        $plugin = $this->createPlugin();

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn(new SalesOrderAmendmentQuoteCollectionTransfer());

        $this->checkoutFacadeMock->expects($this->never())
            ->method('placeOrder');

        // Act
        $result = $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());

        // Assert
        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testRunShouldExecuteCheckoutWithPreparedQuoteWhenAmendmentQuoteFound(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer();
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        $this->checkoutFacadeMock->expects($this->once())
            ->method('placeOrder')
            ->with($this->callback(function (QuoteTransfer $quoteTransfer) {
                $quoteProcessFlowTransfer = $quoteTransfer->getQuoteProcessFlow();

                return $quoteProcessFlowTransfer->getName() === SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT
                    && $quoteTransfer->getShouldSkipStateMachineRun() === true
                    && $quoteTransfer->getDefaultOmsOrderItemState() === static::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE;
            }))
            ->willReturn((new CheckoutResponseTransfer())->setIsSuccess(true));

        $this->salesFacadeMock->expects($this->once())
            ->method('getOrderItems')
            ->willReturn(new ItemCollectionTransfer());

        // Act
        $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunShouldHandleExceptionDuringCheckout(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer();
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        $this->checkoutFacadeMock->expects($this->once())
            ->method('placeOrder')
            ->willThrowException(new Exception());

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('updateSalesOrderAmendmentQuoteCollection')
            ->with($this->callback(function (SalesOrderAmendmentQuoteCollectionRequestTransfer $requestTransfer) {
                $errors = $requestTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->getQuote()->getErrors();

                return $errors->count() === 1 && $errors->offsetGet(0)->getMessage() === static::GLOSSARY_KEY_ERROR_APPLY_ORDER_AMENDMENT_DRAFT_FAILED;
            }));

        $this->salesFacadeMock->expects($this->once())
            ->method('getOrderItems')
            ->willReturn(new ItemCollectionTransfer());

        // Act
        $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunShouldHandleUnsuccessfulCheckoutWithError(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer();
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())->setMessage(static::ERROR_MESSAGE);
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(false)->addError($checkoutErrorTransfer);

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        $this->checkoutFacadeMock->expects($this->once())
            ->method('placeOrder')
            ->willReturn($checkoutResponseTransfer);

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('updateSalesOrderAmendmentQuoteCollection')
            ->with($this->callback(function (SalesOrderAmendmentQuoteCollectionRequestTransfer $requestTransfer) {
                $errors = $requestTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->getQuote()->getErrors();

                return $errors->count() === 1 && $errors->offsetGet(0)->getMessage() === static::ERROR_MESSAGE;
            }));

        $this->salesFacadeMock->expects($this->once())
            ->method('getOrderItems')
            ->willReturn(new ItemCollectionTransfer());

        // Act
        $plugin->run([], $this->createSalesOrderMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunShouldReturnSameOrderItemsOnSuccessfulCheckoutWhenNoItemsRemoved(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer();
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);
        $orderItems = [
            $this->createSalesOrderItemMock(1),
            $this->createSalesOrderItemMock(2),
        ];
        $itemCollectionTransfer = (new ItemCollectionTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem(1))
            ->addItem((new ItemTransfer())->setIdSalesOrderItem(2));

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        $this->checkoutFacadeMock->expects($this->once())
            ->method('placeOrder')
            ->willReturn((new CheckoutResponseTransfer())->setIsSuccess(true));

        $this->salesFacadeMock->expects($this->once())
            ->method('getOrderItems')
            ->willReturn($itemCollectionTransfer);

        // Act
        $result = $plugin->run($orderItems, $this->createSalesOrderMock(), new ReadOnlyArrayObject());

        // Assert
        $this->assertArrayHasKey(static::RETURN_DATA_UPDATED_ORDER_ITEMS, $result);
        $this->assertCount(2, $result[static::RETURN_DATA_UPDATED_ORDER_ITEMS]);
    }

    /**
     * @return void
     */
    public function testRunShouldReturnFilteredOrderItemsOnSuccessfulCheckoutWhenItemRemoved(): void
    {
        // Arrange
        $plugin = $this->createPlugin();
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer();
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);
        $orderItems = [
            $this->createSalesOrderItemMock(1),
            $this->createSalesOrderItemMock(2),
        ];
        $itemCollectionTransfer = (new ItemCollectionTransfer())->addItem((new ItemTransfer())->setIdSalesOrderItem(1));

        $this->salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        $this->checkoutFacadeMock->expects($this->once())
            ->method('placeOrder')
            ->willReturn((new CheckoutResponseTransfer())->setIsSuccess(true));

        $this->salesFacadeMock->expects($this->once())
            ->method('getOrderItems')
            ->willReturn($itemCollectionTransfer);

        // Act
        $result = $plugin->run($orderItems, $this->createSalesOrderMock(), new ReadOnlyArrayObject());

        // Assert
        $this->assertArrayHasKey(static::RETURN_DATA_UPDATED_ORDER_ITEMS, $result);
        $this->assertCount(1, $result[static::RETURN_DATA_UPDATED_ORDER_ITEMS]);
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Communication\Plugin\Oms\ApplyOrderAmendmentDraftCommandByOrderPlugin
     */
    protected function createPlugin(): ApplyOrderAmendmentDraftCommandByOrderPlugin
    {
        $plugin = new ApplyOrderAmendmentDraftCommandByOrderPlugin();
        $plugin->setBusinessFactory($this->createBusinessFactory());

        return $plugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrderMock(): SpySalesOrder
    {
        $orderEntityMock = $this->createMock(SpySalesOrder::class);
        $orderEntityMock->method('getOrderReference')->willReturn(static::ORDER_REFERENCE);
        $orderEntityMock->method('getIdSalesOrder')->willReturn(static::ID_SALES_ORDER);

        return $orderEntityMock;
    }

    /**
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    protected function createSalesOrderAmendmentQuoteTransfer(): SalesOrderAmendmentQuoteTransfer
    {
        return (new SalesOrderAmendmentQuoteTransfer())
            ->setQuote((new QuoteTransfer())->setQuoteProcessFlow(new QuoteProcessFlowTransfer()));
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemMock(int $idSalesOrderItem): SpySalesOrderItem
    {
        $salesOrderItemMock = $this->createMock(SpySalesOrderItem::class);
        $salesOrderItemMock->method('getIdSalesOrderItem')->willReturn($idSalesOrderItem);

        return $salesOrderItemMock;
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Business\OrderAmendmentExampleBusinessFactory
     */
    protected function createBusinessFactory(): OrderAmendmentExampleBusinessFactory
    {
        $businessFactory = new OrderAmendmentExampleBusinessFactory();
        $container = new Container();
        $container->set(OrderAmendmentExampleDependencyProvider::FACADE_SALES_ORDER_AMENDMENT, $this->salesOrderAmendmentFacadeMock);
        $container->set(OrderAmendmentExampleDependencyProvider::FACADE_SALES, $this->salesFacadeMock);
        $container->set(OrderAmendmentExampleDependencyProvider::FACADE_CHECKOUT, $this->checkoutFacadeMock);
        $businessFactory->setContainer($container);

        return $businessFactory;
    }
}
