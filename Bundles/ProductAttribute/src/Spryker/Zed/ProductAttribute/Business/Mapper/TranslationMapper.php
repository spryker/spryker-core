<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Mapper;

use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;

class TranslationMapper implements TranslationMapperInterface
{
    /**
     * @var \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(GlossaryKeyBuilderInterface $glossaryKeyBuilder)
    {
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\TranslationTransfer[] $translationTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     * @param \Generated\Shared\Transfer\GlossaryKeyTransfer[] $glossaryKeyTransfers
     *
     * @return string[][]
     */
    public function mapTranslationsByKeyNameAndLocaleName(
        array $translationTransfers,
        array $localeTransfers,
        array $glossaryKeyTransfers
    ): array {
        $mappedTranslationTransfers = [];

        $mappedLocaleTransfers = $this->mapLocaleTransfersByIdLocale($localeTransfers);
        $mappedGlossaryKeyTransfers = $this->mapGlossaryKeyTransfersByIdGlossaryKey($glossaryKeyTransfers);

        foreach ($translationTransfers as $translationTransfer) {
            $localeTransfer = $mappedLocaleTransfers[$translationTransfer->getFkLocale()] ?? null;
            $glossaryKeyTransfer = $mappedGlossaryKeyTransfers[$translationTransfer->getFkGlossaryKey()] ?? null;

            if (!$localeTransfer || !$glossaryKeyTransfer) {
                continue;
            }

            $mappedTranslationTransfers[$glossaryKeyTransfer->getKey()][$localeTransfer->getLocaleName()] = $translationTransfer->getValue();
        }

        return $mappedTranslationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function mapLocaleTransfersByIdLocale(array $localeTransfers): array
    {
        $mappedLocaleTransfers = [];

        foreach ($localeTransfers as $localeTransfer) {
            $mappedLocaleTransfers[$localeTransfer->getIdLocale()] = $localeTransfer;
        }

        return $mappedLocaleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GlossaryKeyTransfer[] $glossaryKeyTransfers
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    protected function mapGlossaryKeyTransfersByIdGlossaryKey(array $glossaryKeyTransfers): array
    {
        $mappedGlossaryKeyTransfers = [];

        foreach ($glossaryKeyTransfers as $glossaryKeyTransfer) {
            $mappedGlossaryKeyTransfers[$glossaryKeyTransfer->getIdGlossaryKey()] = $glossaryKeyTransfer;
        }

        return $mappedGlossaryKeyTransfers;
    }
}
