<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleCompanyUserMapper;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleCompanyUserMapperInterface;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleMapper;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleMapperInterface;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRolePermissionMapper;
use Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRolePermissionMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyRole\CompanyRoleConfig getConfig()
 */
class CompanyRolePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleMapperInterface
     */
    public function createCompanyRoleMapper(): CompanyRoleMapperInterface
    {
        return new CompanyRoleMapper();
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRolePermissionMapperInterface
     */
    public function createCompanyRolePermissionMapper(): CompanyRolePermissionMapperInterface
    {
        return new CompanyRolePermissionMapper();
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\Mapper\CompanyRoleCompanyUserMapperInterface
     */
    public function createCompanyRoleCompanyUserMapper(): CompanyRoleCompanyUserMapperInterface
    {
        return new CompanyRoleCompanyUserMapper();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery
     */
    public function createCompanyRoleToPermissionQuery(): SpyCompanyRoleToPermissionQuery
    {
        return SpyCompanyRoleToPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery
     */
    public function createCompanyRoleToCompanyUserQuery(): SpyCompanyRoleToCompanyUserQuery
    {
        return new SpyCompanyRoleToCompanyUserQuery();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    public function createCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return SpyCompanyRoleQuery::create();
    }
}
