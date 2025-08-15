<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

interface GlossaryReaderInterface
{
    /**
     * @param array<int, list<string>> $glossaryKeysIndexedByIdLocale
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    public function getTranslationTransfersIndexedByIdLocaleAndGlossaryKey(
        array $glossaryKeysIndexedByIdLocale,
        array $localeTransfersIndexedByIdLocale
    ): array;

    /**
     * @param list<string> $glossaryKeys
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    public function getTranslationsIndexedByGlossaryKey(array $glossaryKeys, string $localeName): array;
}
