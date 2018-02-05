<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Spryker\Zed\CompanyRole\Persistence\Propel\Mapper\CompanyRoleMapper;
use Spryker\Zed\CompanyRole\Persistence\Propel\Mapper\CompanyRoleMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface getQueryContainer()
 */
class CompanyRolePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Persistence\Propel\Mapper\CompanyRoleMapperInterface
     */
    public function createCompanyRoleMapper(): CompanyRoleMapperInterface
    {
        return new CompanyRoleMapper();
    }

    /**
     * @return CompanyRoleRepositoryInterface
     */
    public function createCompanyRoleRepository()
    {
        return new CompanyRoleRepository();
    }
}
