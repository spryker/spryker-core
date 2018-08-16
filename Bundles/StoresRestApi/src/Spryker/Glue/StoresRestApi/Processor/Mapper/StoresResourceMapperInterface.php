<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoresRestAttributesTransfer;

interface StoresResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param array $locales
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapLocaleToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $locales): StoresRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $timeZone
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapTimeZoneToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $timeZone): StoresRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param string $defaulCurrency
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapDefaultCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, string $defaulCurrency): StoresRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer$storesRestAttributes
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $storeCountryAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreCountryToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCountryAttributes): StoresRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoresRestAttributesTransfer $storesRestAttributes
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $storeCurrencyAttributes
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreCurrencyToStoresRestAttributes(StoresRestAttributesTransfer $storesRestAttributes, array $storeCurrencyAttributes): StoresRestAttributesTransfer;
}
