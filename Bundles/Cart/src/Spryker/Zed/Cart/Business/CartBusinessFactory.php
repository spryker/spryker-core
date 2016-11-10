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
        return new Operation(
            $this->createStorageProvider(),
            $this->getCalculatorFacade(),
            $this->getMessengerFacade(),
            $this->getItemExpanderPlugins(),
            $this->getPostSavePlugins()
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
     * @return \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[]
     */
    protected function getPostSavePlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CART_POST_SAVE_PLUGINS);
    }

}
