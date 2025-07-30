<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Communication\Table;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\OrderTableCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilder;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeBridge;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Communication
 * @group Table
 * @group OrdersTableQueryTest
 * Add your own group annotations below this line
 */
class OrdersTableQueryTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\Sales\SalesCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerTwigServiceMock();
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testApplyCriteriaShouldFilterOrdersByStores(): void
    {
        // Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => 'AT']);
        $quoteTransfer = $this->tester->buildFakeQuote(
            $customerTransfer ?? $this->tester->haveCustomer(),
            $storeTransferDE,
        );
        $saveOrderTransfer1 = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        $quoteTransfe2 = $this->tester->buildFakeQuote(
            $customerTransfer ?? $this->tester->haveCustomer(),
            $storeTransferAT,
        );
        $saveOrderTransfer2 = $this->tester->haveOrderFromQuote($quoteTransfe2, static::DEFAULT_OMS_PROCESS_NAME);

        $ordersTableMock = $this->createOrdersTableMock();
        $orderTableCriteriaTransfer = (new OrderTableCriteriaTransfer())
            ->setStores([$storeTransferDE->getName()]);

        // Act
        $ordersTableMock->applyCriteria($orderTableCriteriaTransfer);
        $resultData = $ordersTableMock->fetchData();

        // Assert
        $resultOrderIds = array_column($resultData, SpySalesOrderTableMap::COL_ID_SALES_ORDER);
        $this->assertNotEmpty($resultData);
        $this->assertContains((string)$saveOrderTransfer1->getIdSalesOrder(), $resultOrderIds);
        $this->assertNotContains((string)$saveOrderTransfer2->getIdSalesOrder(), $resultOrderIds);
    }

    /**
     * @return void
     */
    public function testApplyCriteriaShouldFilterOrdersByDates(): void
    {
        // Arrange
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $createdAt = (new DateTime('-10 days'))->format('Y-m-d H:i:s');
        $this->tester->getSalesOrderPropelQuery()
            ->filterByIdSalesOrder($saveOrderTransfer2->getIdSalesOrder())
            ->update([
                'CreatedAt' => $createdAt,
            ]);

        $ordersTableMock = $this->createOrdersTableMock();
        $orderTableCriteriaTransfer = (new OrderTableCriteriaTransfer())
            ->setOrderDateFrom((new DateTime())->modify('-1 day')->format('Y-m-d H:i:s'))
            ->setOrderDateTo((new DateTime())->modify('+1 day')->format('Y-m-d H:i:s'));

        // Act
        $ordersTableMock->applyCriteria($orderTableCriteriaTransfer);
        $resultData = $ordersTableMock->fetchData();

        // Assert
        $resultOrderIds = array_column($resultData, SpySalesOrderTableMap::COL_ID_SALES_ORDER);
        $this->assertNotEmpty($resultData);
        $this->assertContains((string)$saveOrderTransfer1->getIdSalesOrder(), $resultOrderIds);
        $this->assertNotContains((string)$saveOrderTransfer2->getIdSalesOrder(), $resultOrderIds);
    }

    /**
     * @return void
     */
    public function testApplyCriteriaShouldFilterOrdersByStatus(): void
    {
        // Arrange
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $newOmsStateEntity = $this->tester->createOmsState('test_state_1');
        $this->tester->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($saveOrderTransfer2->getOrderItems()[0]->getIdSalesOrderItem())
            ->update([
                'FkOmsOrderItemState' => $newOmsStateEntity->getIdOmsOrderItemState(),
            ]);

        $ordersTableMock = $this->createOrdersTableMock();
        $orderTableCriteriaTransfer = (new OrderTableCriteriaTransfer())
            ->setStatuses(['test_state_1']);

        // Act
        $ordersTableMock->applyCriteria($orderTableCriteriaTransfer);
        $resultData = $ordersTableMock->fetchData();

        // Assert
        $resultOrderIds = array_column($resultData, SpySalesOrderTableMap::COL_ID_SALES_ORDER);
        $this->assertNotEmpty($resultData);
        $this->assertContains((string)$saveOrderTransfer2->getIdSalesOrder(), $resultOrderIds);
        $this->assertNotContains((string)$saveOrderTransfer1->getIdSalesOrder(), $resultOrderIds);
    }

    /**
     * @return \SprykerTest\Zed\Sales\Communication\Table\OrdersTableMock
     */
    protected function createOrdersTableMock(): OrdersTableMock
    {
        $salesOrderQuery = SpySalesOrderQuery::create();
        $ordersTableQueryBuilder = new OrdersTableQueryBuilder($salesOrderQuery);

        $moneyFacade = new SalesToMoneyBridge(
            $this->tester->getLocator()->money()->facade(),
        );
        $utilSanitizeFacade = new SalesToUtilSanitizeBridge(
            $this->tester->getLocator()->utilSanitize()->service(),
        );
        $customerFacade = new SalesToCustomerBridge(
            $this->tester->getLocator()->customer()->facade(),
        );

        return new OrdersTableMock(
            $ordersTableQueryBuilder,
            $moneyFacade,
            $utilSanitizeFacade,
            $this->tester->getLocator()->utilDateTime()->service(),
            $customerFacade,
            [],
        );
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function getTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->method('render')
            ->willReturn('Fully rendered template');
        $twigMock->method('getLoader')->willReturn($this->getChainLoader());

        return $twigMock;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }
}
