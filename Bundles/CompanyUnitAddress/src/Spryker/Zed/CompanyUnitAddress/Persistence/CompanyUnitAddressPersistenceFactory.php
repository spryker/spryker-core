<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\CompanyUnitAddressHydrator;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\CompanyUnitAddressHydratorInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapper;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyUnitAddressPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    public function createCompanyUnitAddressQuery(): SpyCompanyUnitAddressQuery
    {
        return SpyCompanyUnitAddressQuery::create();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\Propel\CompanyUnitAddressHydratorInterface
     */
    public function createCompanyUnitAddressHydrator(): CompanyUnitAddressHydratorInterface
    {
        return new CompanyUnitAddressHydrator($this->createCompanyUniAddressMapper());
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapperInterface
     */
    public function createCompanyUniAddressMapper(): CompanyUnitAddressMapperInterface
    {
        return new CompanyUnitAddressMapper();
    }
}
