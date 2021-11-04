<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlistsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
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
 * @method \Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\ProductConfigurationWishlistsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationWishlistsRestApiBusinessTester extends Actor
{
    use _generated\ProductConfigurationWishlistsRestApiBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string
    {
        return $this->getLocator()
            ->productConfiguration()
            ->service()
            ->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function createWishlistItemWithProductConfigurationInstance(
        CustomerTransfer $customerTransfer,
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): WishlistItemTransfer {
        $productConcreteTransfer = $this->haveProduct();
        $wishlistTransfer = $this->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer = $this->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ]);

        if ($productConfigurationInstanceTransfer !== null) {
            $wishlistItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $wishlistItemTransfer;
    }

    /**
     * @param int $idWishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemById(int $idWishlistItem): ?WishlistItemTransfer
    {
        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->setIdWishlistItem($idWishlistItem);

        return $this->getLocator()
            ->wishlist()
            ->facade()
            ->getWishlistItem($wishlistItemCriteriaTransfer)
            ->getWishlistItem();
    }
}
