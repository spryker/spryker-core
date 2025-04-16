<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\CartReorder\OriginalSalesOrderItemPriceCartPreReorderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group OriginalSalesOrderItemPriceCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class OriginalSalesOrderItemPriceCartPreReorderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentFlagIsSetToFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(false);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemPriceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertEmpty($cartReorderTransfer->getQuoteOrFail()->getOriginalSalesOrderItemUnitPrices());
    }

    /**
     * @dataProvider shouldThrowNullValueExceptionDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testShouldThrowNullValueException(ItemTransfer $itemTransfer, string $exceptionMessage): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer())->addOrderItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        (new OriginalSalesOrderItemPriceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testAddsOriginalSalesOrderItemUnitPrices(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer())
            ->addOrderItem((new ItemTransfer())->setSku('test-sku')->setUnitPrice(123));

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemPriceCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertSame(
            ['test-sku' => 123],
            $cartReorderTransfer->getQuoteOrFail()->getOriginalSalesOrderItemUnitPrices(),
        );
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer|string>>
     */
    protected function shouldThrowNullValueExceptionDataProvider(): array
    {
        return [
            'SKU is not set' => [
                (new ItemTransfer())->setUnitPrice(1),
                'Property "sku" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
            'unitPrice is not set' => [
                (new ItemTransfer())->setSku('test-sku'),
                'Property "unitPrice" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
        ];
    }
}
