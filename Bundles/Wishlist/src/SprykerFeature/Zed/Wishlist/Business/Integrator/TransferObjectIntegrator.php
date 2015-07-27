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
use ArrayObject;

class TransferObjectIntegrator
{
    const MERGE_MODE = "merge";

    const OVERWRITE_MODE = "overwrite";

    /**
     * @var EntityIntegrator
     */
    protected $ei;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @param EntityIntegrator $em
     * @param string $mode
     */
    public function __construct(EntityIntegrator $ei, $mode=self::OVERWRITE_MODE)
    {
        $this->ei = $ei;
        $this->mode = $mode;
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

        $wishlistTransfer->setItems($this->getWishlistEntityItemsArrayObject($customerTransfer));

        return $wishlistTransfer;
    }

    /**
     * @param WishlistInterface $wishlistTransfer
     *
     * @return WishlistTransfer
     */
    public function mergeWishlist(WishlistInterface $wishlistTransfer)
    {
        $databaseWishlistItems = $this->getWishlistEntityItemsArrayObject($wishlistTransfer->getCustomer());

        $changeWishlistTransfer = $this->getWishlistChangeTransferInstance();

        $changeWishlistTransfer->setAddedItems($this->groupItems($wishlistTransfer->getItems(), $databaseWishlistItems));

        $changeWishlistTransfer->setCustomer($wishlistTransfer->getCustomer());

        $this->ei->saveItems($changeWishlistTransfer);

        return $this->getWishlistTransfer($changeWishlistTransfer->getCustomer());
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function groupAddedItems(WishlistChangeInterface $changeTransfer)
    {
        $wishlistTransfer = $this->getWishlistTransferInstance();

        $groupedItems = $this->groupItems($changeTransfer->getAddedItems(), $changeTransfer->getItems());

        $wishlistTransfer->setItems($groupedItems);

        return $wishlistTransfer;
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function ungroupRemovedItems(WishlistChangeInterface $changeTransfer)
    {
        $wishlistTransfer = $this->getWishlistTransferInstance();

        $groupedItems = $this->ungroupItems($changeTransfer->getRemovedItems(), $changeTransfer->getItems());

        $wishlistTransfer->setItems($groupedItems);

        return $wishlistTransfer;
    }

    /**
     * @param \ArrayObject $newItems
     * @param \ArrayObject $existingItems
     *
     * @return \ArrayObject
     */
    protected function groupItems(ArrayObject $newItems, ArrayObject $existingItems)
    {
        $groupedItems = [];

        foreach ($newItems as $newItem) {

            $equalItem = $this->getEqualByProduct($newItem->getProduct(), $existingItems);

            if (null !== $equalItem) {

                $newItem = $this->integrate($equalItem, $newItem);
                $existingItems = $this->ungroupItems($this->wrapInArrayObject([$newItem]), $existingItems);
            }

            $groupedItems[] = $newItem;
        }

        $this->appendItems($existingItems, $this->wrapInArrayObject($groupedItems));

        return $existingItems;
    }

    /**
     * @param ArrayObject $removedItems
     * @param ArrayObject $existingItems
     */
    protected function appendItems(ArrayObject $existingItems, ArrayObject $appendingItems)
    {
        foreach ($appendingItems as $item) {
            $existingItems[] = $item;
        }
    }

    /**
     * @param \ArrayObject $removedItems
     * @param \ArrayObject $existingItems
     *
     * @return \ArrayObject
     */
    protected function ungroupItems(ArrayObject $removedItems, ArrayObject $existingItems)
    {
        $groupedItems = [];

        foreach ($existingItems as $item) {

            $equalItem = $this->getEqualByProduct($item->getProduct(), $removedItems);

            if (null !== $equalItem) {

                continue;
            }

            $groupedItems[] = $item;
        }

        return $this->wrapInArrayObject($groupedItems);
    }

    /**
     * @param WishlistItemInterface $existingWishlistItem
     * @param WishlistItemInterface $wishlistItem
     *
     * @return WishlistItemInterface
     */
    protected function integrate(WishlistItemInterface $existingWishlistItem, WishlistItemInterface $wishlistItem)
    {
        switch($this->mode) {
            case self::OVERWRITE_MODE:
                $item = $this->overwriteWishlistItems($existingWishlistItem, $wishlistItem);
                break;
            case self::MERGE_MODE:
            default:
                $item = $this->mergeWishlistItems($existingWishlistItem, $wishlistItem);
                break;
        }

        return $item;
    }

    /**
     * @param WishlistItemInterface $existingWishlistItem
     * @param WishlistItemInterface $wishlistItem
     *
     * @return WishlistItemInterface
     */
    protected function mergeWishlistItems(WishlistItemInterface $existingWishlistItem, WishlistItemInterface $wishlistItem)
    {
        $quantity = $existingWishlistItem->getQuantity() + $wishlistItem->getQuantity();
        $existingWishlistItem->setQuantity($quantity);

        return $existingWishlistItem;
    }

    /**
     * @param WishlistItemInterface $existingWishlistItem
     * @param WishlistItemInterface $wishlistItem
     *
     * @return WishlistItemInterface
     */
    protected function overwriteWishlistItems(WishlistItemInterface $existingWishlistItem, WishlistItemInterface $wishlistItem)
    {
        $wishlistItem->getQuantity();
        $existingWishlistItem->setQuantity($wishlistItem->getQuantity());

        return $existingWishlistItem;
    }


    /**
     * @param WishlistProductInterface $wishlistProductTransfer
     * @param ArrayObject $wishlistItems
     *
     * @return WishlistItemInterface|null
     */
    protected function getEqualByProduct(WishlistProductInterface $wishlistProductTransfer, ArrayObject $wishlistItems)
    {
        foreach ($wishlistItems as $wishlistItem) {

            if ($wishlistProductTransfer == $wishlistItem->getProduct()) {

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
    protected function getWishlistEntityItemsArrayObject(CustomerInterface $customerTransfer)
    {
        $wishlistItems = array_map(function (SpyWishlistItem $item) {

            return $this->getWishlistItemTransferInstance()
                ->setId($item->getIdWishlistItem())
                ->setAddedAt($item->getAddedAt())
                ->setQuantity($item->getQuantity())
                ->setProduct($this->getWishlistProductTransfer($item));

        }, $this->ei->getItems($customerTransfer));

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

        $concreteSku = $item->getVirtualColumn($this->ei->getConcreteSkuColumnName());
        $wishlitsProductTransfer->setConcreteSku($concreteSku);

        $abstractSku = $item->getVirtualColumn($this->ei->getAbstractSkuColumnName());
        $wishlitsProductTransfer->setAbstractSku($abstractSku);

        return $wishlitsProductTransfer;
    }

    /**
     * @param array $array
     *
     * @return \ArrayObject
     */
    protected function wrapInArrayObject(array $array)
    {
        return new ArrayObject($array);
    }

    /**
     * @return WishlistChangeInterface
     */
    protected function getWishlistChangeTransferInstance()
    {
        return new WishlistChangeTransfer();
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
