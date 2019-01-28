<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorageQuery;
use Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper\CompanyUserStorageMapper;
use Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper\CompanyUserStorageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface getRepository()
 */
class CompanyUserStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorageQuery
     */
    public function createCompanyUserStorageQuery(): SpyCompanyUserStorageQuery
    {
        return SpyCompanyUserStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper\CompanyUserStorageMapperInterface
     */
    public function createCompanyUserStorageMapper(): CompanyUserStorageMapperInterface
    {
        return new CompanyUserStorageMapper();
    }
}
