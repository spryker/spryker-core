<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Persistence;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig getConfig()
 */
class CompanyBusinessUnitDataImportPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    public function createCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }
}
