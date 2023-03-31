<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface CountryFacadeInterface
{
    /**
     * Specification:
     * - Fills the database with countries and regions data.
     *
     * @api
     *
     * @return void
     */
    public function install(): void;

    /**
     * Specification:
     * - Checks if country exists by ISO 2 country code.
     *
     * @api
     *
     * @param string $iso2Code
     *
     * @return bool
     */
    public function hasCountry(string $iso2Code): bool;

    /**
     * Specification:
     * - Reads country from persistence for provided ISO 2 country code.
     *
     * @api
     *
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(string $iso2Code): CountryTransfer;

    /**
     * Specification:
     * - Verifies if countries can be found by countryIso2Codes given in CheckoutDataTransfer.billingAddress.
     * - Verifies if countries can be found by countryIso2Codes given in CheckoutDataTransfer.shipments.shippingAddress.
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
     * - Reads country from persistence for provided ISO 3 country code.
     *
     * @api
     *
     * @param string $iso3Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code(string $iso3Code): CountryTransfer;

    /**
     * Specification:
     * - Returns all available countries.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries(): CountryCollectionTransfer;

    /**
     * Specification:
     * - Returns preferred country by name.
     *
     * @api
     *
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName(string $countryName): CountryTransfer;

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

    /**
     * Specification:
     * - Drops all relation of between store and countries.
     * - Persists new `CountryStore` entities to a database.
     * - Returns a `StoreResponseTransfer` with the data of the store and its countries.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreCountries(StoreTransfer $storeTransfer): StoreResponseTransfer;

    /**
     * Specification:
     * - Expands collection of store transfers with country names and codes.
     * - Expands collection of store transfers only if `Dynamic Store` is enabled.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCountries(array $storeTransfers): array;
}
