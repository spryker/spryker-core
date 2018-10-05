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
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyRolePermissionWriterStep extends AbstractCompanyRoleWriterStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idPermissionListCache = [];

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
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdPermissionByKey(string $permissionKey): int
    {
        if (!isset($this->idPermissionListCache[$permissionKey])) {
            $idPermission = $this->getPermissionQuery()
                ->filterByKey($permissionKey)
                ->select(SpyPermissionTableMap::COL_ID_PERMISSION)
                ->findOne();

            if (!$idPermission) {
                throw new EntityNotFoundException(sprintf('Could not find permission by key "%s"', $permissionKey));
            }

            $this->idPermissionListCache[$permissionKey] = $idPermission;
        }

        return $this->idPermissionListCache[$permissionKey];
    }

    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    protected function getPermissionQuery(): SpyPermissionQuery
    {
        return SpyPermissionQuery::create();
    }
}
