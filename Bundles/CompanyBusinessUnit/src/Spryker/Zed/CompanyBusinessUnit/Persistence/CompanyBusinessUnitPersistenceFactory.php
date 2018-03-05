<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper\CompanyBusinessUnitMapper;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    public function createCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper\CompanyBusinessUnitMapperInterface
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapperInterface
    {
        return new CompanyBusinessUnitMapper();
    }
}
