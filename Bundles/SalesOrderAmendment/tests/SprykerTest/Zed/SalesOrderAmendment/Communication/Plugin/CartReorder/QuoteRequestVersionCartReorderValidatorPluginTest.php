<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\QuoteRequestVersionCartReorderValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group QuoteRequestVersionCartReorderValidatorPluginTest
 * Add your own group annotations below this line
 */
class QuoteRequestVersionCartReorderValidatorPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_QUOTE_REQUEST_VERSION_REFERENCE = 'fake-quote-request-reference';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\QuoteRequestValidator::GLOSSARY_KEY_ORDER_AMENDMENT_AFTER_QUOTE_REQUEST_IS_FORBIDDEN
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_AMENDMENT_AFTER_QUOTE_REQUEST_IS_FORBIDDEN = 'sales_order_amendment.order_amendment_after_rfq.validation.error.forbidden';

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenPrevOrderWasCreatedFromRequestFromQuote(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $cartReorderTransfer = (new CartReorderTransfer())->setOrder($orderTransfer);

        // Arrange
        $cartReorderResponseTransfer = (new QuoteRequestVersionCartReorderValidatorPlugin())
            ->validate($cartReorderTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_ORDER_AMENDMENT_AFTER_QUOTE_REQUEST_IS_FORBIDDEN,
            $cartReorderResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotReturnErrorWhenPrevOrderWasNotCreatedFromRequestFromQuote(): void
    {
        // Arrange
        $orderTransfer = new OrderTransfer();
        $cartReorderTransfer = (new CartReorderTransfer())->setOrder($orderTransfer);

        // Arrange
        $cartReorderResponseTransfer = (new QuoteRequestVersionCartReorderValidatorPlugin())
            ->validate($cartReorderTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertEmpty($cartReorderResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenOrderIsNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "order" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new QuoteRequestVersionCartReorderValidatorPlugin())->validate(
            new CartReorderTransfer(),
            new CartReorderResponseTransfer(),
        );
    }
}
