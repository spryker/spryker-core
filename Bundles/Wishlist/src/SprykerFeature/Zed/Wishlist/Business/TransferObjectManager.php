<?php

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistProductTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Zed\Wishlist\Business\EntityManager;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use Zend\Stdlib\ArrayObject;

class TransferObjectManager
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getWishlistTransfer(CustomerInterface $customerTransfer)
    {
        $wishlistTransfer = $this->getWishlistTransferInstance();
        $wishlistTransfer->setCustomer($customerTransfer);
        $wishlistTransfer->setItems($this->getWishlistItemsArrayObject($customerTransfer));

        return $wishlistTransfer;
    }

    protected function getWishlistItemsArrayObject(CustomerInterface $customerTransfer)
    {
        $wishlistItems = array_map(function(SpyWishlistItem $item){

            return $this->getWishlistItemTransferInstance()
                ->setId($item->getIdWishlistItem())
                ->setAddedAt($item->getAddedAt())
                ->setQuantity($item->getQuantity())
                ->setProduct($this->getWishlistProductTransfer($item));

        }, $this->em->getItems($customerTransfer));

        return $this->wrapInArrayObject($wishlistItems);
    }

    protected function getWishlistProductTransfer(SpyWishlistItem $item)
    {
        $wishlitsProductTransfer = $this->getWishlistProductTransferInstance();

        $concreteSku = $item->getVirtualColumn($this->em->getConcreteSkuColumnName());
        $wishlitsProductTransfer->setConcreteSku($concreteSku);

        $abstractSku = $item->getVirtualColumn($this->em->getAbstractSkuColumnName());
        $wishlitsProductTransfer->setConcreteSku($abstractSku);

        return $wishlitsProductTransfer;

    }

    protected function wrapInArrayObject(array $array)
    {
        return new \ArrayObject($array);
    }

    protected function getWishlistTransferInstance()
    {
        return new WishlistTransfer();
    }

    protected function getWishlistItemTransferInstance()
    {
        return new WishlistItemTransfer();
    }

    protected function getWishlistProductTransferInstance()
    {
        return new WishlistProductTransfer();
    }
}
