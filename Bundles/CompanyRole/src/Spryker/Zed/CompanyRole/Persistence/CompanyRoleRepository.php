<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory getFactory()
 */
class CompanyRoleRepository extends AbstractRepository implements CompanyRoleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        $companyRoleTransfer->requireIdCompanyRole();
        $query = $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByIdCompanyRole($companyRoleTransfer->getIdCompanyRole());

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapEntityTransferToCompanyRoleTransfer($entityTransfer, $companyRoleTransfer);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        $query = $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->joinWithPermission()
            ->joinCompanyRole()
            ->useCompanyRoleQuery()
                ->joinSpyCompanyRoleToCompanyUser()
                    ->useSpyCompanyRoleToCompanyUserQuery()
                        ->filterByFkCompanyUser($idCompanyUser)
                    ->endUse()
            ->endUse();

        $companyRoleToPermissionEntities = $this->buildQueryFromCriteria($query)->find();

        //mapper
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        foreach ($companyRoleToPermissionEntities as $companyRoleToPermissionEntity) {
            $permissionTransfer = new PermissionTransfer();

            $configuration = \json_decode($companyRoleToPermissionEntity->getConfiguration());
            if (json_last_error() === \JSON_ERROR_NONE) {
                $configuration = [];
            }

            $permissionTransfer->setConfiguration($configuration);
            $permissionTransfer->setKey($companyRoleToPermissionEntity->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRole(): CompanyRoleCollectionTransfer
    {
        $query = SpyCompanyRoleQuery::create()
            ->joinWithSpyCompanyRoleToPermission()
            ->useSpyCompanyRoleToPermissionQuery()
                ->joinWithPermission()
            ->endUse();

        $companyRoleEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

        /** @var \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer */
        foreach ($companyRoleEntityTransfers as $companyRoleEntityTransfer) {
            $companyRoleTransfer = (new CompanyRolePersistenceFactory)
                ->createCompanyRoleMapper()
                ->mapEntityTransferToCompanyRoleTransfer(
                    $companyRoleEntityTransfer,
                    new CompanyRoleTransfer()
                );

            $companyRoleTransfer = (new CompanyRolePersistenceFactory())
                ->createCompanyRolePermissionMapper()
                ->hydratePermissionCollection(
                    $companyRoleEntityTransfer,
                    $companyRoleTransfer
                );

            $companyRoleCollectionTransfer->addRole($companyRoleTransfer);
        }

        return $companyRoleCollectionTransfer;
    }

    /**
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(int $idCompanyRole): PermissionCollectionTransfer
    {
        $query = $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->filterByFkCompanyRole($idCompanyRole)
            ->joinWithPermission();

        $companyRoleToPermissionEntities = $this->buildQueryFromCriteria($query)->find();

        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($companyRoleToPermissionEntities as $roleToPermissionEntity) {
            $permissionTransfer = (new PermissionTransfer())
                ->setIdPermission($roleToPermissionEntity->getFkPermission())
                ->setConfiguration(\json_decode($roleToPermissionEntity->getConfiguration(), true))
                ->setKey($roleToPermissionEntity->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        $companyRoleCriteriaFilterTransfer->requireIdCompany();

        $query = $this->getFactory()
            ->createCompanyRoleQuery();

        if ($companyRoleCriteriaFilterTransfer->getIdCompany() !== null) {
            $query->filterByFkCompany($companyRoleCriteriaFilterTransfer->getIdCompany());
        }

        if ($companyRoleCriteriaFilterTransfer->getIdCompanyUser() !== null) {
            $query->useSpyCompanyRoleToCompanyUserQuery()
                ->filterByFkCompanyUser($companyRoleCriteriaFilterTransfer->getIdCompanyUser())
                ->endUse();
        }

        $collection = $this->buildQueryFromCriteria($query, $companyRoleCriteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $companyRoleCriteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyRoleCollectionTransfer();
        foreach ($collection as $companyRoleEntity) {
            $companyRoleTransfer = $this->getFactory()
                ->createCompanyRoleMapper()
                ->mapEntityTransferToCompanyRoleTransfer(
                    $companyRoleEntity,
                    new CompanyRoleTransfer()
                );

            $companyRoleTransfer = $this->getFactory()
                ->createCompanyRolePermissionMapper()
                ->hydratePermissionCollection(
                    $companyRoleEntity,
                    $companyRoleTransfer
                );

            $collectionTransfer->addRole($companyRoleTransfer);
        }

        $collectionTransfer->setPagination($companyRoleCriteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }
}
