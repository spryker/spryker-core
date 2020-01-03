<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Dependency\Adapter;

use Spryker\Shared\Kernel\Store;

class StoreToKernelStoreAdapter implements StoreToStoreInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getCurrentStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string[]
     */
    public function getCurrentStoreAvailableCurrencyIsoCodes()
    {
        return $this->store->getCurrencyIsoCodes();
    }

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string
     */
    public function getCurrentStoreSelectedLocaleIsoCode()
    {
        return $this->store->getCurrentLocale();
    }

    /**
     * @return string
     */
    public function getCurrentStoreSelectedCurrencyIsoCode()
    {
        return $this->store->getCurrencyIsoCode();
    }

    /**
     * @deprecated Unused method will be removed in next major
     *
     * @return string[]
     */
    public function getCurrentAvailableLocaleIsoCodes()
    {
        return $this->store->getLocales();
    }

    /**
     * @return string[]
     */
    public function getAllStoreNames()
    {
        return $this->store->getAllowedStores();
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableCurrenciesFor($storeName)
    {
        return $this->getConfigurationForStore($storeName)['currencyIsoCodes'];
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getAvailableLocaleIsoCodesFor($storeName)
    {
        return $this->getConfigurationForStore($storeName)['locales'];
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    public function getDefaultCurrencyFor($storeName)
    {
        $currencyIsoCodes = $this->getAvailableCurrenciesFor($storeName);

        return current($currencyIsoCodes);
    }

    /**
     * @return string[]
     */
    public function getStoresWithSharedPersistence()
    {
        return $this->store->getStoresWithSharedPersistence();
    }

    /**
     * @return string[]
     */
    public function getCountries()
    {
        return $this->store->getCountries();
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    protected function getConfigurationForStore($storeName)
    {
        return $this->store->getConfigurationForStore($storeName);
    }

    /**
     * @return array
     */
    public function getQueuePools()
    {
        return $this->store->getQueuePools();
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->store->getContexts()['*']['timezone'] ?? '';
    }
}
