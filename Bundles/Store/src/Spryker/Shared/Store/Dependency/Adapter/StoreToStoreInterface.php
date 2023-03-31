<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Dependency\Adapter;

/**
 * @deprecated Will be removed after dynamic multi-store is always enabled.
 */
interface StoreToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrentStoreName();

    /**
     * @return array<string>
     */
    public function getAllStoreNames();

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return array<string>
     */
    public function getCurrentStoreAvailableCurrencyIsoCodes();

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return string
     */
    public function getCurrentStoreSelectedLocaleIsoCode();

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return array<string>
     */
    public function getCurrentAvailableLocaleIsoCodes();

    /**
     * @param string $storeName
     *
     * @return array<string>
     */
    public function getAvailableCurrenciesFor($storeName);

    /**
     * @param string $storeName
     *
     * @return array<string>
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
     * @return array<mixed>
     */
    public function getQueuePools();

    /**
     * @return array<string>
     */
    public function getStoresWithSharedPersistence();

    /**
     * @return array<string>
     */
    public function getCountries();

    /**
     * @return string
     */
    public function getTimezone();
}
