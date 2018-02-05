<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\Base\SpyCompanyRoleToPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class CompanyRoleRepository implements CompanyRoleRepositoryInterface
{
    /**
     * Specification:
     * - Set QueryContainer to Repository Object
     *
     * @deprecated
     *
     * @param \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer $companyRoleQueryContainer
     *
     * @return $this
     */
    public function setQueryContainer(AbstractQueryContainer $companyRoleQueryContainer)
    {
        // TODO: Implement setQueryContainer() method.
    }

    /**
     * Specification:
     * - Set PersistenceFactory to Repository Object
     *
     * @deprecated
     *
     * @param \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory $persistenceFactory
     *
     * @return $this
     */
    public function setPersistenceFactory(AbstractPersistenceFactory $persistenceFactory)
    {
        // TODO: Implement setPersistenceFactory() method.
    }

    /**
     * @param int $idCompanyUser
     *
     * @return PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        $companyRoleToPermissionEntities = SpyCompanyRoleToPermissionQuery::create()
            ->joinPermission()
            ->joinCompanyRole()
            ->useCompanyRoleQuery()
            ->joinSpyCompanyRoleToCompanyUser()
            ->useSpyCompanyRoleToCompanyUserQuery()
            ->filterByIdCompanyRoleToCompanyUser($idCompanyUser)
            ->endUse()
            ->endUse()
            ->find();

        //mapper
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        foreach ($companyRoleToPermissionEntities as $companyRoleToPermissionEntity) {
            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->setConfiguration($companyRoleToPermissionEntity->getConfiguration());
            $permissionTransfer->setKey($companyRoleToPermissionEntity->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }
}
