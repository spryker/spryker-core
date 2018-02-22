<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUser\Persistence\Propel\Mapper\CompanyUserMapperInterface;
use Spryker\Zed\CompanyUser\Persistence\Propel\Mapper\CompanyUserUserMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserQueryContainerInterface getQueryContainer()
 */
class CompanyUserPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CompanyUser\Persistence\Propel\Mapper\CompanyUserMapperInterface
     */
    public function createCompanyUserMapper(): CompanyUserMapperInterface
    {
        return new CompanyUserUserMapper();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function createCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
