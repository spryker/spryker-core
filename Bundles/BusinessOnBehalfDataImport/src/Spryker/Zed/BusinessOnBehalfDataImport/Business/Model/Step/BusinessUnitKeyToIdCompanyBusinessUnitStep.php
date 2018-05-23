<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class BusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idBusinessUnitBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $businessUnitKey = $dataSet[BusinessOnBehalfCompanyUserDataSet::BUSINESS_UNIT_KEY];

        $dataSet[BusinessOnBehalfCompanyUserDataSet::ID_BUSINESS_UNIT] = $this->getIdBusinessUnit($businessUnitKey);
    }

    /**
     * @param string $businessUnitKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdBusinessUnit(string $businessUnitKey): int
    {
        if (isset($this->idBusinessUnitBuffer[$businessUnitKey])) {
            return $this->idBusinessUnitBuffer[$businessUnitKey];
        }

        $idBusinessUnit = SpyCompanyBusinessUnitQuery::create()
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
            ->findOneByKey($businessUnitKey);

        if (!$idBusinessUnit) {
            throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $businessUnitKey));
        }

        $this->idBusinessUnitBuffer[$businessUnitKey] = $idBusinessUnit;

        return $this->idBusinessUnitBuffer[$businessUnitKey];
    }
}
