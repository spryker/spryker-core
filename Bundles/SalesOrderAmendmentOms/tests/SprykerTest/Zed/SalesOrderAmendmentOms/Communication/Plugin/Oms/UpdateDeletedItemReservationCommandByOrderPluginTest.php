<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\UpdateDeletedItemReservationCommandByOrderPlugin;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsDependencyProvider;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group UpdateDeletedItemReservationCommandByOrderPluginTest
 * Add your own group annotations below this line
 */
class UpdateDeletedItemReservationCommandByOrderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DELETED_ITEM_SKU = 'deleted-item-sku';

    /**
     * @var string
     */
    protected const EXISTING_ITEM_SKU = 'existing-item-sku';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testRunShouldNotCallOmsReservationWhenSalesOrderAmendmentQuoteNotFound(): void
    {
        // Arrange
        $salesOrderAmendmentFacadeMock = $this->createSalesOrderAmendmentFacadeMock(null);
        $omsFacadeMock = $this->createOmsFacadeMock(0);
        $plugin = $this->createPlugin($salesOrderAmendmentFacadeMock, $omsFacadeMock);

        // Act
        $plugin->run([], $this->createOrderEntityMock(), new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunShouldUpdateReservationWhenSalesOrderAmendmentQuoteWithDeletedItemFound(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer(static::DELETED_ITEM_SKU);
        $salesOrderAmendmentFacadeMock = $this->createSalesOrderAmendmentFacadeMock($salesOrderAmendmentQuoteTransfer);
        $omsFacadeMock = $this->createOmsFacadeMock(1, static::DELETED_ITEM_SKU);

        $plugin = $this->createPlugin($salesOrderAmendmentFacadeMock, $omsFacadeMock);
        $orderEntityMock = $this->createOrderEntityMock();

        // Act
        $plugin->run([], $orderEntityMock, new ReadOnlyArrayObject());
    }

    /**
     * @return void
     */
    public function testRunShouldNotCallOmsReservationWhenSalesOrderAmendmentQuoteWithoutDeletedItemFound(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->createSalesOrderAmendmentQuoteTransfer(static::EXISTING_ITEM_SKU);
        $salesOrderAmendmentFacadeMock = $this->createSalesOrderAmendmentFacadeMock($salesOrderAmendmentQuoteTransfer);
        $omsFacadeMock = $this->createOmsFacadeMock(0);

        $plugin = $this->createPlugin($salesOrderAmendmentFacadeMock, $omsFacadeMock);
        $orderEntityMock = $this->createOrderEntityMock();

        // Act
        $plugin->run([], $orderEntityMock, new ReadOnlyArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null $salesOrderAmendmentQuoteTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
     */
    protected function createSalesOrderAmendmentFacadeMock(
        ?SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
    ): SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface {
        $salesOrderAmendmentFacadeMock = $this->createMock(SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface::class);
        $salesOrderAmendmentQuoteCollectionTransfer = new SalesOrderAmendmentQuoteCollectionTransfer();
        if ($salesOrderAmendmentQuoteTransfer) {
            $salesOrderAmendmentQuoteCollectionTransfer->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);
        }
        $salesOrderAmendmentFacadeMock->method('getSalesOrderAmendmentQuoteCollection')
            ->willReturn($salesOrderAmendmentQuoteCollectionTransfer);

        return $salesOrderAmendmentFacadeMock;
    }

    /**
     * @param int $expectedCallCount
     * @param string|null $expectedSku
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface
     */
    protected function createOmsFacadeMock(int $expectedCallCount, ?string $expectedSku = null): SalesOrderAmendmentOmsToOmsFacadeInterface
    {
        $omsFacadeMock = $this->createMock(SalesOrderAmendmentOmsToOmsFacadeInterface::class);
        $expectation = $omsFacadeMock->expects($this->exactly($expectedCallCount))
            ->method('updateReservation');

        if ($expectedCallCount > 0 && $expectedSku) {
            $expectation->with($this->callback(function (ReservationRequestTransfer $reservationRequestTransfer) use ($expectedSku) {
                return $reservationRequestTransfer->getSku() === $expectedSku;
            }));
        }

        return $omsFacadeMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacadeMock
     *
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\UpdateDeletedItemReservationCommandByOrderPlugin
     */
    protected function createPlugin(
        SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock,
        SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacadeMock
    ): UpdateDeletedItemReservationCommandByOrderPlugin {
        $updateDeletedItemReservationCommandByOrderPlugin = new UpdateDeletedItemReservationCommandByOrderPlugin();

        $communicationFactoryMock = $this->createCommunicationFactoryMock($salesOrderAmendmentFacadeMock);
        $updateDeletedItemReservationCommandByOrderPlugin->setFactory($communicationFactoryMock);
        $this->tester->setDependency(SalesOrderAmendmentOmsDependencyProvider::FACADE_OMS, $omsFacadeMock);
        $this->tester->setDependency(SalesOrderAmendmentOmsDependencyProvider::FACADE_SALES_ORDER_AMENDMENT, $salesOrderAmendmentFacadeMock);

        return $updateDeletedItemReservationCommandByOrderPlugin;
    }

    /**
     * @param string $originalSalesOrderItemSku
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    protected function createSalesOrderAmendmentQuoteTransfer(string $originalSalesOrderItemSku): SalesOrderAmendmentQuoteTransfer
    {
        $originalSalesOrderItemTransfer = (new OriginalSalesOrderItemTransfer())
            ->setSku($originalSalesOrderItemSku)
            ->setGroupKey($originalSalesOrderItemSku);

        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem($originalSalesOrderItemTransfer)
            ->addItem((new ItemTransfer())->setSku(static::EXISTING_ITEM_SKU));

        return (new SalesOrderAmendmentQuoteTransfer())->setQuote($quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrderEntityMock(): SpySalesOrder
    {
        $orderEntityMock = $this->createMock(SpySalesOrder::class);
        $orderEntityMock->method('getOrderReference')->willReturn('test-order-reference');

        return $orderEntityMock;
    }

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
     *
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory
     */
    protected function createCommunicationFactoryMock(
        SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacadeMock
    ): SalesOrderAmendmentOmsCommunicationFactory {
        $communicationFactoryMock = $this->getMockBuilder(SalesOrderAmendmentOmsCommunicationFactory::class)
            ->onlyMethods(['getSalesOrderAmendmentFacade'])
            ->getMock();
        $communicationFactoryMock->method('getSalesOrderAmendmentFacade')
            ->willReturn($salesOrderAmendmentFacadeMock);

        return $communicationFactoryMock;
    }
}
