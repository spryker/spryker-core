<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface;
use Spryker\Client\CartCode\Operation\CodeAdder;
use Spryker\Client\CartCode\Operation\CodeCleaner;
use Spryker\Client\CartCode\Operation\CodeRemover;
use Spryker\Client\Kernel\AbstractFactory;

class CartCodeFactory extends AbstractFactory
{
    public function createCodeAdder()
    {
        return new CodeAdder(
            $this->getCartClient(),
            $this->getCalculationClient(),
            $this->getQuoteClient(),
            $this->getCartCodeHandlerPlugins()
        );
    }

    public function createCodeRemover()
    {
        return new CodeRemover(
            $this->getCartClient(),
            $this->getCalculationClient(),
            $this->getQuoteClient(),
            $this->getCartCodeHandlerPlugins()
        );
    }

    public function createCodeCleaner()
    {
        return new CodeCleaner(
            $this->getCartClient(),
            $this->getCalculationClient(),
            $this->getQuoteClient(),
            $this->getCartCodeHandlerPlugins()
        );
    }

    /**
     * @return \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface
     */
    public function getCalculationClient(): CartCodeToCalculationClientInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface
     */
    public function getCartClient(): CartCodeToCartClientInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface
     */
    public function getQuoteClient(): CartCodeToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[]
     */
    public function getCartCodeHandlerPlugins(): array
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::PLUGIN_CART_CODE_HANDLER_COLLECTION);
    }
}
