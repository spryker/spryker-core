<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;

interface LocaleRepositoryInterface
{
    /**
     * @param string[] $localeNames
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleTransfersByLocaleNames(array $localeNames): array;

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleTransferByLocaleName(string $localeName): ?LocaleTransfer;

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleByIdLocale(int $idLocale): ?LocaleTransfer;
}
