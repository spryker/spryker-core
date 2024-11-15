<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderCustomReference\Business\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartReorderBuilder;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\OrderCustomReference\OrderCustomReferenceBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OrderCustomReference
 * @group Business
 * @group Business
 * @group Facade
 * @group ExpandCartReorderQuoteWithOrderCustomReferenceTest
 * Add your own group annotations below this line
 */
class ExpandCartReorderQuoteWithOrderCustomReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ORDER_CUSTOM_REFERENCE = 'test-order-custom-reference';

    /**
     * @var \SprykerTest\Zed\OrderCustomReference\OrderCustomReferenceBusinessTester
     */
    protected OrderCustomReferenceBusinessTester $tester;

    /**
     * @return void
     */
    public function testSetsQuoteCartNoteWhenOrderCartNoteIsProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote()
            ->withOrder([OrderTransfer::ORDER_CUSTOM_REFERENCE => static::TEST_ORDER_CUSTOM_REFERENCE])
            ->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()
            ->expandCartReorderQuoteWithOrderCustomReference($cartReorderTransfer);

        // Assert
        $this->assertSame(
            static::TEST_ORDER_CUSTOM_REFERENCE,
            $cartReorderTransfer->getQuoteOrFail()->getOrderCustomReference(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenOrderCartNoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote()
            ->withOrder([OrderTransfer::ORDER_CUSTOM_REFERENCE => null])
            ->build();

        // Act
        $cartReorderTransfer = $this->tester->getFacade()
            ->expandCartReorderQuoteWithOrderCustomReference($cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getOrderCustomReference());
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
        $this->tester->getFacade()->expandCartReorderQuoteWithOrderCustomReference($cartReorderTransfer);
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
                    ->withOrder([OrderTransfer::ORDER_CUSTOM_REFERENCE => static::TEST_ORDER_CUSTOM_REFERENCE])
                    ->build(),
                sprintf('Property "quote" of transfer `%s` is null.', CartReorderTransfer::class),
            ],
        ];
    }
}
