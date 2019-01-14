<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReader;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGenerator;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriterInterface
     */
    public function createQuoteRequestWriter(): QuoteRequestWriterInterface
    {
        return new QuoteRequestWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getCustomerFacade(),
            $this->createQuoteRequestReferenceGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    protected function createQuoteRequestReferenceGenerator(): QuoteRequestReferenceGeneratorInterface
    {
        return new QuoteRequestReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig()->getQuoteRequestReferenceDefaults()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface
     */
    public function getCustomerFacade(): QuoteRequestToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade(): QuoteRequestToSequenceNumberInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }
}
