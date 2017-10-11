<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model\Configuration;

interface StoreConfigurationProviderInterface
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
     * @return string[]
     */
    public function getCurrentStoreAvailableCurrencyIsoCodes();

    /**
     * @return string
     */
    public function getCurrentStoreSelectedLocaleIsoCode();

    /**
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

}
