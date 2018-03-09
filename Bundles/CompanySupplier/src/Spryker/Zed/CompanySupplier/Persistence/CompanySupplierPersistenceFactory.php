<?php

namespace Spryker\Zed\CompanySupplier\Persistence;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanySupplier\CompanySupplierConfig getConfig()
 */
class CompanySupplierPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function createCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }
}
