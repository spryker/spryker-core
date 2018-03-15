<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PersistentCart\Business\Model\CartOperation;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpander;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriter;
use Spryker\Zed\PersistentCart\PersistentCartDependencyProvider;

class PersistentCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface
     */
    public function createCartOperation()
    {
        return new CartOperation(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizerInterface
     */
    public function createQuoteStorageSynchronizer()
    {
        return new QuoteStorageSynchronizer(
            $this->getCartFacade(),
            $this->getQuoteFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteDeleterInterface
     */
    public function createQuoteDeleter()
    {
        return new QuoteDeleter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface
     */
    public function createQuoteWriter()
    {
        return new QuoteWriter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    public function createQuoteResponseExpander()
    {
        return new QuoteResponseExpander(
            $this->getQuoteResponseExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected function getCartFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected function getQuoteFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteResponseExpanderPluginInterface[]
     */
    protected function getQuoteResponseExpanderPlugins()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_RESPONSE_EXPANDER);
    }
}
