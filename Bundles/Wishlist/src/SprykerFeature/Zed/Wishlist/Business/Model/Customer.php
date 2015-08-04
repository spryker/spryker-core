<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Model;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

class Customer
{
    /**
     * @var WishlistQueryContainer
     */
    private $wishlistQueryContainer;

    /**
     * @var CustomerInterface
     */
    private $customerTransfer;

    /**
     * @param WishlistQueryContainer $wishlistQueryContainer
     * @param CustomerInterface      $customerTransfer
     */
    public function __construct(WishlistQueryContainer $wishlistQueryContainer, CustomerInterface $customerTransfer)
    {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
    }

    /**
     * @return WishlistTransfer
     */
    public function getWishlist()
    {
        $wishlist = $this->wishlistQueryContainer
            ->getWishlistQuery()
            ->findOneByFkCustomer($this->customerTransfer->getIdCustomer());

        $wishlistTransfer = new WishlistTransfer();
        foreach ($wishlist->getSpyWishlistItems() as $spyWishlistItem) {
            $wishlistItemTransfer = new ItemTransfer();
            $spyProduct = $spyWishlistItem->getSpyProduct();
            $wishlistItemTransfer->setGroupKey($spyWishlistItem->getGroupKey())
                ->setAddedAt($spyWishlistItem->getAddedAt())
                ->setIdAbstractProduct($spyWishlistItem->getFkAbstractProduct())
                ->setSku($spyProduct->getSku())
                ->setQuantity($spyWishlistItem->getQuantity());

            $wishlistTransfer->addItem($wishlistItemTransfer);
        }

        return $wishlistTransfer;
    }
}
