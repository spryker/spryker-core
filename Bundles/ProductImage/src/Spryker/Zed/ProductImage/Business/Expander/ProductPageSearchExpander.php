<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Expander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface;

class ProductPageSearchExpander implements ProductPageSearchExpanderInterface
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
     * @param \Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface|null $glossaryReader
     */
    public function __construct(
        protected ?GlossaryReaderInterface $glossaryReader = null
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageSearchTransferWithProductImageAlternativeTexts(
        ProductPageSearchTransfer $productPageSearchTransfer
    ): void {
        if (!$this->glossaryReader) {
            return;
        }

        $productPageSearchTransfer->setProductImages(
            $this->expandProductImagesWithProductImageAlternativeTexts(
                $productPageSearchTransfer->getProductImages(),
                $productPageSearchTransfer->getLocaleOrFail(),
            ),
        );
    }

    /**
     * @param list<array<string, mixed>> $productImages
     * @param string $localeName
     *
     * @return list<array<string, mixed>>
     */
    protected function expandProductImagesWithProductImageAlternativeTexts(array $productImages, string $localeName): array
    {
        $glossaryKeys = $this->getGlossaryKeys($productImages);

        if (!$glossaryKeys) {
            return $productImages;
        }

        $translationsIndexedByGlossaryKey = $this->glossaryReader->getTranslationsIndexedByGlossaryKey($glossaryKeys, $localeName);

        foreach ($productImages as &$productImage) {
            $productImage[static::KEY_ALT_TEXT_SMALL] = $translationsIndexedByGlossaryKey[$productImage[static::KEY_ALT_TEXT_SMALL]] ?? null;
            $productImage[static::KEY_ALT_TEXT_LARGE] = $translationsIndexedByGlossaryKey[$productImage[static::KEY_ALT_TEXT_LARGE]] ?? null;
        }

        return $productImages;
    }

    /**
     * @param list<array<string, mixed>> $productImages
     *
     * @return list<string>
     */
    protected function getGlossaryKeys(array $productImages): array
    {
        $glossaryKeys = [];

        foreach ($productImages as $productImage) {
            $glossaryKeys[] = $productImage[static::KEY_ALT_TEXT_SMALL];
            $glossaryKeys[] = $productImage[static::KEY_ALT_TEXT_LARGE];
        }

        return array_filter($glossaryKeys);
    }
}
