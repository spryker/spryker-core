<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartNote\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartReorderBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\CartNote\CartNoteBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartNote
 * @group Business
 * @group Facade
 * @group ExpandCartReorderQuoteWithCartNoteTest
 * Add your own group annotations below this line
 */
class ExpandCartReorderQuoteWithCartNoteTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CART_NOTE = 'test cart note';

    /**
     * @var \SprykerTest\Zed\CartNote\CartNoteBusinessTester
     */
    protected CartNoteBusinessTester $tester;

    /**
     * @return void
     */
    public function testSetsQuoteCartNoteWhenOrderCartNoteIsProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote()
            ->withOrder([OrderTransfer::CART_NOTE => static::TEST_CART_NOTE])
            ->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->expandCartReorderQuoteWithCartNote($cartReorderTransfer);

        // Assert
        $this->assertSame(
            static::TEST_CART_NOTE,
            $cartReorderTransfer->getQuoteOrFail()->getCartNote(),
        );
    }

    /**
     * @return void
     */
    public function testSetsNullWhenOrderCartNoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote([QuoteTransfer::CART_NOTE => static::TEST_CART_NOTE])
            ->withOrder([OrderTransfer::CART_NOTE => null])
            ->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()->expandCartReorderQuoteWithCartNote($cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getCartNote());
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredCartReorderPropertiesAreNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredCartReorderPropertiesAreNotSet(
        CartReorderTransfer $cartReorderTransfer,
        string $exceptionMessage
    ): void {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $this->tester->getFacade()->expandCartReorderQuoteWithCartNote($cartReorderTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CartReorderTransfer|string>>
     */
    protected function throwsNullValueExceptionWhenRequiredCartReorderPropertiesAreNotSetDataProvider(): array
    {
        return [
            'order is not provided' => [
                (new CartReorderBuilder([CartReorderTransfer::ORDER => null]))->withQuote()->build(),
                sprintf('Property "order" of transfer `%s` is null.', CartReorderTransfer::class),
            ],
            'quote is not provided' => [
                (new CartReorderBuilder([CartReorderTransfer::QUOTE => null]))
                    ->withOrder([OrderTransfer::CART_NOTE => static::TEST_CART_NOTE])
                    ->build(),
                sprintf('Property "quote" of transfer `%s` is null.', CartReorderTransfer::class),
            ],
        ];
    }
}
