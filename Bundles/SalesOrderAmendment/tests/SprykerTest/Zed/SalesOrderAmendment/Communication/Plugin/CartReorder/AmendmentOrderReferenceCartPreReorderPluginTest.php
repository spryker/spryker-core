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
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\AmendmentOrderReferenceCartPreReorderPlugin;
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
 * @group AmendmentOrderReferenceCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class AmendmentOrderReferenceCartPreReorderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'DE--123';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldSetAmendmentOrderReferenceToQuote(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new AmendmentOrderReferenceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame(static::FAKE_ORDER_REFERENCE, $cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldNotSetAmendmentOrderReferenceToQuoteWhenIsAmendmentFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(false);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new AmendmentOrderReferenceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldNotSetAmendmentOrderReferenceToQuoteWhenIsAmendmentNull(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new AmendmentOrderReferenceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testShouldNotOverrideAmendmentOrderReferenceInQuote(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference('new-order-reference');

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote((new QuoteTransfer())->setAmendmentOrderReference(static::FAKE_ORDER_REFERENCE));

        // Act
        $cartReorderTransfer = (new AmendmentOrderReferenceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame(
            static::FAKE_ORDER_REFERENCE,
            $cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference(),
        );
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
        (new AmendmentOrderReferenceCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
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
            ->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "orderReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        (new AmendmentOrderReferenceCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }
}
