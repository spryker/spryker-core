<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanyTypeQuery;
use Spryker\Zed\CompanySupplier\CompanySupplierDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanySupplier\CompanySupplierConfig getConfig()
 */
class CompanySupplierPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyTypeQuery
     */
    public function createCompanyTypeQuery(): SpyCompanyTypeQuery
    {
        return SpyCompanyTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery
     */
    public function createCompanySupplierToProductQuery(): SpyCompanySupplierToProductQuery
    {
        return SpyCompanySupplierToProductQuery::create();
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function createCompanyQuery(): SpyCompanyQuery
    {
        return $this->getProvidedDependency(CompanySupplierDependencyProvider::PROPEL_QUERY_COMPANY);
    }
}
