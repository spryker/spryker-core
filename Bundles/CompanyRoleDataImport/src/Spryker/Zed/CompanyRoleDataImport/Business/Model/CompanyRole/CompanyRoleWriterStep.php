<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\DataSet\CompanyRoleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyRoleWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @module Company
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idCompany = $this->getIdCompanyByKey($dataSet[CompanyRoleDataSetInterface::COLUMN_COMPANY_KEY]);

        $companyRoleEntity = SpyCompanyRoleQuery::create()
            ->filterByKey($dataSet[CompanyRoleDataSetInterface::COLUMN_COMPANY_ROLE_KEY])
            ->findOneOrCreate();

        $companyRoleEntity
            ->setFkCompany($idCompany)
            ->setName($dataSet[CompanyRoleDataSetInterface::COLUMN_COMPANY_ROLE_NAME])
            ->setIsDefault((bool)$dataSet[CompanyRoleDataSetInterface::COLUMN_IS_DEFAULT])
            ->save();
    }

    /**
     * @param string $companyKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyByKey(string $companyKey): int
    {
        $idCompany = $this->getCompanyQuery()
            ->select(SpyCompanyTableMap::COL_ID_COMPANY)
            ->findOneByKey($companyKey);

        if (!$idCompany) {
            throw new EntityNotFoundException(sprintf('Could not find company by key "%s"', $companyKey));
        }

        return $idCompany;
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function getCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }
}
