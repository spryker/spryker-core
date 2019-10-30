<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoreLocaleRestAttributesTransfer;
use Generated\Shared\Transfer\StoresRestAttributesTransfer;

class StoresResourceMapper implements StoresResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientInterface $storeClient
     */
    public function __construct($storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $countries
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $currencies
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreToStoresRestAttribute(array $countries, array $currencies): StoresRestAttributesTransfer
    {
        $storesRestAttributes = new StoresRestAttributesTransfer();

        $storesRestAttributes = $this->addLocaleToStoresRestAttributes(
            $storesRestAttributes,
            $this->storeClient->getCurrentStore()->getAvailableLocaleIsoCodes()
        );

        $storesRestAttributes = $this->addStoreCountryToStoresRestAttributes(
            $storesRestAttributes,
            $countries
        );

        $storesRestAttributes = $this->addTimeZoneToStoresRestAttributes($storesRestAttributes);

        $storesRestAttributes = $this->addDefaultCurrencyToStoresRestAttributes(
            $storesRestAttributes,
            $this->storeClient->getCurrentStore()->getDefaultCurrencyIsoCode()
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

            $storesRestAttributes->addLocale($storesLocaleAttributes);
        }

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addTimeZoneToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes): StoresRestAttributesTransfer
    {
        return $storesRestAttributes->setTimeZone($this->storeClient->getCurrentStore()->getTimezone());
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $defaultCurrency
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function addDefaultCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $defaultCurrency): StoresRestAttributesTransfer
    {
        $storesRestAttributes->setDefaultCurrency($defaultCurrency);

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
            $storesRestAttributes->addCountry($storeCountryAttribute);
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
            $storesRestAttributes->addCurrency($storeCurrencyAttribute);
        }

        return $storesRestAttributes;
    }
}
