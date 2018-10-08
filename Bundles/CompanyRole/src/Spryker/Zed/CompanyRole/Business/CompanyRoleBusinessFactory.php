<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business;

use Spryker\Zed\CompanyRole\Business\Model\CompanyRole;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRoleInterface;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionReader;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionReaderInterface;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriter;
use Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriterInterface;
use Spryker\Zed\CompanyRole\CompanyRoleDependencyProvider;
use Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyRole\CompanyRoleConfig getConfig()
 */
class CompanyRoleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Business\Model\CompanyRoleInterface
     */
    public function createCompanyRole(): CompanyRoleInterface
    {
        return new CompanyRole(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createCompanyRolePermissionWriter(),
            $this->getConfig(),
            $this->getPermissionFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionWriterInterface
     */
    protected function createCompanyRolePermissionWriter(): CompanyRolePermissionWriterInterface
    {
        return new CompanyRolePermissionWriter(
            $this->createCompanyRolePermissionReader(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionReaderInterface
     */
    protected function createCompanyRolePermissionReader(): CompanyRolePermissionReaderInterface
    {
        return new CompanyRolePermissionReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface
     */
    public function getPermissionFacade(): CompanyRoleToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleDependencyProvider::FACADE_PERMISSION);
    }
}
