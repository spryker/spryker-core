<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Configuration;

use Generated\Shared\Transfer\StoreTransfer;

class StoreConfigurationReader implements StoreConfigurationReaderInterface
{
    /**
     * @var \Spryker\Shared\Store\Configuration\StoreConfigurationProviderInterface;
     */
    protected $storeConfigurationProvider;

    /**
     * @param \Spryker\Shared\Store\Configuration\StoreConfigurationProviderInterface $storeConfigurationProvider
     */
    public function __construct(StoreConfigurationProviderInterface $storeConfigurationProvider)
    {
        $this->storeConfigurationProvider = $storeConfigurationProvider;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        $storeTransfer = (new StoreTransfer())
            ->setName($storeName)
            ->setSelectedCurrencyIsoCode($this->storeConfigurationProvider->getCurrentStoreSelectedCurrencyIsoCode())
            ->setDefaultCurrencyIsoCode($this->storeConfigurationProvider->getDefaultCurrencyFor($storeName))
            ->setAvailableCurrencyIsoCodes($this->storeConfigurationProvider->getAvailableCurrenciesFor($storeName))
            ->setAvailableLocaleIsoCodes($this->storeConfigurationProvider->getAvailableLocaleIsoCodesFor($storeName));

        return $storeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->getStoreByName($this->storeConfigurationProvider->getCurrentStoreName());
    }
}
