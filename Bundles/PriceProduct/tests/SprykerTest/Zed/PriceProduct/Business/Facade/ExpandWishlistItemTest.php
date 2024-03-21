<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group ExpandWishlistItemTest
 * Add your own group annotations below this line
 */
class ExpandWishlistItemTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandWishlistItemWithPrices(): void
    {
        // Arrange
        /*
         * Used in a context of {@link \Spryker\Zed\Wishlist\Communication\Controller\GatewayController}
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]));
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->createPriceProductTransfer(
            $productConcreteTransfer,
            $priceTypeTransfer,
            10,
            9,
            PriceProductBusinessTester::EUR_ISO_CODE,
        );
        $priceProductTransfer = $this->tester->havePriceProduct($priceProductTransfer->toArray());

        $customer = $this->tester->haveCustomer();
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customer->getIdCustomer(),
        ]);
        $wishlistItem = [
            WishlistItemTransfer::FK_CUSTOMER => $customer->getIdCustomer(),
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ];

        $wishlistItemTransfer = $this->tester->haveItemInWishlist($wishlistItem);
        $priceCountBefore = $wishlistItemTransfer->getPrices()->count();

        // Act
        $wishlistItemTransfer = $priceProductFacade->expandWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertSame($priceCountBefore + 1, $wishlistItemTransfer->getPrices()->count());
        $this->assertSame($priceProductTransfer->getSkuProduct(), $wishlistItemTransfer->getSku());
    }
}
