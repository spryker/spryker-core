<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Cache;

use Generated\Shared\Transfer\LocaleTransfer;

class LocaleCache implements LocaleCacheInterface
{
    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected static $localeCache = [];

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected static $localeCacheById = [];

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findByName(string $localeName): ?LocaleTransfer
    {
        return static::$localeCache[$localeName] ?? null;
    }

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findById(int $idLocale): ?LocaleTransfer
    {
        return static::$localeCacheById[$idLocale] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function set(LocaleTransfer $localeTransfer): void
    {
        static::$localeCache[$localeTransfer->getLocaleName()] = $localeTransfer;
        static::$localeCacheById[$localeTransfer->getIdLocale()] = &static::$localeCache[$localeTransfer->getLocaleName()];
    }
}
