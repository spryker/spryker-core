<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
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
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Add
     */
    public function createAddOperator(WishlistChangeTransfer $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorAdd($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);

        return $operator;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Increase
     */
    public function createIncreaseOperator(WishlistChangeTransfer $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorIncrease($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);

        return $operator;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Decrease
     */
    public function createDecreaseOperator(WishlistChangeTransfer $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = $this->getFactory()->createOperatorDecrease($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);

        return $operator;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Remove
     */
    public function createRemoveOperator(WishlistChangeTransfer $wishlistChange)
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
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return StorageInterface
     */
    protected function createStorage(WishlistChangeTransfer $wishlistChange)
    {
        if ($wishlistChange->getCustomer() !== null) {
            return $this->createPropelStorage($wishlistChange);
        }

        return $this->createInMemoryStrorage($wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Propel
     */
    protected function createPropelStorage(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createStoragePropel(
            $this->getQueryContainer(),
            $this->createCustomer($wishlistChange->getCustomer()),
            $wishlistChange->getWishlist(),
            $wishlistChange->getCustomer(),
            $this->getProvidedDependency(WishlistDependencyProvider::FACADE_PRODUCT)
        );
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return InMemory
     */
    protected function createInMemoryStrorage(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createStorageInMemory(
            $wishlistChange->getWishlist(),
            $this->getProvidedDependency(WishlistDependencyProvider::FACADE_PRODUCT)
        );
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return Customer
     */
    public function createCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()->createModelCustomer($this->getQueryContainer(), $customerTransfer);
    }

}
