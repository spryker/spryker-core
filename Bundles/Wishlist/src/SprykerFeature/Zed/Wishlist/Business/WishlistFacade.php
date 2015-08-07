<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistFacade extends AbstractFacade implements WishlistFacadeInterface
{
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function addItem(WishlistChangeInterface $wishlistChange)
    {
        return $this->getDependencyContainer()->createAddOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItem(WishlistChangeInterface $wishlistChange)
    {
        return $this->getDependencyContainer()->createRemoveOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function decreaseQuantity(WishlistChangeInterface $wishlistChange)
    {
        return $this->getDependencyContainer()->createDecreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseQuantity(WishlistChangeInterface $wishlistChange)
    {
        return $this->getDependencyContainer()->createIncreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function getCustomerWishlist(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer($customerTransfer)->getWishlist();
    }

}
