<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface;
use Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapper;
use Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestVersionMapper;
use Spryker\Zed\QuoteRequest\QuoteRequestDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface getRepository()
 */
class QuoteRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    public function getQuoteRequestPropelQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery
     */
    public function getQuoteRequestVersionPropelQuery(): SpyQuoteRequestVersionQuery
    {
        return SpyQuoteRequestVersionQuery::create();
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapper
     */
    public function createQuoteRequestMapper(): QuoteRequestMapper
    {
        return new QuoteRequestMapper();
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestVersionMapper
     */
    public function createQuoteRequestVersionMapper(): QuoteRequestVersionMapper
    {
        return new QuoteRequestVersionMapper(
            $this->getConfig(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): QuoteRequestToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
