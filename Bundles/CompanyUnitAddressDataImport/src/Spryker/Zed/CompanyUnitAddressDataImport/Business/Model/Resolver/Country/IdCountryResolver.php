<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country;

use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Exception\CountryNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class IdCountryResolver implements IdCountryResolverInterface
{
    const KEY_COUNTRY_ISO_2_CODE = 'country_iso2_code';
    const KEY_COUNTRY_ISO_3_CODE = 'country_iso3_code';

    /**
     * @var array
     */
    protected $idCountryCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    public function getIdCountry(DataSetInterface $dataSet): int
    {
        $iso2Code = $this->getIso2Code($dataSet);
        $iso3Code = $this->getIso3Code($dataSet);

        $cachedIdCountry = $this->getIdCountryFromCache($iso2Code, $iso3Code);
        if ($cachedIdCountry) {
            return $cachedIdCountry;
        }

        return $this->getIdCountryFromDatabase($iso2Code, $iso3Code);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return null|string
     */
    protected function getIso2Code(DataSetInterface $dataSet)
    {
        if (isset($dataSet[static::KEY_COUNTRY_ISO_2_CODE]) && !empty($dataSet[static::KEY_COUNTRY_ISO_2_CODE])) {
            return $dataSet[static::KEY_COUNTRY_ISO_2_CODE];
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
        if (isset($dataSet[static::KEY_COUNTRY_ISO_3_CODE]) && !empty($dataSet[static::KEY_COUNTRY_ISO_3_CODE])) {
            return $dataSet[static::KEY_COUNTRY_ISO_3_CODE];
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
     * @throws \Spryker\Zed\CompanyUnitAddressDataImport\Exception\CountryNotFoundException
     *
     * @return int
     */
    protected function getIdCountryFromDatabase($iso2Code, $iso3Code): int
    {
        $countryQuery = SpyCountryQuery::create();
        if ($iso2Code !== null) {
            $countryQuery->filterByIso2Code($iso2Code);
        }

        if ($iso3Code !== null) {
            $countryQuery->filterByIso3Code($iso3Code);
        }

        $countryEntity = $countryQuery->findOne();

        if (!$countryEntity) {
            throw new CountryNotFoundException(sprintf('Could not find a country by iso2_code "%s" or iso3_code "%s"!', $iso2Code, $iso3Code));
        }

        $this->addCountryEntityToCache($countryEntity);

        return $countryEntity->getIdCountry();
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     *
     * @return void
     */
    protected function addCountryEntityToCache(SpyCountry $countryEntity): void
    {
        if ($countryEntity->getIso2Code() !== null) {
            $this->idCountryCache[$countryEntity->getIso2Code()] = $countryEntity->getIdCountry();
        }
        if ($countryEntity->getIso3Code() !== null) {
            $this->idCountryCache[$countryEntity->getIso3Code()] = $countryEntity->getIdCountry();
        }
    }
}
