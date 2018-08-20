<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoreLocaleRestAttributesTransfer;
use Generated\Shared\Transfer\StoresRestAttributesTransfer;
use Spryker\Shared\Kernel\Store;

class StoresResourceMapper implements StoresResourceMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $countries
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $currencies
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreToStoresRestAttribute(Store $store, array $countries, array $currencies): StoresRestAttributesTransfer
    {
        $storesRestAttributes = new StoresRestAttributesTransfer();

        $storesRestAttributes = $this->addLocaleToStoresRestAttributes(
            $storesRestAttributes,
            $store->getLocales()
        );

        $storesRestAttributes = $this->addStoreCountryToStoresRestAttributes(
            $storesRestAttributes,
            $countries
        );

        $storesRestAttributes = $this->addTimeZoneToStoresRestAttributes($storesRestAttributes, $store);

        $storesRestAttributes = $this->addDefaultCurrencyToStoresRestAttributes(
            $storesRestAttributes,
            $store->getDefaultCurrencyCode()
        );

        $storesRestAttributes = $this->addStoreCurrencyToStoresRestAttributes(
            $storesRestAttributes,
            $currencies
        );

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param array $locales
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addLocaleToStoresRestAttributes(
        StoresRestAttributesTransfer $storesRestAttributes,
        array $locales
    ): StoresRestAttributesTransfer {

        foreach ($locales as $identifier => $name) {
            $storesLocaleAttributes = (new StoreLocaleRestAttributesTransfer())
                ->setName($name)
                ->setCode($identifier);

            $storesRestAttributes->addLocales($storesLocaleAttributes);
        }

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addTimeZoneToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, Store $store): StoresRestAttributesTransfer
    {
        if (isset($store->getContexts()['glue']['timezone'])) {
            return $storesRestAttributes->setTimeZone($store->getContexts()['glue']['timezone']);
        }

        if (isset($store->getContexts()['*']['timezone'])) {
            return $storesRestAttributes->setTimeZone($store->getContexts()['*']['timezone']);
        }

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $defaulCurrency
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addDefaultCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $defaulCurrency): StoresRestAttributesTransfer
    {
        $storesRestAttributes->setDefaultCurrency($defaulCurrency);

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $storeCountryAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addStoreCountryToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCountryAttributes): StoresRestAttributesTransfer
    {
        foreach ($storeCountryAttributes as $storeCountryAttribute) {
            $storesRestAttributes->addCountries($storeCountryAttribute);
        }

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $storeCurrencyAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addStoreCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCurrencyAttributes): StoresRestAttributesTransfer
    {
        foreach ($storeCurrencyAttributes as $storeCurrencyAttribute) {
            $storesRestAttributes->addCurrencies($storeCurrencyAttribute);
        }

        return $storesRestAttributes;
    }
}
