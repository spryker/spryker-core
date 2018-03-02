<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\Model\QuoteDeleter;
use Spryker\Zed\Quote\Business\Model\QuoteMerger;
use Spryker\Zed\Quote\Business\Model\QuoteReader;
use Spryker\Zed\Quote\Business\Model\QuoteStorageSynchronizer;
use Spryker\Zed\Quote\Business\Model\QuoteWriter;
use Spryker\Zed\Quote\QuoteDependencyProvider;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 */
class QuoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteWriterInterface
     */
    public function createQuoteWriter()
    {
        return new QuoteWriter(
            $this->getEntityManager(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteReaderInterface
     */
    public function createQuoteReader()
    {
        return new QuoteReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteDeleterInterface
     */
    public function createQuoteDeleter()
    {
        return new QuoteDeleter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteStorageSynchronizer
     */
    public function createQuoteStorageSynchronizer()
    {
        return new QuoteStorageSynchronizer(
            $this->getConfig(),
            $this->getRepository(),
            $this->createQuoteWriter(),
            $this->createQuoteMerger()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteMergerInterface
     */
    protected function createQuoteMerger()
    {
        return new QuoteMerger(
            $this->getCalculationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Facade\QuoteToCalculationFacadeInterface
     */
    protected function getCalculationFacade()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::FACADE_CALCULATION);
    }
}
