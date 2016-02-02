<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Spryker\Zed\Country\Business\CountryFacade;

class SalesToCountryBridge implements SalesToCountryInterface
{

    /**
     * @var CountryFacade
     */
    protected $countryFacade;

    /**
     * SalesToCountryBridge constructor.
     *
     * @param \Spryker\Zed\Country\Business\CountryFacade $countryFacade
     */
    public function __construct($countryFacade)
    {
        $this->countryFacade = $countryFacade;
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
