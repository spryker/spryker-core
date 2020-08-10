<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\DataSet\BusinessOnBehalfCompanyUserDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class BusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var int[] Keys are business unit keys.
     */
    protected $idBusinessUnitBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $businessUnitKey = $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_BUSINESS_UNIT_KEY];

        $dataSet[BusinessOnBehalfCompanyUserDataSetInterface::COLUMN_ID_BUSINESS_UNIT] = $this->getIdBusinessUnit($businessUnitKey);
    }

    /**
     * @uses \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     *
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

        /** @var int|null $idBusinessUnit */
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
