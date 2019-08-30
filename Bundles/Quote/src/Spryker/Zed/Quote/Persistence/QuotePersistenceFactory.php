<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Quote\Persistence\Propel\Mapper\QuoteMapper;
use Spryker\Zed\Quote\QuoteDependencyProvider;

/**
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 * @method \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 */
class QuotePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    public function createQuoteQuery()
    {
        return SpyQuoteQuery::create();
    }

    /**
     * @return \Spryker\Zed\Quote\Persistence\Propel\Mapper\QuoteMapperInterface
     */
    public function createQuoteMapper()
    {
        return new QuoteMapper($this->getUtilEncodingService(), $this->getConfig(), $this->getQuoteFieldsPlugins());
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteFieldsPlugins(): QuoteTransfer
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_FIELDS_EXPANDER);
    }
}
