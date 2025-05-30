<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleToCompanyUserTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory getFactory()
 */
class CompanyRoleRepository extends AbstractRepository implements CompanyRoleRepositoryInterface
{
    /**
     * @var array<string, \Generated\Shared\Transfer\CompanyRoleCollectionTransfer>
     */
    protected static array $companyRoleCollectionCache = [];

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

        $spyCompanyRole = $this->buildQueryFromCriteria($query)->findOne();

        return $this->prepareCompanyRoleTransfer($spyCompanyRole);
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

        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        foreach ($companyRoleToPermissionEntities as $companyRoleToPermissionEntity) {
            $permissionTransfer = new PermissionTransfer();

            $permissionTransfer->setKey($companyRoleToPermissionEntity->getPermission()->getKey());

            $permissionTransfer->setConfigurationSignature(
                $this->jsonDecode($companyRoleToPermissionEntity->getPermission()->getConfigurationSignature()),
            );

            $permissionTransfer->setConfiguration(
                $this->jsonDecode($companyRoleToPermissionEntity->getConfiguration()),
            );

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionsByIdCompanyRoleByIdPermission(int $idCompanyRole, int $idPermission): PermissionTransfer
    {
        $query = $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkPermission($idPermission)
            ->joinWithPermission();

        $companyRoleToPermissionEntity = $this->buildQueryFromCriteria($query)->findOne();

        $permissionTransfer = new PermissionTransfer();

        if (!$companyRoleToPermissionEntity) {
            return $permissionTransfer;
        }

        $permissionTransfer->setKey($companyRoleToPermissionEntity->getPermission()->getKey());
        $permissionTransfer->setIdPermission($companyRoleToPermissionEntity->getFkPermission());
        $permissionTransfer->setIdCompanyRole($companyRoleToPermissionEntity->getFkCompanyRole());

        $permissionTransfer->setConfigurationSignature(
            $this->jsonDecode($companyRoleToPermissionEntity->getPermission()->getConfigurationSignature()),
        );

        $permissionTransfer->setConfiguration(
            $this->jsonDecode($companyRoleToPermissionEntity->getConfiguration()),
        );

        return $permissionTransfer;
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    protected function jsonDecode($value)
    {
        $decodedValue = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decodedValue;
        }

        return [];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRole(): CompanyRoleCollectionTransfer
    {
        $query = $this->getFactory()
            ->createCompanyRoleQuery()
            ->joinWithSpyCompanyRoleToPermission()
                ->useSpyCompanyRoleToPermissionQuery()
                ->joinWithPermission()
            ->endUse();

        $companyRoleEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

        foreach ($companyRoleEntityTransfers as $spyCompanyRole) {
            $companyRoleTransfer = $this->prepareCompanyRoleTransfer($spyCompanyRole);
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
                ->setConfiguration($this->jsonDecode($roleToPermissionEntity->getConfiguration()))
                ->setConfigurationSignature($this->jsonDecode($roleToPermissionEntity->getPermission()->getConfigurationSignature()))
                ->setKey($roleToPermissionEntity->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @module Permission
     *
     * @param string $permissionKey
     * @param int|null $idCompany
     *
     * @return array<int>
     */
    public function getCompanyUserIdsByPermissionKey(string $permissionKey, ?int $idCompany = null): array
    {
        $companyRoleQuery = $this->getFactory()->createCompanyRoleQuery();

        if ($idCompany) {
            $companyRoleQuery->filterByFkCompany($idCompany);
        }

        /** @var \Propel\Runtime\Collection\ArrayCollection $companyUserIds */
        $companyUserIds = $companyRoleQuery->joinSpyCompanyRoleToCompanyUser()
            ->useSpyCompanyRoleToPermissionQuery()
                ->usePermissionQuery()
                    ->filterByKey($permissionKey)
                ->endUse()
            ->endUse()
            ->select([SpyCompanyRoleToCompanyUserTableMap::COL_FK_COMPANY_USER])
            ->find();

         return $companyUserIds->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        $hash = md5($this->recursiveImplode($companyRoleCriteriaFilterTransfer->toArray(), ','));
        if (isset(static::$companyRoleCollectionCache[$hash])) {
            return static::$companyRoleCollectionCache[$hash];
        }

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
        /** @var array<\Orm\Zed\CompanyRole\Persistence\SpyCompanyRole> $spyCompanyRoleCollection */
        $spyCompanyRoleCollection = $this->getPaginatedCollection($collection, $companyRoleCriteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyRoleCollectionTransfer();
        foreach ($spyCompanyRoleCollection as $spyCompanyRole) {
            $companyRoleTransfer = $this->prepareCompanyRoleTransfer($spyCompanyRole);
            $collectionTransfer->addRole($companyRoleTransfer);
        }

        $collectionTransfer->setPagination($companyRoleCriteriaFilterTransfer->getPagination());

        static::$companyRoleCollectionCache[$hash] = $collectionTransfer;

        return $collectionTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $modelCriteria, ?FilterTransfer $filterTransfer = null): ModelCriteria
    {
        $modelCriteria = parent::buildQueryFromCriteria($modelCriteria, $filterTransfer);

        $modelCriteria->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection|array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
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

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $spyCompanyRole
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected function prepareCompanyRoleTransfer(SpyCompanyRole $spyCompanyRole): CompanyRoleTransfer
    {
        $companyRoleTransfer = $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapEntityToCompanyRoleTransfer(
                $spyCompanyRole,
                new CompanyRoleTransfer(),
            );

        $companyRoleTransfer = $this->getFactory()
            ->createCompanyRolePermissionMapper()
            ->hydratePermissionCollection(
                $spyCompanyRole,
                $companyRoleTransfer,
            );

        $companyRoleTransfer = $this->getFactory()
            ->createCompanyRoleCompanyUserMapper()
            ->hydrateCompanyUserCollection(
                $spyCompanyRole,
                $companyRoleTransfer,
            );

        $companyRoleTransfer = $this->getFactory()
            ->createCompanyRoleCompanyMapper()
            ->mapCompanyFromCompanyRoleEntityToCompanyRoleTransfer(
                $spyCompanyRole,
                $companyRoleTransfer,
            );

        return $companyRoleTransfer;
    }

    /**
     * @deprecated Use {@link findDefaultCompanyRoleByIdCompany()} instead.
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getDefaultCompanyRole(): CompanyRoleTransfer
    {
        $query = $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByIsDefault(true);

        $spyCompanyRole = $this->buildQueryFromCriteria($query)->findOne();

        return $this->prepareCompanyRoleTransfer($spyCompanyRole);
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    public function findDefaultCompanyRoleByIdCompany(int $idCompany): ?CompanyRoleTransfer
    {
        $companyRoleEntity = $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByFkCompany($idCompany)
            ->filterByIsDefault(true)
            ->findOne();

        if (!$companyRoleEntity) {
            return null;
        }

        return $this->prepareCompanyRoleTransfer($companyRoleEntity);
    }

    /**
     * @param int $idCompanyRole
     *
     * @return bool
     */
    public function hasUsers(int $idCompanyRole): bool
    {
        $spyCompanyRoleToCompanyUser = $this->getFactory()
            ->createCompanyRoleToCompanyUserQuery()
            ->filterByFkCompanyRole($idCompanyRole)
            ->findOne();

        return ($spyCompanyRoleToCompanyUser !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    public function findCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): ?CompanyRoleTransfer
    {
        $companyRoleTransfer->requireIdCompanyRole();

        $companyRoleEntity = $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByIdCompanyRole($companyRoleTransfer->getIdCompanyRole())
            ->findOne();

        if (!$companyRoleEntity) {
            return null;
        }

        return $this->prepareCompanyRoleTransfer($companyRoleEntity);
    }

    /**
     * @module Company
     *
     * @param string $companyRoleUuid
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    public function findCompanyRoleByUuid(string $companyRoleUuid): ?CompanyRoleTransfer
    {
        $companyRoleEntity = $this->getFactory()
            ->createCompanyRoleQuery()
            ->joinCompany()
            ->filterByUuid($companyRoleUuid)
            ->findOne();

        if (!$companyRoleEntity) {
            return null;
        }

        return $this->prepareCompanyRoleTransfer($companyRoleEntity);
    }

    /**
     * @param array $array
     * @param string $glue
     *
     * @return string
     */
    protected function recursiveImplode(array $array, string $glue = ','): string
    {
        $result = [];

        foreach ($array as $value) {
            if (is_array($value)) {
                $result[] = $this->recursiveImplode($value, $glue);
            } else {
                $result[] = (string)$value;
            }
        }

        return implode($glue, $result);
    }
}
