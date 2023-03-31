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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Country\Business\CountryBusinessFactory getFactory()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface getEntityManager()
 */
class CountryFacade extends AbstractFacade implements CountryFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $iso2Code
     *
     * @return bool
     */
    public function hasCountry(string $iso2Code): bool
    {
        return $this->getFactory()->createCountryReader()->countryExists($iso2Code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(string $iso2Code): CountryTransfer
    {
        return $this->getFactory()->createCountryReader()->getCountryByIso2Code($iso2Code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountryCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFactory()->createCountryCheckoutDataValidator()->validateCountryCheckoutData($checkoutDataTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $iso3Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso3Code(string $iso3Code): CountryTransfer
    {
        return $this->getFactory()->createCountryReader()->getCountryByIso3Code($iso3Code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getAvailableCountries(): CountryCollectionTransfer
    {
        return $this->getFactory()
            ->createCountryReader()
            ->getCountryCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getPreferredCountryByName(string $countryName): CountryTransfer
    {
        return $this->getFactory()
            ->createCountryReader()
            ->getPreferredCountryByName($countryName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        return $this->getFactory()
            ->createCountryReader()
            ->getCountriesByIso2CodesFromCountryCollection($countryCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStoreCountries(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()
            ->createCountryWriter()
            ->updateStoreCountries($storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCountries(array $storeTransfers): array
    {
        return $this->getFactory()
            ->createStoreExpander()
            ->expandStoreTransfersWithCountries($storeTransfers);
    }
}
