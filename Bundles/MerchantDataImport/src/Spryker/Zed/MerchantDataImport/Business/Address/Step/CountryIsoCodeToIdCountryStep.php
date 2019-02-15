<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Address\Step;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Address\DataSet\MerchantAddressDataSetInterface;

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
    public function execute(DataSetInterface $dataSet): void
    {
        $iso2Code = $this->findIso2Code($dataSet);
        $iso3Code = $this->findIso3Code($dataSet);

        $idCountry = $this->findIdCountryFromCache($iso2Code, $iso3Code);
        if (!$idCountry) {
            $idCountry = $this->getIdCountryFromDatabase($iso2Code, $iso3Code);
        }

        $dataSet[MerchantAddressDataSetInterface::ID_COUNTRY] = $idCountry;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string|null
     */
    protected function findIso2Code(DataSetInterface $dataSet): ?string
    {
        if (!empty($dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE])) {
            return $dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE];
        }

        return null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string|null
     */
    protected function findIso3Code(DataSetInterface $dataSet): ?string
    {
        if (!empty($dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE])) {
            return $dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE];
        }

        return null;
    }

    /**
     * @param string|null $iso2Code
     * @param string|null $iso3Code
     *
     * @return int|null
     */
    protected function findIdCountryFromCache(?string $iso2Code, ?string $iso3Code): ?int
    {
        if ($iso2Code !== null && isset($this->idCountryCache[$iso2Code])) {
            return $this->idCountryCache[$iso2Code];
        }

        if ($iso3Code !== null && isset($this->idCountryCache[$iso3Code])) {
            return $this->idCountryCache[$iso3Code];
        }

        return null;
    }

    /**
     * @param string|null $iso2Code
     * @param string|null $iso3Code
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCountryFromDatabase(?string $iso2Code, ?string $iso3Code): int
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
    protected function addIdCountryToCache(int $idCountry, ?string $iso2Code, ?string $iso3Code): void
    {
        if ($iso2Code !== null) {
            $this->idCountryCache[$iso2Code] = $idCountry;
        }

        if ($iso3Code !== null) {
            $this->idCountryCache[$iso3Code] = $idCountry;
        }
    }
}
