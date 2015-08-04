<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\WishlistBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Wishlist\Business\Model\Customer;
use SprykerFeature\Zed\Wishlist\Business\Operator\AbstractOperator;
use SprykerFeature\Zed\Wishlist\Business\Storage\StorageInterface;
use SprykerFeature\Zed\Wishlist\Business\Storage\Propel;
use SprykerFeature\Zed\Wishlist\Business\Storage\InMemory;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;
use SprykerFeature\Zed\Wishlist\Business\Operator\Add;
use SprykerFeature\Zed\Wishlist\Business\Operator\Increase;
use SprykerFeature\Zed\Wishlist\Business\Operator\Remove;
use SprykerFeature\Zed\Wishlist\Business\Operator\Decrease;
use SprykerFeature\Zed\Wishlist\WishlistDependencyProvider;

/**
 * @method WishlistBusiness getFactory()
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Add
     */
    public function createAddOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorAdd($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);
        return $operator;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Increase
     */
    public function createIncreaseOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorIncrease($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);
        return $operator;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Decrease
     */
    public function createDecreaseOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorDecrease($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);
        return $operator;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Remove
     */
    public function createRemoveOperator(WishlistChangeInterface $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorRemove($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);
        return $operator;
    }

    /**
     * @param AbstractOperator $operator
     */
    protected function provideOperatorPlugins(AbstractOperator $operator)
    {
        $operator->setPostSavePlugins($this->getProvidedDependency(WishlistDependencyProvider::POST_SAVE_PLUGINS));
        $operator->setPreSavePlugins($this->getProvidedDependency(WishlistDependencyProvider::PRE_SAVE_PLUGINS));
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return StorageInterface
     */
    protected function createStorage(WishlistChangeInterface $wishlistChange)
    {
        if (null !== $wishlistChange->getCustomer()) {
            return $this->createPropelStorage($wishlistChange);
        }
        return $this->createInMemoryStrorage($wishlistChange);

    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return Propel
     */
    protected function createPropelStorage(WishlistChangeInterface $wishlistChange)
    {
        return $this->getFactory()->createStoragePropel(
            $this->getQueryContainer(),
            $this->createCustomer($wishlistChange->getCustomer()),
            $wishlistChange->getWishlist(),
            $wishlistChange->getCustomer(),
            $this->getProvidedDependency(WishlistDependencyProvider::PRODUCT_FACADE)
        );
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return InMemory
     */
    protected function createInMemoryStrorage(WishlistChangeInterface $wishlistChange)
    {
        return $this->getFactory()->createStorageInMemory($wishlistChange->getWishlist());
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return Customer
     */
    public function createCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getFactory()->createModelCustomer($this->getQueryContainer(), $customerTransfer);
    }
}
