<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestDependencyProvider;
use Spryker\Zed\AgentQuoteRequest\Dependency\Service\AgentQuoteRequestToUtilEncodingServiceInterface;
use Spryker\Zed\AgentQuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig getConfig()
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface getEntityManager()
 */
class AgentQuoteRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    public function getQuoteRequestPropelQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }

    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapper
     */
    public function createQuoteRequestMapper(): QuoteRequestMapper
    {
        return new QuoteRequestMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\AgentQuoteRequest\Dependency\Service\AgentQuoteRequestToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): AgentQuoteRequestToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
