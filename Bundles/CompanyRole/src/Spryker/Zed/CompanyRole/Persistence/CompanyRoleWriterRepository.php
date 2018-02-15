<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class CompanyRoleWriterRepository implements CompanyRoleWriterRepositoryInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface
     */
    protected $companyRoleQueryContainer;

    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory
     */
    protected $persistenceFactory;

    /**
     * todo: split into create and update methods.
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function save(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        $companyRoleEntity = $this->companyRoleQueryContainer
            ->queryCompanyRole()
            ->filterByIdCompanyRole($companyRoleTransfer->getIdCompanyRole())
            ->findOneOrCreate();

        $companyRoleEntity = $this->persistenceFactory
            ->createCompanyRoleMapper()
            ->mapTransferToCompanyRoleEntity(
                $companyRoleTransfer,
                $companyRoleEntity
            );

        $this->cleanupDefaultRoles($companyRoleTransfer);
        $companyRoleEntity->save();
        $companyRoleTransfer = $this->persistenceFactory
            ->createCompanyRoleMapper()
            ->mapCompanyRoleEntityToTransfer(
                $companyRoleEntity,
                $companyRoleTransfer
            );

        $this->saveCompanyRolePermissions($companyRoleTransfer);

        return $companyRoleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    protected function cleanupDefaultRoles(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $isDefault = $companyRoleTransfer->getIsDefault();

        if ($isDefault === true) {
            $query = $this->companyRoleQueryContainer->queryCompanyRole();
            if ($companyRoleTransfer->getIdCompanyRole() !== null) {
                $query->filterByIdCompanyRole($companyRoleTransfer->getIdCompanyRole(), Criteria::NOT_EQUAL);
            }

            $query->update(['IsDefault' => false]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function saveCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $permissions = [];

        if ($companyRoleTransfer->getPermissionCollection()) {
            $permissions = $companyRoleTransfer->getPermissionCollection()->getPermissions();
        }

        $assignedIdPermissions = [];

        foreach ($permissions as $permissionTransfer) {
            $this->saveCompanyRolePermission($companyRoleTransfer->getIdCompanyRole(), $permissionTransfer);
            $assignedIdPermissions[] = $permissionTransfer->getIdPermission();
        }

        $this->companyRoleQueryContainer
            ->queryCompanyRoleToPermission()
            ->filterByFkCompanyRole($companyRoleTransfer->getIdCompanyRole())
            ->filterByFkPermission($assignedIdPermissions, Criteria::NOT_IN)
            ->delete();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function delete(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $companyRoleTransfer->requireIdCompanyRole();
        $this->companyRoleQueryContainer
            ->queryCompanyRole()
            ->filterByIdCompanyRole($companyRoleTransfer->getIdCompanyRole())
            ->delete();
    }

    /**
     * @param int $idCompanyRole
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    protected function saveCompanyRolePermission(int $idCompanyRole, PermissionTransfer $permissionTransfer): void
    {
        $spyCompanyRoleToPermission = $this->companyRoleQueryContainer
            ->queryCompanyRoleToPermission()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->findOneOrCreate();

        $spyCompanyRoleToPermission->setConfiguration(\json_encode($permissionTransfer->getConfiguration()));
        $spyCompanyRoleToPermission->save();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleQueryContainerInterface $companyRoleQueryContainer
     *
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepository
     */
    public function setQueryContainer(AbstractQueryContainer $companyRoleQueryContainer): CompanyRoleWriterRepository
    {
        $this->companyRoleQueryContainer = $companyRoleQueryContainer;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory $persistenceFactory
     *
     * @return \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepository
     */
    public function setPersistenceFactory(AbstractPersistenceFactory $persistenceFactory): CompanyRoleWriterRepository
    {
        $this->persistenceFactory = $persistenceFactory;

        return $this;
    }
}
