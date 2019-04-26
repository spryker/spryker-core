<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
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
    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected $tester;

    /**
     * @group skipped
     *
     * @return void
     */
    public function testOrderHydratedWithDiscount(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->create();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $orderItemEntity = $salesOrderEntity->getItems()[0];
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $this->createSalesDiscountEntity($salesOrderEntity->getIdSalesOrder(), $orderItemEntity->getIdSalesOrderItem(), 50);
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
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     * @param int $amount
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount
     */
    protected function createSalesDiscountEntity(int $idSalesOrder, int $idSalesOrderItem, int $amount): SpySalesDiscount
    {
        $orderItemDiscountEntity = new SpySalesDiscount();
        $orderItemDiscountEntity->setAmount($amount);
        $orderItemDiscountEntity->setFkSalesOrder($idSalesOrder);
        $orderItemDiscountEntity->setFkSalesOrderItem($idSalesOrderItem);
        $orderItemDiscountEntity->setName('Discount order saver tester');
        $orderItemDiscountEntity->setDisplayName('discount');
        $orderItemDiscountEntity->setDescription('Description');
        $orderItemDiscountEntity->save();

        return $orderItemDiscountEntity;
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
