<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business;

use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestDependencyProvider;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestReader;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestReaderInterface;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriterInterface;
use Spryker\Zed\AgentQuoteRequest\Business\CompanyUser\CompanyUserReader;
use Spryker\Zed\AgentQuoteRequest\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig getConfig()
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestRepositoryInterface getRepository()
 */
class AgentQuoteRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestReaderInterface
     */
    public function createAgentQuoteRequestReader(): AgentQuoteRequestReaderInterface
    {
        return new AgentQuoteRequestReader(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriterInterface
     */
    public function createAgentQuoteRequestWriter(): AgentQuoteRequestWriterInterface
    {
        return new AgentQuoteRequestWriter(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Business\CompanyUser\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface
     */
    public function getQuoteRequestFacade(): AgentQuoteRequestToQuoteRequestInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestDependencyProvider::FACADE_QUOTE_REQUEST);
    }
}
