<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Spryker\Zed\Country\Business\Exception\CountryExistsException;
use Spryker\Zed\Country\Business\Exception\MissingCountryException;
use Spryker\Zed\Country\Persistence\CountryQueryContainerInterface;

class CountryManager implements CountryManagerInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface $countryQueryContainer
     */
    public function __construct(
        CountryQueryContainerInterface $countryQueryContainer
    ) {
        $this->countryQueryContainer = $countryQueryContainer;
    }

    /**
     * @param string $iso2code
     *
     * @return bool
     */
    public function hasCountry($iso2code)
    {
        $query = $this->countryQueryContainer->queryCountryByIso2Code($iso2code);

        return $query->count() > 0;
    }

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection()
    {
        $countries = $this->countryQueryContainer->queryCountries()
            ->orderByName()
            ->find();

        $countryCollectionTransfer = new CountryCollectionTransfer();

        foreach ($countries as $country) {
            $countryTransfer = (new CountryTransfer())->fromArray($country->toArray(), true);
            $countryCollectionTransfer->addCountries($countryTransfer);
        }

        return $countryCollectionTransfer;
    }

    /**
     * @deprecated Use getPreferredCountryByName()
     *
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferedCountryByName($countryName)
    {
        return $this->getPreferredCountryByName($countryName);
    }

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName($countryName)
    {
        $country = $this->countryQueryContainer->queryCountries()->findOneByName($countryName);

        if ($country === null) {
            return new CountryTransfer();
        }

        $countryTransfer = (new CountryTransfer())->fromArray($country->toArray(), true);

        return $countryTransfer;
    }

    /**
     * @deprecated Use {@link saveCountry()} instead.
     *
     * @param string $iso2code
     * @param array $countryData
     *
     * @return int
     */
    public function createCountry($iso2code, array $countryData)
    {
        $this->checkCountryDoesNotExist($iso2code);

        $country = new SpyCountry();
        $country
            ->setName($countryData['name'])
            ->setPostalCodeMandatory($countryData['postal_code_mandatory'])
            ->setPostalCodeRegex($countryData['postal_code_regex'])
            ->setIso2Code($iso2code)
            ->setIso3Code($countryData['iso3_code']);

        $country->save();

        return $country->getIdCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return int
     */
    public function saveCountry(CountryTransfer $countryTransfer)
    {
        return $this->createCountry($countryTransfer->getIso2Code(), $countryTransfer->toArray());
    }

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2code)
    {
        $query = $this->countryQueryContainer->queryCountryByIso2Code($iso2code);
        $country = $query->findOne();

        if (!$country) {
            throw new MissingCountryException();
        }

        return $country->getIdCountry();
    }

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code($iso2code)
    {
        $query = $this->countryQueryContainer->queryCountryByIso2Code($iso2code);
        $countryEntity = $query->findOne();

        if (!$countryEntity) {
            throw new MissingCountryException(sprintf('Country not found for country code: %s', $iso2code));
        }

        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);

        return $countryTransfer;
    }

    /**
     * @param string $iso3code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code($iso3code)
    {
        $query = $this->countryQueryContainer->queryCountryByIso3Code($iso3code);
        $countryEntity = $query->findOne();

        if (!$countryEntity) {
            throw new MissingCountryException(sprintf('Country not found for country code: %s', $iso3code));
        }

        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);

        return $countryTransfer;
    }

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\CountryExistsException
     *
     * @return void
     */
    protected function checkCountryDoesNotExist($iso2code)
    {
        if ($this->hasCountry($iso2code)) {
            throw new CountryExistsException();
        }
    }
}
