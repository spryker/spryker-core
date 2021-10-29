<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlistsRestApi\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationWishlistsRestApi
 * @group Business
 * @group DeleteWishlistItemTest
 * Add your own group annotations below this line
 */
class DeleteWishlistItemTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'FAKE_SKU_1';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteWishlistItemDeletesWishlistItem(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $wishlistItemTransfer = $this->tester->createWishlistItemWithProductConfigurationInstance(
            $customerTransfer,
            (new ProductConfigurationInstanceTransfer())->setDisplayData('{}'),
        );

        $productConfigurationInstanceHash = $this->tester
            ->getProductConfigurationInstanceHash($wishlistItemTransfer->getProductConfigurationInstance());

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', $wishlistItemTransfer->getSku(), $productConfigurationInstanceHash));

        // Act
        $this->tester
            ->getFacade()
            ->deleteWishlistItem($wishlistItemRequestTransfer, new ArrayObject([$wishlistItemTransfer]));

        // Assert
        $this->assertNull($this->tester->findWishlistItemById($wishlistItemTransfer->getIdWishlistItem()));
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemAvoidRemovalCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $wishlistItemTransfer = $this->tester->createWishlistItemWithProductConfigurationInstance(
            $customerTransfer,
            (new ProductConfigurationInstanceTransfer())->setDisplayData('{}'),
        );

        $productConfigurationInstanceHash = $this->tester
            ->getProductConfigurationInstanceHash($wishlistItemTransfer->getProductConfigurationInstance());

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_1, $productConfigurationInstanceHash));

        // Act
        $this->tester
            ->getFacade()
            ->deleteWishlistItem($wishlistItemRequestTransfer, new ArrayObject([$wishlistItemTransfer]));

        // Assert
        $this->assertNotNull($this->tester->findWishlistItemById($wishlistItemTransfer->getIdWishlistItem()));
    }
}
