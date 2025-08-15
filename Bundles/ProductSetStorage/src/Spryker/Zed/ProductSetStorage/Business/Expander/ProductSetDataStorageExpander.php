<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Zed\ProductSetStorage\Business\Reader\GlossaryReaderInterface;

class ProductSetDataStorageExpander implements ProductSetDataStorageExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_SPY_PRODUCT_SET = 'SpyProductSet';

    /**
     * @var string
     */
    protected const KEY_SPY_PRODUCT_IMAGE_SETS = 'SpyProductImageSets';

    /**
     * @var string
     */
    protected const KEY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGES = 'SpyProductImageSetToProductImages';

    /**
     * @var string
     */
    protected const KEY_SPY_PRODUCT_IMAGE = 'SpyProductImage';

    /**
     * @var string
     */
    protected const KEY_SPY_LOCALE = 'SpyLocale';

    /**
     * @var string
     */
    protected const KEY_ID_PRODUCT_IMAGE = 'id_product_image';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_SMALL = 'alt_text_small';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_LARGE = 'alt_text_large';

    /**
     * @var string
     */
    protected const KEY_LOCALE_NAME = 'locale_name';

    /**
     * @param \Spryker\Zed\ProductSetStorage\Business\Reader\GlossaryReaderInterface $glossaryReader
     */
    public function __construct(protected GlossaryReaderInterface $glossaryReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param array<mixed> $spyProductSetLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function expandProductSetDataStorageWithProductImageAlternativeTexts(
        ProductSetDataStorageTransfer $productSetDataStorageTransfer,
        array $spyProductSetLocalizedEntity
    ): ProductSetDataStorageTransfer {
        $productSetDataStorageTransfer->setImageSets(
            $this->expandProductImagesWithProductImageAlternativeTexts(
                $productSetDataStorageTransfer->getImageSets(),
                $spyProductSetLocalizedEntity,
            ),
        );

        return $productSetDataStorageTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer> $productImageSetStorageTransfers
     * @param array<mixed> $spyProductSetLocalizedEntity
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer>
     */
    protected function expandProductImagesWithProductImageAlternativeTexts(
        ArrayObject $productImageSetStorageTransfers,
        array $spyProductSetLocalizedEntity
    ): ArrayObject {
        $glossaryKeysIndexedByIdProductImage = $this->getGlossaryKeysIndexedByIdProductImage($spyProductSetLocalizedEntity);
        $localeName = $spyProductSetLocalizedEntity[static::KEY_SPY_LOCALE][static::KEY_LOCALE_NAME] ?? null;

        if (!$glossaryKeysIndexedByIdProductImage || !$localeName) {
            return $productImageSetStorageTransfers;
        }

        $translationsIndexedByGlossaryKey = $this->glossaryReader
            ->getTranslationsIndexedByGlossaryKey($this->getGlossaryKeys($glossaryKeysIndexedByIdProductImage), $localeName);

        foreach ($productImageSetStorageTransfers as $productImageSetStorageTransfer) {
            foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
                $idProductImage = $productImageStorageTransfer->getIdProductImage();

                if (!isset($glossaryKeysIndexedByIdProductImage[$idProductImage])) {
                    continue;
                }

                $altTextSmallGlossaryKey = $glossaryKeysIndexedByIdProductImage[$idProductImage][static::KEY_ALT_TEXT_SMALL];
                $altTextLargeGlossaryKey = $glossaryKeysIndexedByIdProductImage[$idProductImage][static::KEY_ALT_TEXT_LARGE];

                $productImageStorageTransfer->setAltTextSmall($translationsIndexedByGlossaryKey[$altTextSmallGlossaryKey] ?? null)
                    ->setAltTextLarge($translationsIndexedByGlossaryKey[$altTextLargeGlossaryKey] ?? null);
            }
        }

        return $productImageSetStorageTransfers;
    }

    /**
     * @param array<mixed> $spyProductSetLocalizedEntity
     *
     * @return array<int, array<string, string>>
     */
    protected function getGlossaryKeysIndexedByIdProductImage(array $spyProductSetLocalizedEntity): array
    {
        $productImageSetsData = $spyProductSetLocalizedEntity[static::KEY_SPY_PRODUCT_SET][static::KEY_SPY_PRODUCT_IMAGE_SETS] ?? [];
        $glossaryKeysIndexedByIdProductImage = [];

        if (!$productImageSetsData) {
            return $glossaryKeysIndexedByIdProductImage;
        }

        foreach ($productImageSetsData as $productImageSetData) {
            if (!isset($productImageSetData[static::KEY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGES])) {
                continue;
            }
            foreach ($productImageSetData[static::KEY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGES] as $productImageSetToProductImageData) {
                if (!isset($productImageSetToProductImageData[static::KEY_SPY_PRODUCT_IMAGE])) {
                    continue;
                }

                $productImage = $productImageSetToProductImageData[static::KEY_SPY_PRODUCT_IMAGE];

                if (!isset($productImage[static::KEY_ALT_TEXT_SMALL]) || !isset($productImage[static::KEY_ALT_TEXT_LARGE])) {
                    continue;
                }

                $glossaryKeysIndexedByIdProductImage[(int)$productImage[static::KEY_ID_PRODUCT_IMAGE]] = [
                    static::KEY_ALT_TEXT_SMALL => (string)$productImage[static::KEY_ALT_TEXT_SMALL],
                    static::KEY_ALT_TEXT_LARGE => (string)$productImage[static::KEY_ALT_TEXT_LARGE],
                ];
            }
        }

        return $glossaryKeysIndexedByIdProductImage;
    }

    /**
     * @param array<int, array<string, string>> $glossaryKeysIndexedByIdProductImage
     *
     * @return list<string>
     */
    protected function getGlossaryKeys(array $glossaryKeysIndexedByIdProductImage): array
    {
        $glossaryKeys = [];

        foreach ($glossaryKeysIndexedByIdProductImage as $imageGlossaryKeys) {
            $glossaryKeys[] = $imageGlossaryKeys[static::KEY_ALT_TEXT_SMALL];
            $glossaryKeys[] = $imageGlossaryKeys[static::KEY_ALT_TEXT_LARGE];
        }

        return $glossaryKeys;
    }
}
