<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business;

use Spryker\Zed\CartCode\Business\Operation\CartCodeAdder;
use Spryker\Zed\CartCode\Business\Operation\CartCodeAdderInterface;
use Spryker\Zed\CartCode\Business\Operation\CartCodeClearer;
use Spryker\Zed\CartCode\Business\Operation\CartCodeClearerInterface;
use Spryker\Zed\CartCode\Business\Operation\CartCodeRemover;
use Spryker\Zed\CartCode\Business\Operation\CartCodeRemoverInterface;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationChecker;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface;
use Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessor;
use Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessorInterface;
use Spryker\Zed\CartCode\CartCodeDependencyProvider;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartCode\CartCodeConfig getConfig()
 */
class CartCodeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CartCodeAdderInterface
     */
    public function createCartCodeAdder(): CartCodeAdderInterface
    {
        return new CartCodeAdder(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->createRecalculationResultProcessor(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CartCodeRemoverInterface
     */
    public function createCartCodeRemover(): CartCodeRemoverInterface
    {
        return new CartCodeRemover(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->createRecalculationResultProcessor(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CartCodeClearerInterface
     */
    public function createCartCodeClearer(): CartCodeClearerInterface
    {
        return new CartCodeClearer(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface
     */
    public function createQuoteOperationChecker(): QuoteOperationCheckerInterface
    {
        return new QuoteOperationChecker($this->getQuoteFacade());
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessorInterface
     */
    public function createRecalculationResultProcessor(): RecalculationResultProcessorInterface
    {
        return new RecalculationResultProcessor($this->getCartCodePlugins());
    }

    /**
     * @return \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface
     */
    public function getCalculationFacade(): CartCodeToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface
     */
    public function getQuoteFacade(): CartCodeToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    public function getCartCodePlugins(): array
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::PLUGINS_CART_CODE);
    }
}
