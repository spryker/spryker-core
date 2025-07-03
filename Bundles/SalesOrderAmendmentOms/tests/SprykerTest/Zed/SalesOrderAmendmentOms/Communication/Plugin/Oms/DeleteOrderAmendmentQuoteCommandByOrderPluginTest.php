<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\DeleteOrderAmendmentQuoteCommandByOrderPlugin;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;
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
 * @group DeleteOrderAmendmentQuoteCommandByOrderPluginTest
 * Add your own group annotations below this line
 */
class DeleteOrderAmendmentQuoteCommandByOrderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'test-order-reference';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testRunShouldCallDeleteSalesOrderAmendmentQuoteCollection(): void
    {
        // Arrange
        $deleteOrderAmendmentQuoteCommandByOrderPlugin = new DeleteOrderAmendmentQuoteCommandByOrderPlugin();
        $deleteOrderAmendmentQuoteCommandByOrderPlugin->setFactory($this->createFactoryMock());

        $orderEntityMock = $this->createOrderEntityMock();

        // Act
        $deleteOrderAmendmentQuoteCommandByOrderPlugin->run([], $orderEntityMock, new ReadOnlyArrayObject());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory
     */
    protected function createFactoryMock(): SalesOrderAmendmentOmsCommunicationFactory
    {
        $factoryMock = $this->getMockBuilder(SalesOrderAmendmentOmsCommunicationFactory::class)
            ->onlyMethods(['getSalesOrderAmendmentFacade'])
            ->getMock();
        $factoryMock->method('getSalesOrderAmendmentFacade')
            ->willReturn($this->createSalesOrderAmendmentFacadeMock());

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
     */
    protected function createSalesOrderAmendmentFacadeMock(): SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
    {
        $salesOrderAmendmentFacadeMock = $this->createMock(SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface::class);
        $salesOrderAmendmentFacadeMock->expects($this->once())
            ->method('deleteSalesOrderAmendmentQuoteCollection')
            ->with($this->callback(function (SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer) {
                return count($salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer->getAmendmentOrderReferences()) === 1
                    && $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer->getAmendmentOrderReferences()[0] === static::ORDER_REFERENCE;
            }));

        return $salesOrderAmendmentFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrderEntityMock(): SpySalesOrder
    {
        $orderEntityMock = $this->createMock(SpySalesOrder::class);
        $orderEntityMock->method('getOrderReference')->willReturn(static::ORDER_REFERENCE);

        return $orderEntityMock;
    }
}
