<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiDependencyProvider;
use Spryker\Zed\CompanyUsersRestApi\Persistence\Mapper\CompanyUsersRestApiMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyUsersRestApi\Persistence\CompanyUsersRestApiRepositoryInterface getRepository()
 */
class CompanyUsersRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Persistence\Mapper\CompanyUsersRestApiMapper
     */
    public function createCompanyUsersRestApiMapper(): CompanyUsersRestApiMapper
    {
        return new CompanyUsersRestApiMapper();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserPropelQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    public function getCompanyRolePropelQuery(): SpyCompanyRoleQuery
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::PROPEL_QUERY_COMPANY_ROLE);
    }
}
