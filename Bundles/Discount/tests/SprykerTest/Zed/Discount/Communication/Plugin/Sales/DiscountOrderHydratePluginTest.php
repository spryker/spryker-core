<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Discount\Communication\Plugin\Sales\DiscountOrderHydratePlugin;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group DiscountOrderHydratePluginTest
 * Add your own group annotations below this line
 */
class DiscountOrderHydratePluginTest extends Unit
{
    protected const DISCOUNT_AMOUNT = 50;
    protected const DISCOUNT_NAME = 'Discount order saver tester';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOrderHydratedWithDiscount(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $orderItemEntity = $salesOrderEntity->getItems()[0];
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $seedData = $this->getSeedDataForSalesDiscount($salesOrderEntity, $orderItemEntity, static::DISCOUNT_AMOUNT, static::DISCOUNT_NAME);
        $this->tester->haveSalesDiscount($seedData);
        $salesFacade = $this->createSalesFacade();
        $orderListTransfer = new OrderListTransfer();
        $orderListTransfer = $salesFacade->getCustomerOrders($orderListTransfer, $salesOrderEntity->getFkCustomer());

        $orderTransfer = $orderListTransfer->getOrders()[0];

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        $this->assertNotEmpty($orderTransfer->getCalculatedDiscounts());
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param int $amount
     * @param string $name
     *
     * @return array
     */
    protected function getSeedDataForSalesDiscount(SpySalesOrder $salesOrderEntity, SpySalesOrderItem $salesOrderItemEntity, int $amount, string $name): array
    {
        return [
            SpySalesDiscountTableMap::translateFieldName(SpySalesDiscountTableMap::COL_FK_SALES_ORDER, SpySalesDiscountTableMap::TYPE_COLNAME, SpySalesDiscountTableMap::TYPE_FIELDNAME) => $salesOrderEntity->getIdSalesOrder(),
            SpySalesDiscountTableMap::translateFieldName(SpySalesDiscountTableMap::COL_FK_SALES_ORDER_ITEM, SpySalesDiscountTableMap::TYPE_COLNAME, SpySalesDiscountTableMap::TYPE_FIELDNAME) => $salesOrderItemEntity->getIdSalesOrderItem(),
            SpySalesDiscountTableMap::translateFieldName(SpySalesDiscountTableMap::COL_AMOUNT, SpySalesDiscountTableMap::TYPE_COLNAME, SpySalesDiscountTableMap::TYPE_FIELDNAME) => $amount,
            SpySalesDiscountTableMap::translateFieldName(SpySalesDiscountTableMap::COL_NAME, SpySalesDiscountTableMap::TYPE_COLNAME, SpySalesDiscountTableMap::TYPE_FIELDNAME) => $name,
        ];
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface
     */
    protected function createDiscountOrderHydratePlugin(): HydrateOrderPluginInterface
    {
        return new DiscountOrderHydratePlugin();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade(): SalesFacadeInterface
    {
        return new SalesFacade();
    }
}
