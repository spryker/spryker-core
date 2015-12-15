<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Customer
{

    /**
     * @var WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @param WishlistQueryContainerInterface $wishlistQueryContainer
     * @param CustomerTransfer $customerTransfer
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        CustomerTransfer $customerTransfer
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
