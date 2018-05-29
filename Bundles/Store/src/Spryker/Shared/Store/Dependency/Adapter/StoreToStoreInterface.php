<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Dependency\Adapter;

interface StoreToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrentStoreName();

    /**
     * @return string[]
     */
    public function getAllStoreNames();

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string[]
     */
    public function getCurrentStoreAvailableCurrencyIsoCodes();

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string
     */
    public function getCurrentStoreSelectedLocaleIsoCode();

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string[]
     */
    public function getCurrentAvailableLocaleIsoCodes();

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesFor($storeName);

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableLocaleIsoCodesFor($storeName);

    /**
     * @param string $storeName
     *
     * @return string
     */
    public function getDefaultCurrencyFor($storeName);

    /**
     * @return string
     */
    public function getCurrentStoreSelectedCurrencyIsoCode();

    /**
     * @return string[]
     */
    public function getStoresWithSharedPersistence();

    /**
     * @return array
     */
    public function getCountries();
}
