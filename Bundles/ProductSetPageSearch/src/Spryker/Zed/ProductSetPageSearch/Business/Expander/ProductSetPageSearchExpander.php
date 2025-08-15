<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business\Expander;

use Generated\Shared\Transfer\ProductSetPageSearchTransfer;
use Spryker\Zed\ProductSetPageSearch\Business\Reader\GlossaryReaderInterface;

class ProductSetPageSearchExpander implements ProductSetPageSearchExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_SMALL = 'alt_text_small';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_LARGE = 'alt_text_large';

    /**
     * @param \Spryker\Zed\ProductSetPageSearch\Business\Reader\GlossaryReaderInterface $glossaryReader
     */
    public function __construct(protected GlossaryReaderInterface $glossaryReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetPageSearchTransfer $productSetPageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetPageSearchTransfer
     */
    public function expandProductSetPageSearchWithProductImageAlternativeTexts(
        ProductSetPageSearchTransfer $productSetPageSearchTransfer
    ): ProductSetPageSearchTransfer {
        $productSetPageSearchTransfer->setImageSets(
            $this->expandProductImagesWithProductImageAlternativeTexts(
                $productSetPageSearchTransfer->getImageSets(),
                $productSetPageSearchTransfer->getLocaleOrFail(),
            ),
        );

        return $productSetPageSearchTransfer;
    }

    /**
     * @param array<string, list<array<string, mixed>>> $productImageSetsData
     * @param string $localeName
     *
     * @return array<string, list<array<string, mixed>>>
     */
    protected function expandProductImagesWithProductImageAlternativeTexts(array $productImageSetsData, string $localeName): array
    {
        $glossaryKeys = $this->getGlossaryKeys($productImageSetsData);

        if (!$glossaryKeys) {
            return $productImageSetsData;
        }

        $translationsIndexedByGlossaryKey = $this->glossaryReader->getTranslationsIndexedByGlossaryKey($glossaryKeys, $localeName);

        foreach ($productImageSetsData as &$productImageSetData) {
            foreach ($productImageSetData as &$productImage) {
                $productImage[static::KEY_ALT_TEXT_SMALL] = $translationsIndexedByGlossaryKey[$productImage[static::KEY_ALT_TEXT_SMALL]] ?? null;
                $productImage[static::KEY_ALT_TEXT_LARGE] = $translationsIndexedByGlossaryKey[$productImage[static::KEY_ALT_TEXT_LARGE]] ?? null;
            }
        }

        return $productImageSetsData;
    }

    /**
     * @param array<string, list<array<string, mixed>>> $productImageSetsData
     *
     * @return list<string>
     */
    protected function getGlossaryKeys(array $productImageSetsData): array
    {
        $glossaryKeys = [];

        foreach ($productImageSetsData as $productImageSetData) {
            foreach ($productImageSetData as $productImage) {
                $glossaryKeys[] = $productImage[static::KEY_ALT_TEXT_SMALL];
                $glossaryKeys[] = $productImage[static::KEY_ALT_TEXT_LARGE];
            }
        }

        return array_filter($glossaryKeys);
    }
}
