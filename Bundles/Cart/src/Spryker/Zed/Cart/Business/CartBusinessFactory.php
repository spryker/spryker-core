<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Cart\CartDependencyProvider;

/**
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class CartBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Cart\Business\Operation
     */
    public function createCartOperation()
    {
        return new Operation(
             $this->createStorageProvider(),
             $this->getCalculator(),
             $this->getItemGrouper(),
             $this->getMessengerFacade(),
             $this->getItemExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected function createStorageProvider()
    {
        return new NonPersistentProvider();
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface
     */
    protected function getItemGrouper()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_ITEM_GROUPER);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     *
     */
    protected function getCalculator()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected function getItemExpanderPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_EXPANDER_PLUGINS);
    }

}
