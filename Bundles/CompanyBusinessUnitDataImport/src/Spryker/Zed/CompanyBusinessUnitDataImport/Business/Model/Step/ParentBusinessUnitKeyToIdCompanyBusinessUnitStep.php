<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ParentBusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCompanyBusinessUnitCache = [];

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

        $idCompanyBusinessUnit = $this->getIdCompanyBusinessUnitByKey($companyBusinessUnitKey);

        $dataSet[CompanyBusinessUnitDataSet::FK_PARENT_BUSINESS_UNIT] = $idCompanyBusinessUnit;
    }

    /**
     * @param string $companyBusinessUnitKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyBusinessUnitByKey(string $companyBusinessUnitKey): int
    {
        if (isset($this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey])) {
            return $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey];
        }

        $idCompanyBusinessUnit = $this->createCompanyBusinessUnitQuery()
            ->filterByKey($companyBusinessUnitKey)
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
            ->findOne();

        if (!$idCompanyBusinessUnit) {
            throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $companyBusinessUnitKey));
        }

        $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey] = $idCompanyBusinessUnit;

        return $this->idCompanyBusinessUnitListCache[$companyBusinessUnitKey];
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function createCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }
}
