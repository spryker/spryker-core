<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeExpandItemsTest
 * Add your own group annotations below this line
 */
class SalesFacadeExpandItemsTest extends Test
{
    protected const ITEM_NAME = 'ITEM_NAME';
    protected const CURRENCY_ISO_CODE = 'CODE';
    protected const CUSTOMER_REFERENCE = 'CUSTOMER_REFERENCE';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithOrderWithCurrencyCode(): void
    {
        // Arrange
        $salesFacade = $this->getSalesFacade();
        $quoteTransfer = $this->getFakeQuote(static::CURRENCY_ISO_CODE);
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $saveOrderTransfer->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $salesFacade->expandItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertEquals($orderTransfer->getCurrencyIsoCode(), $itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithOrderWithoutCurrencyCode(): void
    {
        // Arrange
        $salesFacade = $this->getSalesFacade();
        $quoteTransfer = $this->getFakeQuote(false);
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $saveOrderTransfer->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $salesFacade->expandItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertEmpty($itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithoutOrder(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build();

        // Act
        $itemTransfers = $this->getSalesFacade()->expandItemsWithCurrencyIsoCode([$itemTransfer]);

        // Assert
        $this->assertNull($itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->tester->getLocator()->sales()->facade();
    }

    /**
     * @param string|null $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getFakeQuote(?string $currencyIsoCode = null): QuoteTransfer
    {
        $quoteBuilder = (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE])
            ->withCurrency([CurrencyTransfer::CODE => $currencyIsoCode]);

        return $quoteBuilder->build();
    }
}
