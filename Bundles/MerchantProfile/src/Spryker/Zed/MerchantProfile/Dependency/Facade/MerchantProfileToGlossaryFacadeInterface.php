<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface MerchantProfileToGlossaryFacadeInterface
{
    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey(string $keyName): int;

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool;

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation(string $keyName, LocaleTransfer $locale, string $value, bool $isActive = true): TranslationTransfer;

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, ?LocaleTransfer $locale = null): bool;

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation(string $keyName, LocaleTransfer $locale, string $value, bool $isActive = true): TranslationTransfer;

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteTranslation(string $keyName, LocaleTransfer $locale): bool;
}
