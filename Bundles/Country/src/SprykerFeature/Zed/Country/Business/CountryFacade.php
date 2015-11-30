<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method CountryDependencyContainer getDependencyContainer()
 */
class CountryFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $messenger
     */
    public function install(LoggerInterface $messenger)
    {
        $this->getDependencyContainer()->createInstaller($messenger)->install();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->getDependencyContainer()->createCountryManager()->getIdCountryByIso2Code($iso2Code);
    }

    /**
     * @return CountryCollectionTransfer
     */
    public function getAvailableCountries()
    {
        $countries = $this->getDependencyContainer()
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
        $countryTransfer = $this->getDependencyContainer()
            ->createCountryManager()
            ->getPreferedCountryByName($countryName);

        return $countryTransfer;
    }

}
