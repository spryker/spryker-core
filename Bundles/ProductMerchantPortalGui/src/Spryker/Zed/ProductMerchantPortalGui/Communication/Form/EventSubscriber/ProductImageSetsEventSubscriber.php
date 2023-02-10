<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductImageSetsEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected const FIELD_IMAGE_SETS_FRONT = 'imageSetsFront';

    /**
     * @var string
     */
    protected const FIELD_IMAGE_SETS = 'imageSets';

    /**
     * @var string
     */
    protected const FIELD_IMAGE_SET_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_ID_PRODUCT_IMAGE_SET = 'idProductImageSet';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_IMAGES = 'productImages';

    /**
     * @var string
     */
    protected const FIELD_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const FIELD_ID_LOCALE = 'idLocale';

    /**
     * @var string
     */
    protected const FIELD_LOCALE_NAME = 'localeName';

    /**
     * @var string
     */
    protected const FIELD_ORIGINAL_INDEX = 'originalIndex';

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $product = $event->getData();

        if (!$product) {
            return;
        }

        $frontImageSetsData = $product[static::FIELD_IMAGE_SETS_FRONT] ?? [];

        $newImageSets = $this->findNewImageSets($frontImageSetsData);
        $oldImageSets = $this->findOldImageSetsIndexedByOriginalIndex($frontImageSetsData);

        unset($product[static::FIELD_IMAGE_SETS_FRONT]);

        ksort($oldImageSets);
        $product[static::FIELD_IMAGE_SETS] = array_merge($oldImageSets, $newImageSets);

        $event->setData($product);
    }

    /**
     * @param array<array<string, mixed>> $imageSetsFront
     *
     * @return array<array<string, mixed>>
     */
    protected function findNewImageSets(array $imageSetsFront): array
    {
        $newImageSets = [];

        foreach ($imageSetsFront as $imageSets) {
            [$idLocale, $localeName, $imageSets] = $this->parseFrontendImageSetsData($imageSets);

            foreach ($imageSets as $imageSet) {
                if (isset($imageSet[static::FIELD_ID_PRODUCT_IMAGE_SET])) {
                    continue;
                }

                $newImageSets[] = [
                    static::FIELD_IMAGE_SET_NAME => $imageSet[static::FIELD_IMAGE_SET_NAME],
                    static::FIELD_PRODUCT_IMAGES => $imageSet[static::FIELD_PRODUCT_IMAGES],
                    static::FIELD_LOCALE => $idLocale,
                ];
            }
        }

        return $newImageSets;
    }

    /**
     * @param array<array<string, mixed>> $imageSetsFront
     *
     * @return array<array<string, mixed>>
     */
    protected function findOldImageSetsIndexedByOriginalIndex(array $imageSetsFront): array
    {
        $oldImageSets = [];

        foreach ($imageSetsFront as $imageSets) {
            [$idLocale, $localeName, $imageSets] = $this->parseFrontendImageSetsData($imageSets);

            foreach ($imageSets as $imageSet) {
                if (!isset($imageSet[static::FIELD_ID_PRODUCT_IMAGE_SET])) {
                    continue;
                }

                $oldImageSets[$imageSet[static::FIELD_ORIGINAL_INDEX]] = [
                    static::FIELD_IMAGE_SET_NAME => $imageSet[static::FIELD_IMAGE_SET_NAME],
                    static::FIELD_ID_PRODUCT_IMAGE_SET => $imageSet[static::FIELD_ID_PRODUCT_IMAGE_SET],
                    static::FIELD_PRODUCT_IMAGES => $imageSet[static::FIELD_PRODUCT_IMAGES],
                    static::FIELD_LOCALE => $idLocale,
                ];
            }
        }

        return $oldImageSets;
    }

    /**
     * @param array<string, mixed> $imageSetsData
     *
     * @return array<mixed>
     */
    protected function parseFrontendImageSetsData(array $imageSetsData): array
    {
        $idLocale = $imageSetsData[static::FIELD_ID_LOCALE] ?? '';
        $localeName = $imageSetsData[static::FIELD_LOCALE_NAME] ?? '';

        unset($imageSetsData[static::FIELD_ID_LOCALE], $imageSetsData[static::FIELD_LOCALE_NAME]);

        return [$idLocale, $localeName, $imageSetsData];
    }
}
