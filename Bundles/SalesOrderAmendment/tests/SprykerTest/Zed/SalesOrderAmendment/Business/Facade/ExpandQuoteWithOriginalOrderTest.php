<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group ExpandQuoteWithOriginalOrderTest
 * Add your own group annotations below this line
 */
class ExpandQuoteWithOriginalOrderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([SalesOrderAmendmentBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldExpandQuoteTransferWithOriginalOrder(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->haveOrderFromQuote(
            $this->tester->createQuoteTransfer($customerTransfer),
            SalesOrderAmendmentBusinessTester::DEFAULT_OMS_PROCESS_NAME,
        );
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail(),
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => $orderTransfer->getOrderReferenceOrFail(),
        ]))->build();

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithOriginalOrder($quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getOriginalOrder());
        $this->assertSame($orderTransfer->getIdSalesOrderOrFail(), $quoteTransfer->getOriginalOrderOrFail()->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenOrderIsNotFound(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail(),
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'fake-order-reference',
        ]))->build();

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithOriginalOrder($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenAmendmentOrderReferenceIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => 'customer-reference']))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "amendmentOrderReference" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        $this->tester->getFacade()->expandQuoteWithOriginalOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenCustomerReferenceIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference']))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customerReference" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        $this->tester->getFacade()->expandQuoteWithOriginalOrder($quoteTransfer);
    }
}
