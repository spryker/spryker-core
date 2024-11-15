<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\AmendmentQuoteNameCartPreReorderPlugin;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group AmendmentQuoteNameCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class AmendmentQuoteNameCartPreReorderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'DE--123';

    /**
     * @var string
     */
    protected const FAKE_QUOTE_NAME = 'FAKE_QUOTE_NAME';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldUpdateQuoteName(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote((new QuoteTransfer())->setName(static::FAKE_QUOTE_NAME));

        // Arrange
        $cartReorderTransfer = (new AmendmentQuoteNameCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame('Editing Order ' . static::FAKE_ORDER_REFERENCE, $cartReorderTransfer->getQuoteOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testShouldNotUpdateQuoteNameWhenIsAmendmentFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(false);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote((new QuoteTransfer())->setName(static::FAKE_QUOTE_NAME));

        // Arrange
        $cartReorderTransfer = (new AmendmentQuoteNameCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame(static::FAKE_QUOTE_NAME, $cartReorderTransfer->getQuoteOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testShouldNotUpdateQuoteWhenIsAmendmentNull(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote((new QuoteTransfer())->setName(static::FAKE_QUOTE_NAME));

        // Arrange
        $cartReorderTransfer = (new AmendmentQuoteNameCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame(static::FAKE_QUOTE_NAME, $cartReorderTransfer->getQuoteOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = new CartReorderTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new AmendmentQuoteNameCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote((new QuoteTransfer())->setName(static::FAKE_QUOTE_NAME));

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "orderReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        (new AmendmentQuoteNameCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }
}
