<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitUserDataSet;
use Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportRepositoryInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyBusinessUnitUserWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportRepositoryInterface
     */
    protected $businessUnitDataImportRepository;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportRepositoryInterface $businessUnitDataImportRepository
     */
    public function __construct(CompanyBusinessUnitDataImportRepositoryInterface $businessUnitDataImportRepository)
    {
        $this->businessUnitDataImportRepository = $businessUnitDataImportRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idCompanyBusinessUnit = $this->businessUnitDataImportRepository->getIdCompanyBusinessUnitByKey($dataSet[CompanyBusinessUnitUserDataSet::COLUMN_BUSINESS_UNIT_KEY]);

        $companyUserEntity = $this->createCompanyUserQuery()
            ->findOneByKey($dataSet[CompanyBusinessUnitUserDataSet::COLUMN_COMPANY_USER_KEY]);

        if ($companyUserEntity === null) {
            throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $dataSet[CompanyBusinessUnitUserDataSet::COLUMN_COMPANY_USER_KEY]));
        }

        $companyUserEntity
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->save();
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function createCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
