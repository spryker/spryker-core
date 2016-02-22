<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Psr\Log\LoggerInterface;

interface CountryFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function install(LoggerInterface $messenger);

    /**
     * @param string $iso2Code
     *
     * @return bool
     */
    public function hasCountry($iso2Code);

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code);

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries();

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName($countryName);

}
