<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Business;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method CountryBusinessFactory getBusinessFactory()
 */
class CountryFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $messenger
     *
     * @return void
     */
    public function install(LoggerInterface $messenger)
    {
        $this->getBusinessFactory()->createInstaller($messenger)->install();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->getBusinessFactory()->createCountryManager()->getIdCountryByIso2Code($iso2Code);
    }

    /**
     * @return CountryCollectionTransfer
     */
    public function getAvailableCountries()
    {
        $countries = $this->getBusinessFactory()
            ->createCountryManager()
            ->getCountryCollection();

        return $countries;
    }

    /**
     * @param string $countryName
     *
     * @return CountryTransfer
     */
    public function getPreferedCountryByName($countryName)
    {
        $countryTransfer = $this->getBusinessFactory()
            ->createCountryManager()
            ->getPreferedCountryByName($countryName);

        return $countryTransfer;
    }

}
