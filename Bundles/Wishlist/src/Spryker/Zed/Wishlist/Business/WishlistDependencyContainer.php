<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Wishlist\Business\Model\Customer;
use Spryker\Zed\Wishlist\Business\Operator\AbstractOperator;
use Spryker\Zed\Wishlist\Business\Storage\StorageInterface;
use Spryker\Zed\Wishlist\Business\Storage\Propel;
use Spryker\Zed\Wishlist\Business\Storage\InMemory;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer;
use Spryker\Zed\Wishlist\Business\Operator\Add;
use Spryker\Zed\Wishlist\Business\Operator\Increase;
use Spryker\Zed\Wishlist\Business\Operator\Remove;
use Spryker\Zed\Wishlist\Business\Operator\Decrease;
use Spryker\Zed\Wishlist\WishlistDependencyProvider;

/**
 * @method WishlistQueryContainer getQueryContainer()
 */
class WishlistDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return Add
     */
    public function createAddOperator(WishlistChangeTransfer $wishlistChange)
    {
        $storage = $this->createStorage($wishlistChange);
        $operator = new Add($storage, $wishlistChange);
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
        $operator = new Increase($storage, $wishlistChange);
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
        $operator = new Decrease($storage, $wishlistChange);
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
        $operator = new Remove($storage, $wishlistChange);
        $this->provideOperatorPlugins($operator);

        return $operator;
    }

    /**
     * @param AbstractOperator $operator
     *
     * @return void
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
        return new Propel(
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
        return new InMemory(
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
        return new Customer($this->getQueryContainer(), $customerTransfer);
    }

}
