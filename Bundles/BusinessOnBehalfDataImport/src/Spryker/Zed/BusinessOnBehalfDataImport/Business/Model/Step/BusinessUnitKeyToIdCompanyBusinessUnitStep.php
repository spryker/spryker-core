<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class BusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idBusinessUnitCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $businessUnitKey = $dataSet[BusinessOnBehalfDataSet::BUSINESS_UNIT_KEY];
        if (!isset($this->idBusinessUnitCache[$businessUnitKey])) {
            $businessUnitQuery = SpyCompanyBusinessUnitQuery::create();
            $idBusinessUnit = $businessUnitQuery
                ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
                ->findOneByKey($businessUnitKey);

            if (!$idBusinessUnit) {
                throw new EntityNotFoundException(sprintf('Could not find company business unit by key "%s"', $businessUnitKey));
            }

            $this->idBusinessUnitCache[$businessUnitKey] = $idBusinessUnit;
        }

        $dataSet[BusinessOnBehalfDataSet::ID_BUSINESS_UNIT] = $this->idBusinessUnitCache[$businessUnitKey];
    }
}
