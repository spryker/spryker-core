<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Spryker\Zed\Discount\Communication\Plugin\Sales\DiscountOrderHydratePlugin;
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
    protected const FIELD_NAME_AMOUNT = 'amount';

    protected const DISCOUNT_NAME = 'Discount order saver tester';
    protected const FIELD_NAME_NAME = 'name';
    protected const FIELD_DISPLAY_NAME = 'display_name';

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
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->tester->haveOrder(['unitPrice' => 1000], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->createOrder($saveOrderTransfer);
        $this->createDiscountForOrder($orderTransfer);

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        $this->assertNotEmpty($orderTransfer->getCalculatedDiscounts());
    }

    /**
     * @return void
     */
    public function testOrderHydratedWithMultipleOrderItems(): void
    {
        //Arrange
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $saveOrderTransfer = $this->tester
            ->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->createOrder($saveOrderTransfer);

        $this->createDiscountForOrder($orderTransfer);

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
                $this->assertEquals(1, $calculatedDiscountTransfer->getQuantity());
            }
        }

        foreach ($orderTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $this->assertEquals(3, $calculatedDiscountTransfer->getQuantity());
        }
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return array
     */
    protected function getSeedDataForSalesDiscount(int $idSalesOrder, int $idSalesOrderItem): array
    {
        return [
            $this->getDiscountPhpFieldName(SpySalesDiscountTableMap::COL_FK_SALES_ORDER) => $idSalesOrder,
            $this->getDiscountPhpFieldName(SpySalesDiscountTableMap::COL_FK_SALES_ORDER_ITEM) => $idSalesOrderItem,
            static::FIELD_NAME_AMOUNT => static::DISCOUNT_AMOUNT,
            static::FIELD_NAME_NAME => static::DISCOUNT_NAME,
            static::FIELD_DISPLAY_NAME => static::DISCOUNT_NAME,
        ];
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getDiscountPhpFieldName(string $fieldName): string
    {

        return SpySalesDiscountTableMap::translateFieldName($fieldName, SpySalesDiscountTableMap::TYPE_COLNAME, SpySalesDiscountTableMap::TYPE_FIELDNAME);
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
        /**
         * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
         */
        $salesFacade = $this->tester->getLocator()->sales()->facade();

        return $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrder(SaveOrderTransfer $saveOrderTransfer): OrderTransfer
    {
        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function createDiscountForOrder(OrderTransfer $orderTransfer): void
    {
        $orderTransfer->requireItems();
//        $orderItem = $orderTransfer->getItems()[0];
        foreach ($orderTransfer->getItems() as $orderItem) {
            $seedData = $this->getSeedDataForSalesDiscount($orderTransfer->getIdSalesOrder(), $orderItem->getIdSalesOrderItem());
            $this->tester->haveSalesDiscount($seedData);
        }
    }
}
