<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\WishlistBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Wishlist\Business\Storage\StorageInterface;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * @method WishlistBusiness getFactory()
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Operator\Add
     */
    public function createAddOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        return $this->getFactory()->createOperatorAdd($storage, $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Operator\Increase
     */
    public function createIncreaseOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        return $this->getFactory()->createOperatorIncrease($storage, $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Operator\Decrease
     */
    public function createDecreaseOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        return $this->getFactory()->createOperatorDecrease($storage, $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Operator\Remove
     */
    public function createRemoveOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        return $this->getFactory()->createOperatorRemove($storage, $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return StorageInterface
     */
    protected function createStorage(WishlistChangeInterface $wishlistChange)
    {
        if (null !==$wishlistChange->getCustomer()) {
            return $this->createPropelStorage($wishlistChange);
        }
        return $this->createInMemoryStrorage($wishlistChange);

    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Storage\Propel
     */
    protected function createPropelStorage(WishlistChangeInterface $wishlistChange)
    {
        return $this->getFactory()->createStoragePropel($this->getQueryContainer(), $wishlistChange->getWishlist());
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Storage\InMemory
     */
    protected function createInMemoryStrorage(WishlistChangeInterface $wishlistChange)
    {
        return $this->getFactory()->createStorageInMemory($wishlistChange->getWishlist());
    }
}
