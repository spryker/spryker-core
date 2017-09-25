<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Dependency;

interface StoreToKernelStoreInterface
{

    /**
     * @return string
     */
    public function getDefaultStore();

    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     *
     * @return void
     */
    public function initializeSetup($currentStoreName);

    /**
     * @throws \Spryker\Shared\Kernel\Locale\LocaleNotFoundException
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * @return string
     */
    public function getCurrentLanguage();

    /**
     * @return array
     */
    public function getAllowedStores();

    /**
     * @return array
     */
    public function getInactiveStores();

    /**
     * @return array
     */
    public function getLocales();

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getLocalesPerStore($storeName);

    /**
     * @return string
     */
    public function getStoreName();

    /**
     * @param string $storeName
     *
     * @return void
     */
    public function setStoreName($storeName);

    /**
     * @param string $currentLocale
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale);

    /**
     * @return array
     */
    public function getContexts();

    /**
     * @return array
     */
    public function getCountries();

    /**
     * @param string $currentCountry
     *
     * @return void
     */
    public function setCurrentCountry($currentCountry);

    /**
     * @return string
     */
    public function getCurrentCountry();

    /**
     * @return string
     */
    public function getStorePrefix();

    /**
     * @param string $currencyIsoCode
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setCurrencyIsoCode($currencyIsoCode);

    /**
     * @return string[]
     */
    public function getCurrencyIsoCodes();

    /**
     * @return string
     */
    public function getCurrencyIsoCode();

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesForStore($storeName);

}
