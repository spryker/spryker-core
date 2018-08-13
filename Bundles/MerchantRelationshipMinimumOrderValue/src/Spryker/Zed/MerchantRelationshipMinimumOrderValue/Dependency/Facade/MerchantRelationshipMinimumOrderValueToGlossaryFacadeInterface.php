<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
{
    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool;

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey(string $keyName): int;

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, LocaleTransfer $localeTransfer): bool;

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer;

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value
    ): TranslationTransfer;

    /**
     * @param string $keyName
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\LocaleTransfer $localeTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateTranslation(
        string $keyName,
        LocaleTransfer $localeTransfer,
        string $value
    ): TranslationTransfer;
}
