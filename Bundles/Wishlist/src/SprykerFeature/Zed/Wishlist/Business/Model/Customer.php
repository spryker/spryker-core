<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Model;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Customer
{
    /**
     * @var WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var CustomerInterface
     */
    protected $customerTransfer;

    /**
     * @param WishlistQueryContainerInterface $wishlistQueryContainer
     * @param CustomerInterface      $customerTransfer
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        CustomerInterface $customerTransfer
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
    }

    /**
     * @return WishlistTransfer
     */
    public function getWishlist()
    {
        $wishlist = $this->wishlistQueryContainer
            ->queryWishlist()
            ->findOneByFkCustomer($this->customerTransfer->getIdCustomer());

        $wishlistTransfer = new WishlistTransfer();
        foreach ($wishlist->getSpyWishlistItems() as $wishlistItemEntity) {
            $wishlistItemTransfer = new ItemTransfer();
            $productEntity = $wishlistItemEntity->getSpyProduct();
            $wishlistItemTransfer->setGroupKey($wishlistItemEntity->getGroupKey())
                ->setAddedAt($wishlistItemEntity->getAddedAt())
                ->setIdAbstractProduct($wishlistItemEntity->getFkAbstractProduct())
                ->setSku($productEntity->getSku())
                ->setQuantity($wishlistItemEntity->getQuantity());

            $wishlistTransfer->addItem($wishlistItemTransfer);
        }

        return $wishlistTransfer;
    }
}
