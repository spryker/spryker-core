<?php

namespace SprykerFeature\Zed\Country\Business;

use SprykerFeature\Zed\Country\Business\Exception\MissingCountryException;

interface CountryManagerInterface
{
    /**
     * @param string $iso2code
     *
     * @return bool
     */
    public function hasCountry($iso2code);

    /**
     * @param string $iso2code
     * @param array $countryData
     *
     * @return int
     */
    public function createCountry($iso2code, array $countryData);

    /**
     * @param string $iso2code
     *
     * @return int
     * @throws MissingCountryException
     */
    public function getIdCountryByIso2Code($iso2code);
}
