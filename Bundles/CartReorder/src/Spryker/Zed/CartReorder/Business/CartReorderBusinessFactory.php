<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business;

use Spryker\Zed\CartReorder\Business\Adder\CartItemAdder;
use Spryker\Zed\CartReorder\Business\Adder\CartItemAdderInterface;
use Spryker\Zed\CartReorder\Business\Creator\CartReorderCreator;
use Spryker\Zed\CartReorder\Business\Creator\CartReorderCreatorInterface;
use Spryker\Zed\CartReorder\Business\Hydrator\ItemHydrator;
use Spryker\Zed\CartReorder\Business\Hydrator\ItemHydratorInterface;
use Spryker\Zed\CartReorder\Business\Reader\OrderReader;
use Spryker\Zed\CartReorder\Business\Reader\OrderReaderInterface;
use Spryker\Zed\CartReorder\Business\Validator\CartReorderValidator;
use Spryker\Zed\CartReorder\Business\Validator\CartReorderValidatorInterface;
use Spryker\Zed\CartReorder\CartReorderDependencyProvider;
use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToCartFacadeInterface;
use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartReorder\CartReorderConfig getConfig()
 */
class CartReorderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartReorder\Business\Creator\CartReorderCreatorInterface
     */
    public function createCartReorderCreator(): CartReorderCreatorInterface
    {
        return new CartReorderCreator(
            $this->createCartReorderValidator(),
            $this->createOrderReader(),
            $this->createItemHydrator(),
            $this->createCartItemAdder(),
            $this->getCartReorderQuoteProviderStrategyPlugins(),
            $this->getCartReorderItemFilterPlugins(),
            $this->getCartPreReorderPlugins(),
            $this->getCartPostReorderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CartReorder\Business\Adder\CartItemAdderInterface
     */
    public function createCartItemAdder(): CartItemAdderInterface
    {
        return new CartItemAdder(
            $this->getCartFacade(),
            $this->getCartReorderPreAddToCartPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CartReorder\Business\Hydrator\ItemHydratorInterface
     */
    public function createItemHydrator(): ItemHydratorInterface
    {
        return new ItemHydrator(
            $this->getCartReorderItemHydratorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CartReorder\Business\Validator\CartReorderValidatorInterface
     */
    public function createCartReorderValidator(): CartReorderValidatorInterface
    {
        return new CartReorderValidator(
            $this->getCartReorderValidatorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CartReorder\Business\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader(
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToCartFacadeInterface
     */
    public function getCartFacade(): CartReorderToCartFacadeInterface
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeInterface
     */
    public function getSalesFacade(): CartReorderToSalesFacadeInterface
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::FACADE_SALES);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface>
     */
    public function getCartReorderValidatorPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_VALIDATOR);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface>
     */
    public function getCartReorderQuoteProviderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemFilterPluginInterface>
     */
    public function getCartReorderItemFilterPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_ITEM_FILTER);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface>
     */
    public function getCartPreReorderPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_PRE_REORDER);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface>
     */
    public function getCartReorderItemHydratorPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_ITEM_HYDRATOR);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface>
     */
    public function getCartReorderPreAddToCartPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_PRE_ADD_TO_CART);
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface>
     */
    public function getCartPostReorderPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_POST_REORDER);
    }
}
