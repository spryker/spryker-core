<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApi
 * @group Business
 * @group Facade
 * @group IsItemInQuoteCartsRestApiFacadeTest
 *
 * Add your own group annotations below this line
 */
class IsItemInQuoteCartsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CartsRestApi\CartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider isItemInQuoteDataProvider
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $expectedResult
     * @param string $message
     *
     * @return void
     */
    public function testIsItemInQuote(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteTransfer $quoteTransfer,
        bool $expectedResult,
        string $message
    ): void {
        // Act
        $isItemInQuote = $this->tester->getFacade()->isItemInQuote($cartItemRequestTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($expectedResult, $isItemInQuote, $message);
    }

    /**
     * @return array
     */
    public function isItemInQuoteDataProvider(): array
    {
        return [
            [
                (new CartItemRequestBuilder([CartItemRequestTransfer::GROUP_KEY => 'groupKey']))->build(),
                (new QuoteBuilder())->withItem([ItemTransfer::GROUP_KEY => 'groupKey'])->build(),
                true,
                'Item must be findable by groupKey',
            ],
            [
                (new CartItemRequestBuilder([CartItemRequestTransfer::SKU => 'sku']))->build(),
                (new QuoteBuilder())->withItem([ItemTransfer::SKU => 'sku'])->build(),
                true,
                'Item must be findable by sku',
            ],
            [
                (new CartItemRequestBuilder([CartItemRequestTransfer::GROUP_KEY => 'groupKey']))->build(),
                (new QuoteBuilder())->withItem([ItemTransfer::GROUP_KEY => 'Some other group key'])->build(),
                false,
                'Missing item must not be findable',
            ],
        ];
    }
}
