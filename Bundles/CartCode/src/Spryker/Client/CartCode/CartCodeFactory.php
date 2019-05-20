<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface;
use Spryker\Client\CartCode\Operation\CodeAdder;
use Spryker\Client\CartCode\Operation\CodeAdderInterface;
use Spryker\Client\CartCode\Operation\CodeClearer;
use Spryker\Client\CartCode\Operation\CodeClearerInterface;
use Spryker\Client\CartCode\Operation\CodeRemover;
use Spryker\Client\CartCode\Operation\CodeRemoverInterface;
use Spryker\Client\CartCode\Operation\QuoteOperationChecker;
use Spryker\Client\CartCode\Operation\QuoteOperationCheckerInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartCodeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartCode\Operation\CodeAdderInterface
     */
    public function createCodeAdder(): CodeAdderInterface
    {
        return new CodeAdder(
            $this->getCalculationClient(),
            $this->createQuoteOperationChecker(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Client\CartCode\Operation\CodeRemoverInterface
     */
    public function createCodeRemover(): CodeRemoverInterface
    {
        return new CodeRemover(
            $this->getCalculationClient(),
            $this->createQuoteOperationChecker(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Client\CartCode\Operation\CodeClearerInterface
     */
    public function createCodeClearer(): CodeClearerInterface
    {
        return new CodeClearer(
            $this->getCalculationClient(),
            $this->createQuoteOperationChecker(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Client\CartCode\Operation\QuoteOperationCheckerInterface
     */
    public function createQuoteOperationChecker(): QuoteOperationCheckerInterface
    {
        return new QuoteOperationChecker($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface
     */
    public function getCalculationClient(): CartCodeToCalculationClientInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface
     */
    public function getQuoteClient(): CartCodeToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    public function getCartCodePlugins(): array
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::PLUGIN_CART_CODE_COLLECTION);
    }
}
