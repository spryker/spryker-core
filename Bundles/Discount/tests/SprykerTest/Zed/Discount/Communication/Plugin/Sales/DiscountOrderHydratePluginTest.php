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
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Spryker\Zed\Discount\Communication\Plugin\Sales\DiscountOrderHydratePlugin;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
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
        $orderTransfer = $this->findOrder($saveOrderTransfer);
        $this->createDiscountForOrder($orderTransfer);

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        $this->assertNotEmpty($orderTransfer->getCalculatedDiscounts());
    }

    /**
     * @return array
     */
    public function orderHydratorItemsDataProvider(): array
    {
        return [
            'single item' => $this->getDataForOrderHydratorSingleItem(),
            'single item higher quantity' => $this->getDataForOrderHydratorSingleItemHigherQuantity(),
            'quote with multiple items' => $this->getDataForOrderHydratorMultipleItem(),
            'quote with multiple items mixed quantity' => $this->getDataForOrderHydratorMultipleItemsMixedQuantity(),
        ];
    }

    /**
     * @return \Generated\Shared\DataBuilder\QuoteBuilder
     */
    protected function getBaseQuoteBuilder(): QuoteBuilder
    {
        return (new QuoteBuilder())
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getMultipleItemsMixedQuantityQuote(): QuoteTransfer
    {
        return $this->getBaseQuoteBuilder()
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withAnotherItem([ItemTransfer::QUANTITY => 2, ItemTransfer::UNIT_PRICE => 1000])
            ->withAnotherItem([ItemTransfer::QUANTITY => 3, ItemTransfer::UNIT_PRICE => 1000])
            ->build();
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorMultipleItemsMixedQuantity(): array
    {
        $quoteTransfer = $this->getMultipleItemsMixedQuantityQuote();
        $itemDiscountQuantities = [
            $quoteTransfer->getItems()[0]->getSku() => 1,
            $quoteTransfer->getItems()[1]->getSku() => 2,
            $quoteTransfer->getItems()[2]->getSku() => 3,
        ];
        $discountAmounts = [
            $quoteTransfer->getItems()[0]->getSku() => [50, 50],
            $quoteTransfer->getItems()[1]->getSku() => [50, 25],
            $quoteTransfer->getItems()[2]->getSku() => [50, 17],
        ];

        return [
            $quoteTransfer,
            $itemDiscountQuantities,
            $discountAmounts,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getSingleItemHigherQuantityQuote(): QuoteTransfer
    {
        return $this->getBaseQuoteBuilder()
            ->withItem([ItemTransfer::QUANTITY => 3, ItemTransfer::UNIT_PRICE => 1000])
            ->build();
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorSingleItemHigherQuantity(): array
    {
        $quoteTransfer = $this->getSingleItemHigherQuantityQuote();
        $itemDiscountQuantities = [
            $quoteTransfer->getItems()[0]->getSku() => 3,
        ];
        $discountAmounts = [
            $quoteTransfer->getItems()[0]->getSku() => [50, 17],
        ];

        return [
            $quoteTransfer,
            $itemDiscountQuantities,
            $discountAmounts,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getSingleItemQuote(): QuoteTransfer
    {
        return $this->getBaseQuoteBuilder()
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->build();
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorSingleItem(): array
    {
        $quoteTransfer = $this->getSingleItemQuote();
        $itemDiscountQuantities = [
            $quoteTransfer->getItems()[0]->getSku() => 1,
        ];
        $discountAmounts = [
            $quoteTransfer->getItems()[0]->getSku() => [50, 50],
        ];

        return [
            $quoteTransfer,
            $itemDiscountQuantities,
            $discountAmounts,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getMultipleItemsQuote(): QuoteTransfer
    {
        return $this->getBaseQuoteBuilder()
            ->withItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withAnotherItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->withAnotherItem([ItemTransfer::QUANTITY => 1, ItemTransfer::UNIT_PRICE => 1000])
            ->build();
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorMultipleItem(): array
    {
        $quoteTransfer = $this->getMultipleItemsQuote();
        $itemDiscountQuantities = [
            $quoteTransfer->getItems()[0]->getSku() => 1,
            $quoteTransfer->getItems()[1]->getSku() => 1,
            $quoteTransfer->getItems()[2]->getSku() => 1,
        ];
        $discountAmounts = [
            $quoteTransfer->getItems()[0]->getSku() => [50, 50],
            $quoteTransfer->getItems()[1]->getSku() => [50, 50],
            $quoteTransfer->getItems()[2]->getSku() => [50, 50],
        ];

        return [
            $quoteTransfer,
            $itemDiscountQuantities,
            $discountAmounts,
        ];
    }

    /**
     * @dataProvider orderHydratorItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $itemDiscountQuantities
     * @param array $discountAmounts
     *
     * @return void
     */
    public function testOrderHydratedShouldCorrectlyHydrateOrderItems(
        QuoteTransfer $quoteTransfer,
        array $itemDiscountQuantities,
        array $discountAmounts
    ): void {
        //Arrange
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);

        $saveOrderTransfer = $this->tester
            ->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->findOrder($saveOrderTransfer);

        $this->createDiscountForOrder($orderTransfer);

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
                $this->assertEquals($itemDiscountQuantities[$itemTransfer->getSku()], $calculatedDiscountTransfer->getQuantity(), 'Discount quantity does not match expected value');
                $this->assertEquals($discountAmounts[$itemTransfer->getSku()][0], $calculatedDiscountTransfer->getSumAmount(), 'Discount sum amount does not match expected value');
                $this->assertEquals($discountAmounts[$itemTransfer->getSku()][1], $calculatedDiscountTransfer->getUnitAmount(), 'Discount unit amount does not match expected value');
            }
        }
    }

    /**
     * @dataProvider orderHydratorDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $discountQuantity
     * @param int $discountAmount
     *
     * @return void
     */
    public function testOrderHydratedShouldCorrectlyHydrateOrder(
        QuoteTransfer $quoteTransfer,
        int $discountQuantity,
        int $discountAmount
    ): void {
        //Arrange
        $discountOrderHydratePlugin = $this->createDiscountOrderHydratePlugin();
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);

        $saveOrderTransfer = $this->tester
            ->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->findOrder($saveOrderTransfer);

        $this->createDiscountForOrder($orderTransfer);

        //Act
        $orderTransfer = $discountOrderHydratePlugin->hydrate($orderTransfer);

        //Assert
        foreach ($orderTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $this->assertEquals($discountQuantity, $calculatedDiscountTransfer->getQuantity(), 'Discount quantity does not match expected value');
            $this->assertEquals($discountAmount, $calculatedDiscountTransfer->getSumAmount(), 'Discount sum amount does not match expected value');
        }
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorMultipleItemsMixedQuantityOrderLevel(): array
    {
        $quoteTransfer = $this->getMultipleItemsMixedQuantityQuote();
        $discountQuantities = 6;
        $discountAmount = 150;

        return [
            $quoteTransfer,
            $discountQuantities,
            $discountAmount,
        ];
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorMultipleItemOrderLevel(): array
    {
        $quoteTransfer = $this->getMultipleItemsQuote();
        $discountQuantity = 3;
        $discountAmount = 150;

        return [
            $quoteTransfer,
            $discountQuantity,
            $discountAmount,
        ];
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorSingleItemHigherQuantityOrderLevel(): array
    {
        $quoteTransfer = $this->getSingleItemHigherQuantityQuote();
        $discountQuantity = 3;
        $discountAmount = 50;

        return [
            $quoteTransfer,
            $discountQuantity,
            $discountAmount,
        ];
    }

    /**
     * @return array
     */
    protected function getDataForOrderHydratorSingleItemOrderLevel(): array
    {
        $quoteTransfer = $this->getSingleItemQuote();
        $discountQuantity = 1;
        $discountAmount = 50;

        return [
            $quoteTransfer,
            $discountQuantity,
            $discountAmount,
        ];
    }

    /**
     * @return array
     */
    public function orderHydratorDataProvider(): array
    {
        return [
            'single item' => $this->getDataForOrderHydratorSingleItemOrderLevel(),
            'single item higher quantity' => $this->getDataForOrderHydratorSingleItemHigherQuantityOrderLevel(),
            'quote with multiple items' => $this->getDataForOrderHydratorMultipleItemOrderLevel(),
            'quote with multiple items mixed quantity' => $this->getDataForOrderHydratorMultipleItemsMixedQuantityOrderLevel(),
        ];
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
    protected function getSalesFacade(): SalesFacadeInterface
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
    protected function findOrder(SaveOrderTransfer $saveOrderTransfer): OrderTransfer
    {
        $salesFacade = $this->getSalesFacade();

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
        foreach ($orderTransfer->getItems() as $orderItem) {
            $seedData = $this->getSeedDataForSalesDiscount($orderTransfer->getIdSalesOrder(), $orderItem->getIdSalesOrderItem());
            $this->tester->haveSalesDiscount($seedData);
        }
    }
}
