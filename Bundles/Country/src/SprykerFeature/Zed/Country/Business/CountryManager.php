<?php

namespace SprykerFeature\Zed\Country\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Country\Business\Exception\CountryExistsException;
use SprykerFeature\Zed\Country\Business\Exception\MissingCountryException;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;

class CountryManager implements CountryManagerInterface
{
    /**
     * @var CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param CountryQueryContainerInterface $countryQueryContainer
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CountryQueryContainerInterface $countryQueryContainer,
        LocatorLocatorInterface $locator
    ) {
        $this->countryQueryContainer = $countryQueryContainer;
        $this->locator = $locator;
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
     * @return int
     * @throws CountryExistsException
     */
    public function createCountry($iso2code, array $countryData)
    {
        $this->checkCountryDoesNotExist($iso2code);

        $country = $this->locator->country()->entitySpyCountry();
        $country
            ->setName($countryData['name'])
            ->setPostalCodeMandatory($countryData['postal_code_mandatory'])
            ->setPostalCodeRegex($countryData['postal_code_regex'])
            ->setIso2Code($iso2code)
            ->setIso3Code($countryData['iso3_code'])
        ;

        $country->save();

        return $country->getIdCountry();
    }

    /**
     * @param string $iso2code
     *
     * @return int
     * @throws MissingCountryException
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
