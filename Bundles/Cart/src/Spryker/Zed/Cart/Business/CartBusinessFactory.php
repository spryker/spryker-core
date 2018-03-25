<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business;

use Spryker\Zed\Cart\Business\Model\Operation;
use Spryker\Zed\Cart\Business\StorageProvider\NonPersistentProvider;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class CartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Cart\Business\Model\OperationInterface
     */
    public function createCartOperation()
    {
        $operation = new Operation(
            $this->createStorageProvider(),
            $this->getCalculatorFacade(),
            $this->getMessengerFacade(),
            $this->getItemExpanderPlugins(),
            $this->getCartPreCheckPlugins(),
            $this->getPostSavePlugins(),
            $this->getTerminationPlugins()
        );

        $operation->setPreReloadLoadPlugins($this->getPreReloadItemsPlugins());

        return $operation;
    }

    /**
     * @return \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected function createStorageProvider()
    {
        return new NonPersistentProvider();
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     */
    protected function getCalculatorFacade()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
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

    /**
     * @return \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[]
     */
    protected function getCartPreCheckPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_PRE_CHECK_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[]
     */
    protected function getPostSavePlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_POST_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\PreReloadItemsPluginInterface[]
     */
    protected function getPreReloadItemsPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_PRE_RELOAD_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[]
     */
    protected function getTerminationPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_TERMINATION_PLUGINS);
    }
}
