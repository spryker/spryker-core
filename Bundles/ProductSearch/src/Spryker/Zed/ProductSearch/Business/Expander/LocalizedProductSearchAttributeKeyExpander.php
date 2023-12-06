<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Expander;

use Generated\Shared\Transfer\LocalizedProductSearchAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;

class LocalizedProductSearchAttributeKeyExpander implements LocalizedProductSearchAttributeKeyExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected ProductSearchToLocaleInterface $localeFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface
     */
    protected ProductSearchToGlossaryInterface $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected GlossaryKeyBuilderInterface $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductSearchToLocaleInterface $localeFacade,
        ProductSearchToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function expandProductSearchAttributeCollectionWithLocalizedKeys(
        ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
    ): ProductSearchAttributeCollectionTransfer {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $glossaryKeys = $this->extractGlossaryKeysFromProductSearchAttributeCollectionTransfer($productSearchAttributeCollectionTransfer);

        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers($glossaryKeys, $localeTransfers);
        $translationsIndexedByKeyAndLocale = $this->getTranslationValuesIndexedByGlossaryKeyAndLocale($translationTransfers);

        foreach ($productSearchAttributeCollectionTransfer->getProductSearchAttributes() as $productSearchAttributeTransfer) {
            $this->expandProductSearchAttributeWithLocalizedKeys($localeTransfers, $translationsIndexedByKeyAndLocale, $productSearchAttributeTransfer);
        }

        return $productSearchAttributeCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     * @param array<int, array<string, string>> $translationsIndexedByKeyAndLocale
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function expandProductSearchAttributeWithLocalizedKeys(
        array $localeTransfers,
        array $translationsIndexedByKeyAndLocale,
        ProductSearchAttributeTransfer $productSearchAttributeTransfer
    ): ProductSearchAttributeTransfer {
        foreach ($localeTransfers as $localeTransfer) {
            $translation = $translationsIndexedByKeyAndLocale[$localeTransfer->getIdLocaleOrFail()][$productSearchAttributeTransfer->getKeyOrFail()] ?? null;
            $localizedProductSearchAttributeKeyTransfer = new LocalizedProductSearchAttributeKeyTransfer();
            $localizedProductSearchAttributeKeyTransfer
                ->setLocaleName($localeTransfer->getLocaleNameOrFail())
                ->setKeyTranslation($translation);

            $productSearchAttributeTransfer->addLocalizedKey($localizedProductSearchAttributeKeyTransfer);
        }

        return $productSearchAttributeTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\TranslationTransfer> $translationTransfers
     *
     * @return array<int, array<string, string>>
     */
    protected function getTranslationValuesIndexedByGlossaryKeyAndLocale(array $translationTransfers): array
    {
        $translationsIndexedByKeyAndLocale = [];

        foreach ($translationTransfers as $translationTransfer) {
            $key = $translationTransfer->getGlossaryKeyOrFail()->getKeyOrFail();
            $translationsIndexedByKeyAndLocale[$translationTransfer->getFkLocaleOrFail()][$key] = $translationTransfer->getValueOrFail();
        }

        return $translationsIndexedByKeyAndLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractGlossaryKeysFromProductSearchAttributeCollectionTransfer(
        ProductSearchAttributeCollectionTransfer $productSearchAttributeCollectionTransfer
    ): array {
        $glossaryKeys = [];

        foreach ($productSearchAttributeCollectionTransfer->getProductSearchAttributes() as $productSearchAttributeTransfer) {
            if (!$productSearchAttributeTransfer->getKey()) {
                continue;
            }

            $glossaryKeys[] = $productSearchAttributeTransfer->getKeyOrFail();
        }

        return $glossaryKeys;
    }
}
