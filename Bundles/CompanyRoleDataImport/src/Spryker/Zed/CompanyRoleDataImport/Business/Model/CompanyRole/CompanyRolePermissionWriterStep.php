<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery;
use Orm\Zed\Permission\Persistence\Map\SpyPermissionTableMap;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet\CompanyRolePermissionDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyRolePermissionWriterStep implements DataImportStepInterface
{
    /**
     * @module CompanyRole
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idPermission = $this->getIdPermissionByKey($dataSet[CompanyRolePermissionDataSetInterface::COLUMN_PERMISSION_KEY]);
        if ($idPermission === null) {
            return;
        }

        $idCompanyRole = $this->getIdCompanyRoleByKey($dataSet[CompanyRolePermissionDataSetInterface::COLUMN_COMPANY_ROLE_KEY]);

        $companyRolePermissionEntity = SpyCompanyRoleToPermissionQuery::create()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkPermission($idPermission)
            ->findOneOrCreate();

        $companyRolePermissionEntity
            ->setConfiguration($dataSet[CompanyRolePermissionDataSetInterface::COLUMN_CONFIGURATION])
            ->save();
    }

    /**
     * @param string $permissionKey
     *
     * @return int|null
     */
    protected function getIdPermissionByKey(string $permissionKey): ?int
    {
        $idCompanyRole = $this->getPermissionQuery()
            ->select(SpyPermissionTableMap::COL_ID_PERMISSION)
            ->findOneByKey($permissionKey);

        if (!$idCompanyRole) {
            return null;
        }

        return $idCompanyRole;
    }

    /**
     * @param string $companyRoleKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyRoleByKey(string $companyRoleKey): int
    {
        $idCompanyRole = $this->getCompanyRoleQuery()
            ->select(SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE)
            ->findOneByKey($companyRoleKey);

        if (!$idCompanyRole) {
            throw new EntityNotFoundException(sprintf('Could not find company role by key "%s"', $companyRoleKey));
        }

        return $idCompanyRole;
    }

    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    protected function getPermissionQuery(): SpyPermissionQuery
    {
        return SpyPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected function getCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return SpyCompanyRoleQuery::create();
    }
}
