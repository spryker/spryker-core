<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorder;

use Spryker\Client\CartReorder\Creator\CartReorderCreator;
use Spryker\Client\CartReorder\Creator\CartReorderCreatorInterface;
use Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface;
use Spryker\Client\CartReorder\Dependency\Client\CartReorderToZedRequestClientInterface;
use Spryker\Client\CartReorder\Zed\CartReorderStub;
use Spryker\Client\CartReorder\Zed\CartReorderStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CartReorder\CartReorderConfig getConfig()
 */
class CartReorderFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartReorder\Creator\CartReorderCreatorInterface
     */
    public function createCartReorderCreator(): CartReorderCreatorInterface
    {
        return new CartReorderCreator(
            $this->createCartReorderStub(),
            $this->getQuoteClient(),
            $this->getCartReorderQuoteProviderStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\CartReorder\Zed\CartReorderStubInterface
     */
    public function createCartReorderStub(): CartReorderStubInterface
    {
        return new CartReorderStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface
     */
    public function getQuoteClient(): CartReorderToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\CartReorder\Dependency\Client\CartReorderToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartReorderToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return list<\Spryker\Client\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface>
     */
    public function getCartReorderQuoteProviderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderDependencyProvider::PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY);
    }
}
