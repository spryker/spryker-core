<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToPermissionQuery;
use Orm\Zed\Permission\Persistence\Map\SpyPermissionTableMap;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet\CompanyRolePermissionDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyRolePermissionWriterStep extends AbstractCompanyRoleWriterStep implements DataImportStepInterface
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
            ->filterByKey($permissionKey)
            ->select(SpyPermissionTableMap::COL_ID_PERMISSION)
            ->findOne();

        if (!$idCompanyRole) {
            return null;
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
}
