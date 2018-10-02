<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step;

use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitDataSet;
use Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportRepositoryInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ParentBusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
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
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyBusinessUnitKey = $dataSet[CompanyBusinessUnitDataSet::PARENT_BUSINESS_UNIT_KEY];
        if (!$companyBusinessUnitKey) {
            return;
        }

        $dataSet[CompanyBusinessUnitDataSet::FK_PARENT_BUSINESS_UNIT] = $this->businessUnitDataImportRepository
            ->getIdCompanyBusinessUnitByKey($companyBusinessUnitKey);
    }
}
