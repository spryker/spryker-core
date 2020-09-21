<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleCartsRestApi\Business;

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
 * @group ProductBundleCartsRestApi
 * @group Business
 * @group Facade
 * @group ProductBundleCartsRestApiFacadeTest
 *
 * Add your own group annotations below this line
 */
class ProductBundleCartsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundleCartsRestApi\ProductBundleCartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider validateBundleItemDataProvider
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $expectedResult
     * @param string $message
     *
     * @return void
     */
    public function testValidateBundleItemWillFindBundleItem(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteTransfer $quoteTransfer,
        bool $expectedResult,
        string $message
    ): void {
        // Act
        $isBundleItemInQuote = $this->tester->getFacade()->validateBundleItem($cartItemRequestTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($expectedResult, $isBundleItemInQuote, $message);
    }

    /**
     * @return array[]
     */
    public function validateBundleItemDataProvider(): array
    {
        return [
            [
                (new CartItemRequestBuilder([CartItemRequestTransfer::GROUP_KEY => 'groupKey']))->build(),
                (new QuoteBuilder())->withBundleItem([ItemTransfer::GROUP_KEY => 'groupKey'])->build(),
                true,
                'Item must be findable by groupKey',
            ],
            [
                (new CartItemRequestBuilder([CartItemRequestTransfer::GROUP_KEY => 'groupKey']))->build(),
                (new QuoteBuilder())->withBundleItem([ItemTransfer::GROUP_KEY => 'someOtherGroupKey'])->build(),
                false,
                'Missing item must not be findable',
            ],
        ];
    }
}
