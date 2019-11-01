<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business;

use Spryker\Zed\CartCode\Business\Operation\CodeAdder;
use Spryker\Zed\CartCode\Business\Operation\CodeAdderInterface;
use Spryker\Zed\CartCode\Business\Operation\CodeDeleter;
use Spryker\Zed\CartCode\Business\Operation\CodeDeleterInterface;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationChecker;
use Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface;
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
     * @return \Spryker\Zed\CartCode\Business\Operation\CodeAdderInterface
     */
    public function createCodeAdder(): CodeAdderInterface
    {
        return new CodeAdder(
            $this->getCalculationFacade(),
            $this->createQuoteOperationChecker(),
            $this->getCartCodePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartCode\Business\Operation\CodeDeleterInterface
     */
    public function createCodeDeleter(): CodeDeleterInterface
    {
        return new CodeDeleter(
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
     * @return \Spryker\Shared\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    public function getCartCodePlugins(): array
    {
        return $this->getProvidedDependency(CartCodeDependencyProvider::PLUGIN_CART_CODE_COLLECTION);
    }
}
