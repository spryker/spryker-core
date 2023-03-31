<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Country;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;

interface CountryReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2CodesFromCountryCollection(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer;

    /**
     * @param array<string> $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer;

    /**
     * @param string $iso2code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(string $iso2code): CountryTransfer;

    /**
     * @param string $iso3code
     *
     * @throws \Spryker\Zed\Country\Business\Exception\MissingCountryException
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code(string $iso3code): CountryTransfer;

    /**
     * @param string $iso2code
     *
     * @return bool
     */
    public function countryExists(string $iso2code): bool;

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(): CountryCollectionTransfer;

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName(string $countryName): CountryTransfer;
}
