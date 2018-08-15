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
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param array $locales
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapLocaleToStoresRestAttributes(
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
     * @param string $timeZone
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapTimeZoneToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $timeZone): StoresRestAttributesTransfer
    {
        $storesRestAttributes->setTimeZone($timeZone);

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $defaulCurrency
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapDefaultCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $defaulCurrency): StoresRestAttributesTransfer
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
    public function mapStoreCountryToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCountryAttributes): StoresRestAttributesTransfer
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
    public function mapStoreCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCurrencyAttributes): StoresRestAttributesTransfer
    {
        foreach ($storeCurrencyAttributes as $storeCurrencyAttribute) {
            $storesRestAttributes->addCurrency($storeCurrencyAttribute);
        }

        return $storesRestAttributes;
    }
}
