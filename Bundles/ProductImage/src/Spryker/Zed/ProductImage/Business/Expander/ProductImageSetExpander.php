<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTranslationTransfer;
use Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToStoreFacadeInterface;

class ProductImageSetExpander implements ProductImageSetExpanderInterface
{
    /**
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface $glossaryReader
     */
    public function __construct(
        protected ProductImageToLocaleInterface $localeFacade,
        protected ProductImageToStoreFacadeInterface $storeFacade,
        protected GlossaryReaderInterface $glossaryReader
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function expandProductImageSetCollectionWithProductImageAlternativeTextTranslations(array $productImageSetTransfers): array
    {
        $localeTransfersIndexedByIdLocale = $this->getLocaleTransfersIndexedByIdLocale();
        $glossaryKeysIndexedByIdLocale = $this->getGlossaryKeysIndexedByIdLocale(
            $productImageSetTransfers,
            $localeTransfersIndexedByIdLocale,
        );
        $translationTransfersIndexedByIdLocaleAndGlossaryKey = $this->glossaryReader->getTranslationTransfersIndexedByIdLocaleAndGlossaryKey(
            $glossaryKeysIndexedByIdLocale,
            $localeTransfersIndexedByIdLocale,
        );

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $this->expandProductImageSetTransferWithProductImageAlternativeTextTranslations(
                $productImageSetTransfer,
                $translationTransfersIndexedByIdLocaleAndGlossaryKey,
                $localeTransfersIndexedByIdLocale,
            );
        }

        return $productImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     * @param array<int, array<string, \Generated\Shared\Transfer\TranslationTransfer>> $translationTransfersIndexedByLocaleIdAndGlossaryKey
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return void
     */
    protected function expandProductImageSetTransferWithProductImageAlternativeTextTranslations(
        ProductImageSetTransfer $productImageSetTransfer,
        array $translationTransfersIndexedByLocaleIdAndGlossaryKey,
        array $localeTransfersIndexedByIdLocale
    ): void {
        $idLocale = $productImageSetTransfer->getLocale()?->getIdLocale();

        if ($idLocale) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $altTextSmallTranslationTransfer = $translationTransfersIndexedByLocaleIdAndGlossaryKey[$idLocale][$productImageTransfer->getAltTextSmall()] ?? null;
                $altTextLargeTranslationTransfer = $translationTransfersIndexedByLocaleIdAndGlossaryKey[$idLocale][$productImageTransfer->getAltTextLarge()] ?? null;

                if (!$altTextSmallTranslationTransfer && !$altTextLargeTranslationTransfer) {
                    continue;
                }

                $productImageTranslationTransfer = (new ProductImageTranslationTransfer())
                    ->setLocale($localeTransfersIndexedByIdLocale[$idLocale])
                    ->setAltTextSmall($altTextSmallTranslationTransfer?->getValue())
                    ->setAltTextLarge($altTextLargeTranslationTransfer?->getValue());
                $productImageTransfer->addTranslation($productImageTranslationTransfer);
            }

            return;
        }

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            foreach ($localeTransfersIndexedByIdLocale as $idLocale => $localeTransfer) {
                $altTextSmallTranslationTransfer = $translationTransfersIndexedByLocaleIdAndGlossaryKey[$idLocale][$productImageTransfer->getAltTextSmall()] ?? null;
                $altTextLargeTranslationTransfer = $translationTransfersIndexedByLocaleIdAndGlossaryKey[$idLocale][$productImageTransfer->getAltTextLarge()] ?? null;

                if (!$altTextSmallTranslationTransfer && !$altTextLargeTranslationTransfer) {
                    continue;
                }

                $productImageTranslationTransfer = (new ProductImageTranslationTransfer())
                    ->setLocale($localeTransfer)
                    ->setAltTextSmall($altTextSmallTranslationTransfer?->getValue())
                    ->setAltTextLarge($altTextLargeTranslationTransfer?->getValue());
                $productImageTransfer->addTranslation($productImageTranslationTransfer);
            }
        }
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function getLocaleTransfersIndexedByIdLocale(): array
    {
        $localeTransfersIndexedByIdLocale = [];

        foreach ($this->localeFacade->getLocaleCollection($this->createLocaleCriteriaTransfer()) as $localeTransfer) {
            $localeTransfersIndexedByIdLocale[(int)$localeTransfer->getIdLocale()] = $localeTransfer;
        }

        return $localeTransfersIndexedByIdLocale;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleCriteriaTransfer
     */
    protected function createLocaleCriteriaTransfer(): LocaleCriteriaTransfer
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $localeConditionsTransfer = new LocaleConditionsTransfer();

        foreach ($storeTransfers as $storeTransfer) {
            $localeConditionsTransfer->addStoreName($storeTransfer->getNameOrFail());
        }

        return (new LocaleCriteriaTransfer())->setLocaleConditions($localeConditionsTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param array<int, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByIdLocale
     *
     * @return array<int, list<string>>
     */
    protected function getGlossaryKeysIndexedByIdLocale(array $productImageSetTransfers, array $localeTransfersIndexedByIdLocale): array
    {
        $glossaryKeyArraysIndexedByIdLocale = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $glossarykeys = $this->getGlossarykeys($productImageSetTransfer->getProductImages());
            $idLocale = $productImageSetTransfer->getLocale()?->getIdLocale();

            if ($idLocale && isset($localeTransfersIndexedByIdLocale[$idLocale])) {
                $glossaryKeyArraysIndexedByIdLocale[$idLocale][] = $glossarykeys;

                continue;
            }

            foreach ($localeTransfersIndexedByIdLocale as $localeTransfer) {
                $glossaryKeyArraysIndexedByIdLocale[$localeTransfer->getIdLocale()][] = $glossarykeys;
            }
        }

        $glossaryKeysIndexedByIdLocale = [];

        foreach ($glossaryKeyArraysIndexedByIdLocale as $idLocale => $glossaryKeys) {
            $glossaryKeysIndexedByIdLocale[(int)$idLocale] = array_merge(...$glossaryKeys);
        }

        return $glossaryKeysIndexedByIdLocale;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageTransfer> $productImageTransfers
     *
     * @return list<string>
     */
    protected function getGlossarykeys(ArrayObject $productImageTransfers): array
    {
        $glossarykeys = [];

        foreach ($productImageTransfers as $productImageTransfer) {
            $glossarykeys[] = [
                $productImageTransfer->getAltTextSmall(),
                $productImageTransfer->getAltTextLarge(),
            ];
        }

        return array_filter(array_merge(...$glossarykeys));
    }
}
