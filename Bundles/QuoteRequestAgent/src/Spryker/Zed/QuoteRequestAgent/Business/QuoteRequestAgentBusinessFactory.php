<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\CompanyUserReader;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\CompanyUserReaderInterface;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestAgentReader;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestAgentReaderInterface;
use Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestAgentWriter;
use Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestAgentWriterInterface;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface;
use Spryker\Zed\QuoteRequestAgent\QuoteRequestAgentDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequestAgent\QuoteRequestAgentConfig getConfig()
 */
class QuoteRequestAgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Business\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestAgentReaderInterface
     */
    public function createQuoteRequestAgentReader(): QuoteRequestAgentReaderInterface
    {
        return new QuoteRequestAgentReader(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestAgentWriterInterface
     */
    public function createQuoteRequestAgentWriter(): QuoteRequestAgentWriterInterface
    {
        return new QuoteRequestAgentWriter(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface
     */
    public function getQuoteRequestFacade(): QuoteRequestAgentToQuoteRequestFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::FACADE_QUOTE_REQUEST);
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): QuoteRequestAgentToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::FACADE_COMPANY_USER);
    }
}
