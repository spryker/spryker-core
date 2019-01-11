<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface;
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
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface
     */
    public function getCustomerFacade(): QuoteRequestToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CUSTOMER);
    }
}
