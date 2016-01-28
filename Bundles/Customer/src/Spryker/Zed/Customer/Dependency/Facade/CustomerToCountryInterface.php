<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;

interface CustomerToCountryInterface
{

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code);

    /**
     * @param string$countryName
     *
     * @return CountryTransfer
     */
    public function getPreferredCountryByName($countryName);

    /**
     * @return CountryCollectionTransfer
     */
    public function getAvailableCountries();

}
