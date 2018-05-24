<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\DataSet\CompanyUnitAddressDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CountryIsoCodeToIdCountryStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCountryCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $iso2Code = $this->getIso2Code($dataSet);
        $iso3Code = $this->getIso3Code($dataSet);

        $idCountry = $this->getIdCountryFromCache($iso2Code, $iso3Code);
        if (!$idCountry) {
            $idCountry = $this->getIdCountryFromDatabase($iso2Code, $iso3Code);
        }

        $dataSet[CompanyUnitAddressDataSet::ID_COUNTRY] = $idCountry;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return null|string
     */
    protected function getIso2Code(DataSetInterface $dataSet)
    {
        if (isset($dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_2_CODE]) && !empty($dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_2_CODE])) {
            return $dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_2_CODE];
        }

        return null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return null|string
     */
    protected function getIso3Code(DataSetInterface $dataSet)
    {
        if (isset($dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_3_CODE]) && !empty($dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_3_CODE])) {
            return $dataSet[CompanyUnitAddressDataSet::COUNTRY_ISO_3_CODE];
        }

        return null;
    }

    /**
     * @param string|null $iso2Code
     * @param string|null $iso3Code
     *
     * @return bool|int
     */
    protected function getIdCountryFromCache($iso2Code, $iso3Code)
    {
        if ($iso2Code !== null && isset($this->idCountryCache[$iso2Code])) {
            return $this->idCountryCache[$iso2Code];
        }

        if ($iso3Code !== null && isset($this->idCountryCache[$iso3Code])) {
            return $this->idCountryCache[$iso3Code];
        }

        return false;
    }

    /**
     * @param string|null $iso2Code
     * @param string|null $iso3Code
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCountryFromDatabase($iso2Code, $iso3Code): int
    {
        $countryQuery = SpyCountryQuery::create();
        $countryQuery->select(SpyCountryTableMap::COL_ID_COUNTRY);

        if ($iso2Code !== null) {
            $countryQuery->filterByIso2Code($iso2Code);
        }

        if ($iso3Code !== null) {
            $countryQuery->filterByIso3Code($iso3Code);
        }

        /** @var int $idCountry */
        $idCountry = $countryQuery->findOne();

        if (!$idCountry) {
            throw new EntityNotFoundException(sprintf('Could not find a country by iso2_code "%s" or iso3_code "%s"!', $iso2Code, $iso3Code));
        }

        $this->addIdCountryToCache($idCountry, $iso2Code, $iso3Code);

        return $idCountry;
    }

    /**
     * @param int $idCountry
     * @param string|null $iso2Code
     * @param string|null $iso3Code
     *
     * @return void
     */
    protected function addIdCountryToCache($idCountry, $iso2Code, $iso3Code): void
    {
        if ($iso2Code !== null) {
            $this->idCountryCache[$iso2Code] = $idCountry;
        }
        if ($iso3Code !== null) {
            $this->idCountryCache[$iso3Code] = $idCountry;
        }
    }
}
