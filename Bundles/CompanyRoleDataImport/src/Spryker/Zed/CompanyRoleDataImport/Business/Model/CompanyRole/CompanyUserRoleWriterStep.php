<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet\CompanyUserRoleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserRoleWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @module CompanyUser
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idCompanyRole = $this->getIdCompanyRoleByKey($dataSet[CompanyUserRoleDataSetInterface::COLUMN_COMPANY_ROLE_KEY]);
        $idCompanyUser = $this->getIdCompanyUserByKey($dataSet[CompanyUserRoleDataSetInterface::COLUMN_COMPANY_USER_KEY]);

        $companyUserRoleEntity = SpyCompanyRoleToCompanyUserQuery::create()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOneOrCreate();

        $companyUserRoleEntity->save();
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
     * @param string $companyUserKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUserByKey(string $companyUserKey): int
    {
        $idCompanyUser = $this->getCompanyUserQuery()
            ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
            ->findOneByKey($companyUserKey);

        if (!$idCompanyUser) {
            throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $companyUserKey));
        }

        return $idCompanyUser;
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected function getCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return SpyCompanyRoleQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
