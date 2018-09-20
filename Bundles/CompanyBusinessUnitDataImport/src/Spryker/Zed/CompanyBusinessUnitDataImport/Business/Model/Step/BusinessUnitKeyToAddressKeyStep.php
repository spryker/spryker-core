<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet\CompanyBusinessUnitAddressDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class BusinessUnitKeyToAddressKeyStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCompanyBusinessUnitListCache = [];

    /**
     * @var int[]
     */
    protected $idCompanyUnitAddressListCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idCompanyBusinessUnit = $this->getIdCompanyBusinessUnitByKey($dataSet[CompanyBusinessUnitAddressDataSet::COLUMN_BUSINESS_UNIT_KEY]);
        $idCompanyUnitAddress = $this->getIdCompanyUnitAddressByKey($dataSet[CompanyBusinessUnitAddressDataSet::COLUMN_ADDRESS_KEY]);

        $this->createCompanyUnitAddressToCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFKCompanyUnitAddress($idCompanyUnitAddress)
            ->findOneOrCreate()
            ->save();
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
     * @param string $companyAddressKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUnitAddressByKey(string $companyAddressKey): int
    {
        if (isset($this->idCompanyUnitAddressListCache[$companyAddressKey])) {
            return $this->idCompanyUnitAddressListCache[$companyAddressKey];
        }

        $idCompanyUnitAddress = $this->createCompanyUnitAddressQuery()
            ->filterByKey($companyAddressKey)
            ->select(SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS)
            ->findOne();

        if (!$idCompanyUnitAddress) {
            throw new EntityNotFoundException(sprintf('Could not find company address by key "%s"', $companyAddressKey));
        }

        $this->idCompanyUnitAddressListCache[$companyAddressKey] = $idCompanyUnitAddress;

        return $this->idCompanyUnitAddressListCache[$companyAddressKey];
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function createCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function createCompanyUnitAddressQuery(): SpyCompanyUnitAddressQuery
    {
        return SpyCompanyUnitAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressToCompanyBusinessUnitQuery
     */
    protected function createCompanyUnitAddressToCompanyBusinessUnitQuery(): SpyCompanyUnitAddressToCompanyBusinessUnitQuery
    {
        return SpyCompanyUnitAddressToCompanyBusinessUnitQuery::create();
    }
}
