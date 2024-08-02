<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;

/**
 * @deprecated Will be removed after dynamic multi-store is always enabled.
 */
class StoreReader implements StoreReaderInterface
{
    /**
     * @var \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected $store;

    /**
     * @var array<\Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface>
     */
    protected array $storeExpanders;

    /**
     * @param \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface $store
     * @param array<\Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface> $storeExpanders
     */
    public function __construct(
        StoreToStoreInterface $store,
        array $storeExpanders = []
    ) {
        $this->store = $store;
        $this->storeExpanders = $storeExpanders;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        $availableLocaleIsoCodes = $this->store->getAvailableLocaleIsoCodesFor($storeName);

        $storeTransfer = (new StoreTransfer())
            ->setName($storeName)
            ->setQueuePools($this->store->getQueuePools())
            ->setSelectedCurrencyIsoCode($this->store->getCurrentStoreSelectedCurrencyIsoCode())
            ->setDefaultCurrencyIsoCode($this->store->getDefaultCurrencyFor($storeName))
            ->setAvailableCurrencyIsoCodes($this->store->getAvailableCurrenciesFor($storeName))
            ->setAvailableLocaleIsoCodes($availableLocaleIsoCodes)
            ->setDefaultLocaleIsoCode($this->findDefaultLocaleIsoCode($availableLocaleIsoCodes))
            ->setStoresWithSharedPersistence($this->store->getStoresWithSharedPersistence())
            ->setCountries($this->store->getCountries())
            ->setTimezone($this->store->getTimezone());

        return $this->expandStore($storeTransfer);
    }

    /**
     * @param array<string> $availableLocaleIsoCodes
     *
     * @return string|null
     */
    protected function findDefaultLocaleIsoCode(array $availableLocaleIsoCodes): ?string
    {
        return $availableLocaleIsoCodes[0] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function expandStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        foreach ($this->storeExpanders as $storeExpander) {
            $storeTransfer = $storeExpander->expand($storeTransfer);
        }

        return $storeTransfer;
    }
}
