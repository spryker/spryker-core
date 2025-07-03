<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Checkout\SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group SalesOrderAmendmentQuoteCheckoutDoSaveOrderPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderAmendmentQuoteCheckoutDoSaveOrderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testSaveOrderShouldCreateSalesOrderAmendmentQuote(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
        $amendmentOrderReference = 'test-amendment-order-reference';
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer((new CustomerTransfer())->setCustomerReference('test-customer-reference'))
            ->setAmendmentOrderReference($amendmentOrderReference);

        // Act
        (new SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin())->saveOrder($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->assertNotNull(
            $this->tester->findSalesOrderAmendmentQuoteByAmendmentOrderReference($amendmentOrderReference),
        );
    }

    /**
     * @return void
     */
    public function testSaveOrderShouldThrowExceptionWhenCustomerIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference('amendment-order-reference');

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customer" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin())->saveOrder($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testSaveOrderShouldThrowExceptionWhenCustomerReferenceIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(new CustomerTransfer())
            ->setAmendmentOrderReference('amendment-order-reference');

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customerReference" of transfer `Generated\Shared\Transfer\CustomerTransfer` is null.');

        // Act
        (new SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin())->saveOrder($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testSaveOrderShouldThrowExceptionWhenAmendmentOrderReferenceIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer((new CustomerTransfer())->setCustomerReference('test-customer'));

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "amendmentOrderReference" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin())->saveOrder($quoteTransfer, new SaveOrderTransfer());
    }
}
