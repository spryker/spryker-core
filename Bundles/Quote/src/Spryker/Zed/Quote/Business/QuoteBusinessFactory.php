<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\Model\Quote;
use Spryker\Zed\Quote\Business\Model\QuoteMerger;
use Spryker\Zed\Quote\Business\Model\QuotePluginExecutor;
use Spryker\Zed\Quote\QuoteDependencyProvider;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 */
class QuoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteInterface
     */
    public function createQuote()
    {
        return new Quote(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createQoutePluginExecutor(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteMergerInterface
     */
    public function createQuoteMerger()
    {
        return new QuoteMerger(
            $this->createQuote(),
            $this->getCalculationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuotePluginExecutorInterface
     */
    protected function createQoutePluginExecutor()
    {
        return new QuotePluginExecutor(
            $this->getQuotePreSavePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Plugin\QuotePreSavePluginInterface[]
     */
    protected function getQuotePreSavePlugins()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::QUOTE_PRE_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Facade\QuoteToCalculationFacadeInterface
     */
    protected function getCalculationFacade()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::FACADE_CALCULATION);
    }
}
