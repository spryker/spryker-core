<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
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
        $currencyTransfer = (new CurrencyBuilder([CurrencyTransfer::CODE => static::CURRENCY_ISO_CODE]))->build();
        $customerTransfer = (new CustomerBuilder([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))->build();
        $quoteTransfer = $this->tester->buildQuote([
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]);
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->getFacade()->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $saveOrderTransfer->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $this->tester->getFacade()->expandOrderItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertEquals($orderTransfer->getCurrencyIsoCode(), $itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithOrderWithoutCurrencyCode(): void
    {
        // Arrange
        $currencyTransfer = (new CurrencyBuilder([CurrencyTransfer::CODE => null]))->build();
        $customerTransfer = (new CustomerBuilder([CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE]))->build();
        $quoteTransfer = $this->tester->buildQuote([
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]);
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->getFacade()->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $saveOrderTransfer->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $this->tester->getFacade()->expandOrderItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

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
        $itemTransfers = $this->tester->getFacade()->expandOrderItemsWithCurrencyIsoCode([$itemTransfer]);

        // Assert
        $this->assertNull($itemTransfers[0]->getCurrencyIsoCode());
    }
}
