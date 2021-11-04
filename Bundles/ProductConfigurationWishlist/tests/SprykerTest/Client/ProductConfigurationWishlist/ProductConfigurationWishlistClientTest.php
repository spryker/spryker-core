<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationWishlist;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationWishlist
 * @group ProductConfigurationWishlistClientTest
 * Add your own group annotations below this line
 */
class ProductConfigurationWishlistClientTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU = 'FAKE_SKU';

    /**
     * @var string
     */
    protected const FAKE_DISPLAY_DATA_1 = 'FAKE_DISPLAY_DATA_1';

    /**
     * @var string
     */
    protected const FAKE_DISPLAY_DATA_2 = 'FAKE_DISPLAY_DATA_2';

    /**
     * @var \SprykerTest\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandWishlistMoveToCartRequestCollectionWillReturnEmptyDiffForQuoteItemsWithoutProductConfigurationInstances(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::ITEMS => [
                [ItemTransfer::SKU => static::FAKE_SKU],
            ],
        ]);

        $wishlistMoveToCartRequestCollectionTransfer = $this->tester
            ->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance($customerTransfer);

        // Act
        $resultWishlistMoveToCartRequestCollectionDiffTransferList = $this->tester
            ->getClient()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                (new WishlistMoveToCartRequestCollectionTransfer()),
            );

        // Assert
        $this->assertSame(0, $resultWishlistMoveToCartRequestCollectionDiffTransferList->getRequests()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistMoveToCartRequestCollectionWillReturnEmptyDiffForWishlistItemsWithoutProductConfigurationInstances(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => static::FAKE_SKU,
                    ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $this->tester->createProductConfigurationInstance(),
                ],
            ],
        ]);

        $wishlistMoveToCartRequestCollectionTransfer = $this->tester
            ->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance($customerTransfer);

        // Act
        $resultWishlistMoveToCartRequestCollectionDiffTransferList = $this->tester
            ->getClient()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                (new WishlistMoveToCartRequestCollectionTransfer()),
            );

        // Assert
        $this->assertSame(0, $resultWishlistMoveToCartRequestCollectionDiffTransferList->getRequests()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistMoveToCartRequestCollectionWillReturnEmptyDiffForItemsWithTheSameConfigurationInstances(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $productConfigurationInstanceTransfer = $this->tester->createProductConfigurationInstance();

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => static::FAKE_SKU,
                    ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
                ],
            ],
        ]);

        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $customerTransfer,
            $productConfigurationInstanceTransfer,
        );

        // Act
        $resultWishlistMoveToCartRequestCollectionDiffTransferList = $this->tester
            ->getClient()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                (new WishlistMoveToCartRequestCollectionTransfer()),
            );

        // Assert
        $this->assertSame(0, $resultWishlistMoveToCartRequestCollectionDiffTransferList->getRequests()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistMoveToCartRequestCollectionWillReturnDiffForItemsWithDifferentConfigurationInstances(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => static::FAKE_SKU,
                    ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $this->tester->createProductConfigurationInstance([
                        ProductConfigurationInstanceTransfer::DISPLAY_DATA => static::FAKE_DISPLAY_DATA_1,
                    ]),
                ],
            ],
        ]);

        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $customerTransfer,
            $this->tester->createProductConfigurationInstance([
                ProductConfigurationInstanceTransfer::DISPLAY_DATA => static::FAKE_DISPLAY_DATA_2,
            ]),
        );

        // Act
        $resultWishlistMoveToCartRequestCollectionDiffTransferList = $this->tester
            ->getClient()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                (new WishlistMoveToCartRequestCollectionTransfer()),
            );

        // Assert
        $this->assertSame(1, $resultWishlistMoveToCartRequestCollectionDiffTransferList->getRequests()->count());

        $resultWishlistItemProductConfigurationInstanceConfigurationKey = $resultWishlistMoveToCartRequestCollectionDiffTransferList
            ->getRequests()[0]
            ->getWishlistItem()
            ->getProductConfigurationInstance()
            ->getDisplayData();
        $this->assertSame(static::FAKE_DISPLAY_DATA_2, $resultWishlistItemProductConfigurationInstanceConfigurationKey);
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWillExpandCollectionWhenFailedCollectionDoesNotContainIsProductConfigurationInstance(): void
    {
        // Arrange
        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $this->tester->createProductConfigurationInstance(),
        );

        $failedWishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
        );

        // Act
        $wishlistItemCollectionTransfer = $this->tester->getClient()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                (new WishlistItemCollectionTransfer()),
            );

        // Assert
        $this->assertSame(1, $wishlistItemCollectionTransfer->getItems()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWillExpandCollectionWhenWishlistCollectionDoesNotContainProductConfigurationInstance(): void
    {
        // Arrange
        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
        );

        $failedWishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $this->tester->createProductConfigurationInstance(),
        );

        // Act
        $wishlistItemCollectionTransfer = $this->tester->getClient()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                (new WishlistItemCollectionTransfer()),
            );

        // Assert
        $this->assertSame(1, $wishlistItemCollectionTransfer->getItems()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWillExpandCollectionWhenItemsHaveDifferentProductConfigurationInstances(): void
    {
        // Arrange
        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $this->tester->createProductConfigurationInstance([
                ProductConfigurationInstanceTransfer::DISPLAY_DATA => static::FAKE_DISPLAY_DATA_1,
            ]),
        );

        $failedWishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $this->tester->createProductConfigurationInstance([
                ProductConfigurationInstanceTransfer::DISPLAY_DATA => static::FAKE_DISPLAY_DATA_2,
            ]),
        );

        // Act
        $wishlistItemCollectionTransfer = $this->tester->getClient()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                (new WishlistItemCollectionTransfer()),
            );

        // Assert
        $this->assertSame(1, $wishlistItemCollectionTransfer->getItems()->count());

        $resultWishlistItemProductConfigurationInstanceConfigurationKey = $wishlistItemCollectionTransfer
            ->getItems()[0]
            ->getProductConfigurationInstance()
            ->getDisplayData();

        $this->assertSame(static::FAKE_DISPLAY_DATA_1, $resultWishlistItemProductConfigurationInstanceConfigurationKey);
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWillNotExpandCollectionWhenItemsHaveSameProductConfigurationInstances(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = $this->tester->createProductConfigurationInstance([
            ProductConfigurationInstanceTransfer::DISPLAY_DATA => static::FAKE_DISPLAY_DATA_2,
        ]);

        $wishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $productConfigurationInstanceTransfer,
        );

        $failedWishlistMoveToCartRequestCollectionTransfer = $this->tester->createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
            $this->tester->haveCustomer(),
            $productConfigurationInstanceTransfer,
        );

        // Act
        $wishlistItemCollectionTransfer = $this->tester->getClient()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                (new WishlistItemCollectionTransfer()),
            );

        // Assert
        $this->assertSame(0, $wishlistItemCollectionTransfer->getItems()->count());
    }
}
