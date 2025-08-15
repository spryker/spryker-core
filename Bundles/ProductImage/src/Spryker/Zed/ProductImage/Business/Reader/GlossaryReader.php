<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(protected ProductImageToGlossaryFacadeInterface $glossaryFacade)
    {
    }

    /**
     * @param array<int, list<string>> $glossaryKeysIndexedByIdLocale
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    public function getTranslationTransfersIndexedByIdLocaleAndGlossaryKey(
        array $glossaryKeysIndexedByIdLocale,
        array $localeTransfersIndexedByIdLocale
    ): array {
        $translationTransfers = $this->getTranslationTransfers(
            $glossaryKeysIndexedByIdLocale,
            $localeTransfersIndexedByIdLocale,
        );

        return $this->indexTranslationTransfersByIdLocaleAndGlossaryKey($translationTransfers);
    }

    /**
     * @param list<string> $glossaryKeys
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    public function getTranslationsIndexedByGlossaryKey(array $glossaryKeys, string $localeName): array
    {
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            [(new LocaleTransfer())->setLocaleName($localeName)],
        );

        $translationsIndexedByGlossaryKey = [];

        foreach ($translationTransfers as $translationTransfer) {
            if (!$translationTransfer->getIsActive()) {
                continue;
            }

            $translationsIndexedByGlossaryKey[$translationTransfer->getGlossaryKeyOrFail()->getKey()] = $translationTransfer->getValue();
        }

        return $translationsIndexedByGlossaryKey;
    }

    /**
     * @param array<int, list<string>> $glossaryKeysIndexedByIdLocale
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return list<\Generated\Shared\Transfer\TranslationTransfer>
     */
    protected function getTranslationTransfers(
        array $glossaryKeysIndexedByIdLocale,
        array $localeTransfersIndexedByIdLocale
    ): array {
        $translationTransfers = [];

        foreach ($glossaryKeysIndexedByIdLocale as $idLocale => $glossaryKeys) {
            $translationTransfers[] = $this->glossaryFacade
                ->getTranslationsByGlossaryKeysAndLocaleTransfers($glossaryKeys, [$localeTransfersIndexedByIdLocale[$idLocale]]);
        }

        return array_merge(...$translationTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\TranslationTransfer> $translationTransfers
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>>
     */
    protected function indexTranslationTransfersByIdLocaleAndGlossaryKey(array $translationTransfers): array
    {
        $translationTransfersIndexedByIdLocaleAndGlossaryKey = [];

        foreach ($translationTransfers as $translationTransfer) {
            $glossaryKey = $translationTransfer->getGlossaryKeyOrFail()->getKey();
            $translationTransfersIndexedByIdLocaleAndGlossaryKey[(int)$translationTransfer->getFkLocale()][$glossaryKey] = $translationTransfer;
        }

        return $translationTransfersIndexedByIdLocaleAndGlossaryKey;
    }
}
