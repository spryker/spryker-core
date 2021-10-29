<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationWishlist;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationWishlistClientTester extends Actor
{
    use _generated\ProductConfigurationWishlistClientTesterActions;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function createProductConfigurationInstance(array $seedData = []): ProductConfigurationInstanceTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer */
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder($seedData))->build();

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function createWishlistMoveToCartRequestCollectionWithProductConfigurationInstance(
        CustomerTransfer $customerTransfer,
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): WishlistMoveToCartRequestCollectionTransfer {
        $wishListMoveToCartRequestTransfer = (new WishlistMoveToCartRequestTransfer())->setWishlistItem(
            $this->createWishlistItemWithProductConfigurationInstance(
                $customerTransfer,
                $productConfigurationInstanceTransfer,
            ),
        );

        return (new WishlistMoveToCartRequestCollectionTransfer())
            ->addRequest($wishListMoveToCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemWithProductConfigurationInstance(
        CustomerTransfer $customerTransfer,
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): WishlistItemTransfer {
        $wishlistTransfer = $this->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer = $this->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        if ($productConfigurationInstanceTransfer !== null) {
            $wishlistItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $wishlistItemTransfer;
    }
}
