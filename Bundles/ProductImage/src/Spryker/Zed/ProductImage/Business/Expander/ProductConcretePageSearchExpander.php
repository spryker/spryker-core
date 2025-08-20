<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Expander;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface;

class ProductConcretePageSearchExpander implements ProductConcretePageSearchExpanderInterface
{
    /**
     * @param \Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface|null $glossaryReader
     */
    public function __construct(
        protected ?GlossaryReaderInterface $glossaryReader = null
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expandProductConcretePageSearchTransferWithProductImageAlternativeTexts(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        if (!$this->glossaryReader) {
            return $productConcretePageSearchTransfer;
        }

        $productConcretePageSearchTransfer->setImages(
            $this->expandProductImagesWithProductImageAlternativeTexts(
                $productConcretePageSearchTransfer->getImages(),
                $productConcretePageSearchTransfer->getLocaleOrFail(),
            ),
        );

        return $productConcretePageSearchTransfer;
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
            $productImage[ProductImageTransfer::ALT_TEXT_SMALL] = $translationsIndexedByGlossaryKey[$productImage[ProductImageTransfer::ALT_TEXT_SMALL]] ?? null;
            $productImage[ProductImageTransfer::ALT_TEXT_LARGE] = $translationsIndexedByGlossaryKey[$productImage[ProductImageTransfer::ALT_TEXT_LARGE]] ?? null;
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
            $glossaryKeys[] = $productImage[ProductImageTransfer::ALT_TEXT_SMALL];
            $glossaryKeys[] = $productImage[ProductImageTransfer::ALT_TEXT_LARGE];
        }

        return array_filter($glossaryKeys);
    }
}
