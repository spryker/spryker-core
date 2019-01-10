<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapper;
use Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapperInterface;

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
    public function createQuoteRequestQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper\QuoteRequestMapperInterface
     */
    public function createQuoteRequestMapper(): QuoteRequestMapperInterface
    {
        return new QuoteRequestMapper();
    }
}
