<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model\Configuration;

use Spryker\Shared\Kernel\Store;

class StoreConfigurationProvider implements StoreConfigurationProviderInterface
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
     * @return string[]
     */
    public function getCurrentStoreAvailableCurrencyIsoCodes()
    {
        return $this->store->getCurrencyIsoCodes();
    }

    /**
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
     * @param string $storeName
     *
     * @return array
     */
    protected function getConfigurationForStore($storeName)
    {
        return $this->store->getConfigurationForStore($storeName);
    }

}
