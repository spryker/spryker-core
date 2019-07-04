<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;

interface CountryFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * @api
     *
     * @param string $iso2Code
     *
     * @return bool
     */
    public function hasCountry($iso2Code);

    /**
     * @api
     *
     * @deprecated Use getCountryByIso2Code() instead.
     *
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code);

    /**
     * Specification:
     * - Reads country from persistence for provided ISO 2 country code
     *
     * @api
     *
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code($iso2Code);

    /**
     * Specification:
     * - Verifies if countries can be found by countryIso2Codes given in CheckoutDataTransfer.billingAddress and CheckoutDataTransfer.shippingAddress.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountryCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Reads country from persistence for provided ISO 3 country code
     *
     * @api
     *
     * @param string $iso3Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code($iso3Code);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries();

    /**
     * @api
     *
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName($countryName);

    /**
     * Specification:
     * - Retrieves countries with regions data by country ISO-2 codes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer;
}
