<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Integrator;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistProductTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use Generated\Shared\Wishlist\WishlistProductInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use Zend\Stdlib\ArrayObject;

class TransferObjectIntegrator
{
    /**
     * @param EntityIntegrator $em
     */
    public function __construct(EntityIntegrator $em)
    {
        $this->em = $em;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistTransfer
     */
    public function getWishlistTransfer(CustomerInterface $customerTransfer)
    {
        $wishlistTransfer = $this->getWishlistTransferInstance();
        $wishlistTransfer->setCustomer($customerTransfer);
        $wishlistTransfer->setItems($this->getWishlistItemsArrayObject($customerTransfer));

        return $wishlistTransfer;
    }

    /**
     * @param WishlistInterface $wishlistTransfer
     *
     * @return WishlistTransfer
     */
    public function mergeWishlist(WishlistInterface $wishlistTransfer)
    {
        $databaseWishlistItems = $this->getWishlistItemsArrayObject($wishlistTransfer->getCustomer());

        $changeWishlistTransfer = $this->getWishlistChangeTransferForCustomer($wishlistTransfer->getCustomer());

        foreach ($wishlistTransfer->getItems() as $wishlistItem) {

            $equalDatabaseItem = $this->getEqualByProduct($wishlistItem->getProduct(), $databaseWishlistItems->getItems());

            if(null !== $equalDatabaseItem) {

                $this->mergeWishlistItems($equalDatabaseItem, $wishlistItem);
            }

            $this->appendWishlistItem($changeWishlistTransfer, $wishlistItem);

        }

        $this->em->saveItems($changeWishlistTransfer);

        return $this->getWishlistTransfer($changeWishlistTransfer->getCustomer());
    }

    /**
     * @param WishlistItemInterface $databaseWishlistItem
     * @param WishlistItemInterface $wishlistItem
     */
    protected function mergeWishlistItems(WishlistItemInterface $databaseWishlistItem, WishlistItemInterface $wishlistItem)
    {
        $quantity = $databaseWishlistItem->getQuantity() + $wishlistItem->getQuantity();
        $databaseWishlistItem->setQuantity($quantity);
        $wishlistItem = $databaseWishlistItem;
    }

    protected function appendWishlistItem(WishlistChangeInterface $changeTransfer, WishlistItemInterface $item)
    {
        $changeTransfer->getItems()[] = $item;
    }

    /**
     * @param WishlistProductInterface $wishlistProductTransfer
     * @param ArrayObject $wishlistItems
     *
     * @return bool
     */
    protected function getEqualByProduct(WishlistProductInterface $wishlistProductTransfer, ArrayObject $wishlistItems)
    {
        foreach ($wishlistItems as $wishlistItem) {

            if($wishlistProductTransfer == $wishlistItem->getProduct()){

                return $wishlistItem;

            }
        }

        return null;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return \ArrayObject
     */
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

    /**
     * @param SpyWishlistItem $item
     *
     * @throws PropelException
     * @return WishlistProductTransfer
     */
    protected function getWishlistProductTransfer(SpyWishlistItem $item)
    {
        $wishlitsProductTransfer = $this->getWishlistProductTransferInstance();

        $concreteSku = $item->getVirtualColumn($this->em->getConcreteSkuColumnName());
        $wishlitsProductTransfer->setConcreteSku($concreteSku);

        $abstractSku = $item->getVirtualColumn($this->em->getAbstractSkuColumnName());
        $wishlitsProductTransfer->setConcreteSku($abstractSku);

        return $wishlitsProductTransfer;

    }

    /**
     * @param array $array
     *
     * @return \ArrayObject
     */
    protected function wrapInArrayObject(array $array)
    {
        return new \ArrayObject($array);
    }

    /**
     * @return WishlistChangeInterface
     */
    protected function getWishlistChangeTransferForCustomer(CustomerInterface $customerTransfer)
    {
        return (new WishlistChangeTransfer())->setCustomer($customerTransfer);
    }

    /**
     * @return WishlistInterface
     */
    protected function getWishlistTransferInstance()
    {
        return new WishlistTransfer();
    }

    /**
     * @return WishlistItemInterface
     */
    protected function getWishlistItemTransferInstance()
    {
        return new WishlistItemTransfer();
    }

    /**
     * @return WishlistProductInterface
     */
    protected function getWishlistProductTransferInstance()
    {
        return new WishlistProductTransfer();
    }
}
