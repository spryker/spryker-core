<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface LocaleEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function createLocaleStore(StoreTransfer $storeTransfer, LocaleTransfer $localeTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return void
     */
    public function updateStoreLocales(StoreTransfer $storeTransfer, array $localeTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateStoreDefaultLocale(StoreTransfer $storeTransfer, LocaleTransfer $localeTransfer): void;

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale(string $localeName): LocaleTransfer;

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale(string $localeName): void;
}
