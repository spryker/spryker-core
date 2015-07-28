<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business;

use Generated\Shared\Country\CountryInterface;
use SprykerFeature\Zed\Country\Business\Exception\CountryExistsException;
use SprykerFeature\Zed\Country\Business\Exception\MissingCountryException;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountry;

class CountryManager implements CountryManagerInterface
{

    /**
     * @var CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @param CountryQueryContainerInterface $countryQueryContainer
     */
    public function __construct(
        CountryQueryContainerInterface $countryQueryContainer
    )
    {
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
     * @param string $iso2code
     * @param array $countryData
     *
     * @deprecated
     *
     * @throws CountryExistsException
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
     * @param CountryInterface $countryTransfer
     *
     * @return int
     */
    public function saveCountry(CountryInterface $countryTransfer)
    {
        return $this->createCountry($countryTransfer->getIso2Code(), $countryTransfer->toArray());

    }

    /**
     * @param string $iso2code
     *
     * @throws MissingCountryException
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
     * @throws CountryExistsException
     */
    protected function checkCountryDoesNotExist($iso2code)
    {
        if ($this->hasCountry($iso2code)) {
            throw new CountryExistsException();
        }
    }

}
