<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoreCountryRestAttributesTransfer;
use Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer;
use Generated\Shared\Transfer\StoreLocaleRestAttributesTransfer;
use Generated\Shared\Transfer\StoresRestAttributesTransfer;

class StoresResourceMapper implements StoresResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $identifier
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapLocaleToStoresRestAttributes(
        StoresRestAttributesTransfer $storesRestAttributes,
        string $identifier,
        string $name
    ): StoresRestAttributesTransfer {
        $storesLocaleAttributes = (new StoreLocaleRestAttributesTransfer())
            ->setName($name)
            ->setIdentifier($identifier);

        $storesRestAttributes->addLocales($storesLocaleAttributes);

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
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer $storeCountryAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreCountryToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, StoreCountryRestAttributesTransfer $storeCountryAttributes): StoresRestAttributesTransfer
    {
        $storesRestAttributes->addCountries($storeCountryAttributes);

        return $storesRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer $storeCurrencyAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, StoreCurrencyRestAttributesTransfer $storeCurrencyAttributes): StoresRestAttributesTransfer
    {
        $storesRestAttributes->setCurrency($storeCurrencyAttributes);

        return $storesRestAttributes;
    }
}
