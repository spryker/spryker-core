<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\CountryFacade;

class CustomerToCountryBridge implements CustomerToCountryInterface
{

    /**
     * @var CountryFacade
     */
    protected $countryFacade;

    /**
     * CustomerToCountryBridge constructor.
     *
     * @param CountryFacade $countryFacade
     */
    public function __construct($countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param string$countryName
     *
     * @return CountryTransfer
     */
    public function getPreferredCountryByName($countryName)
    {
        return $this->countryFacade->getPreferredCountryByName($countryName);
    }

    /**
     * @return CountryCollectionTransfer
     */
    public function getAvailableCountries()
    {
        return $this->countryFacade->getAvailableCountries();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->countryFacade->getIdCountryByIso2Code($iso2Code);
    }

}
