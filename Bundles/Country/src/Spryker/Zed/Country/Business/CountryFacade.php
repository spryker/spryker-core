<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method \Spryker\Zed\Country\Business\CountryBusinessFactory getFactory()
 */
class CountryFacade extends AbstractFacade implements CountryFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function install(LoggerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

    /**
     * @param string $iso2Code
     *
     * @return bool
     */
    public function hasCountry($iso2Code)
    {
        return $this->getFactory()->createCountryManager()->hasCountry($iso2Code);
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->getFactory()->createCountryManager()->getIdCountryByIso2Code($iso2Code);
    }

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries()
    {
        $countries = $this->getFactory()
            ->createCountryManager()
            ->getCountryCollection();

        return $countries;
    }

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName($countryName)
    {
        $countryTransfer = $this->getFactory()
            ->createCountryManager()
            ->getPreferredCountryByName($countryName);

        return $countryTransfer;
    }

}
