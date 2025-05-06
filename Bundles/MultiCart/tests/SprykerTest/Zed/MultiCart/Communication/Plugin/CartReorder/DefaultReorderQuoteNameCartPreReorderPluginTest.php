<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiCart\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\MultiCart\Communication\Plugin\CartReorder\DefaultReorderQuoteNameCartPreReorderPlugin;
use SprykerTest\Zed\MultiCart\MultiCartCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiCart
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group DefaultReorderQuoteNameCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class DefaultReorderQuoteNameCartPreReorderPluginTest extends Unit
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
     * @var \SprykerTest\Zed\MultiCart\MultiCartCommunicationTester
     */
    protected MultiCartCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldSetDefaultQuoteNameForReorder(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Arrange
        $cartReorderTransfer = (new DefaultReorderQuoteNameCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame('Cart from order DE--123', $cartReorderTransfer->getQuoteOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE);

        $cartReorderTransfer = new CartReorderTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new DefaultReorderQuoteNameCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = new CartReorderRequestTransfer();
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "orderReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        (new DefaultReorderQuoteNameCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }
}
