<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\CartConfig;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;

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
     * @return \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade
     */
    protected function getItemGrouper()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_ITEM_GROUPER);
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\CalculationFacade
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
