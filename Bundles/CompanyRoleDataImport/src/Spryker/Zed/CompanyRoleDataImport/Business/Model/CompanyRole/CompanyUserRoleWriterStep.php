<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet\CompanyUserRoleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUserRoleWriterStep extends AbstractCompanyRoleWriterStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyUserListCache = [];

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
     * @param string $companyUserKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUserByKey(string $companyUserKey): int
    {
        if (!isset($this->idCompanyUserListCache[$companyUserKey])) {
            $idCompanyUser = $this->getCompanyUserQuery()
                ->filterByKey($companyUserKey)
                ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
                ->findOne();

            if (!$idCompanyUser) {
                throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $companyUserKey));
            }

            $this->idCompanyUserListCache[$companyUserKey] = $idCompanyUser;
        }

        return $this->idCompanyUserListCache[$companyUserKey];
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
