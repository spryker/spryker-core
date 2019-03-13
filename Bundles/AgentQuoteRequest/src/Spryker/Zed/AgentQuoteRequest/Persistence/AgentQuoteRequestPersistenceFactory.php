<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig getConfig()
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestRepositoryInterface getRepository()
 */
class AgentQuoteRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserPropelQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(AgentQuoteRequestDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }
}
