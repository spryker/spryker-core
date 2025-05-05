<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\QuoteRequest;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\QuoteRequest\OrderAmendmentQuoteRequestUserValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group QuoteRequest
 * @group OrderAmendmentQuoteRequestUserValidatorPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentQuoteRequestUserValidatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\QuoteRequestValidator::GLOSSARY_KEY_QUOTE_REQUEST_IN_ORDER_AMENDMENT_IS_FORBIDDEN
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_IN_ORDER_AMENDMENT_IS_FORBIDDEN = 'sales_order_amendment.quote_request.validation.error.forbidden';

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenCartIsInAmendmentProcessByQuoteProcessFlow(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName('order-amendment'));

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($quoteTransfer);
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setLatestVersion($quoteRequestVersionTransfer);

        // Arrange
        $quoteRequestResponseTransfer = (new OrderAmendmentQuoteRequestUserValidatorPlugin())
            ->validate($quoteRequestTransfer);

        // Assert
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_QUOTE_REQUEST_IN_ORDER_AMENDMENT_IS_FORBIDDEN,
            $quoteRequestResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenCartIsInAmendmentProcessByAmendmentOrderReference(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference('fake-order-reference');

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($quoteTransfer);
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setLatestVersion($quoteRequestVersionTransfer);

        // Arrange
        $quoteRequestResponseTransfer = (new OrderAmendmentQuoteRequestUserValidatorPlugin())
            ->validate($quoteRequestTransfer);

        // Assert
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_QUOTE_REQUEST_IN_ORDER_AMENDMENT_IS_FORBIDDEN,
            $quoteRequestResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotReturnErrorWhenCartIsNotInAmendmentProcess(): void
    {
        // Arrange
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote(new QuoteTransfer());
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setLatestVersion($quoteRequestVersionTransfer);

        // Arrange
        $quoteRequestResponseTransfer = (new OrderAmendmentQuoteRequestUserValidatorPlugin())
            ->validate($quoteRequestTransfer);

        // Assert
        $this->assertEmpty($quoteRequestResponseTransfer->getMessages());
    }
}
