<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Dependency;

class StoreToKernelStoreBridge implements StoreToKernelStoreInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getDefaultStore()
    {
        return $this->store->getDefaultStore();
    }

    /**
     * @param string $currentStoreName
     *
     * @return void
     */
    public function initializeSetup($currentStoreName)
    {
        $this->store->initializeSetup($currentStoreName);
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->store->getCurrentLocale();
    }

    /**
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->store->getCurrentLanguage();
    }

    /**
     * @return array
     */
    public function getAllowedStores()
    {
        return $this->store->getAllowedStores();
    }

    /**
     * @return array
     */
    public function getInactiveStores()
    {
        return $this->store->getInactiveStores();
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return $this->store->getLocales();
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getLocalesPerStore($storeName)
    {
        return $this->store->getLocalesPerStore($storeName);
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @param string $storeName
     *
     * @return void
     */
    public function setStoreName($storeName)
    {
        $this->store->setStoreName($storeName);
    }

    /**
     * @param string $currentLocale
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->store->setCurrentLocale($currentLocale);
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->store->getContexts();
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->store->getCountries();
    }

    /**
     * @param string $currentCountry
     *
     * @return void
     */
    public function setCurrentCountry($currentCountry)
    {
        $this->store->setCurrentCountry($currentCountry);
    }

    /**
     * @return string
     */
    public function getCurrentCountry()
    {
        return $this->store->getCurrentCountry();
    }

    /**
     * @return string
     */
    public function getStorePrefix()
    {
        return $this->store->getStorePrefix();
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrencyIsoCode($currencyIsoCode)
    {
        $this->store->setCurrencyIsoCode($currencyIsoCode);
    }

    /**
     * @return string[]
     */
    public function getCurrencyIsoCodes()
    {
        return $this->store->getCurrencyIsoCodes();
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->store->getCurrencyIsoCode();
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesForStore($storeName)
    {
        return $this->store->getAvailableCurrenciesForStore($storeName);
    }

}
