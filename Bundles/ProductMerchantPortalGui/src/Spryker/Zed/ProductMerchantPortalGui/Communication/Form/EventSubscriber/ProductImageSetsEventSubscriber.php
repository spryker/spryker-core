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
    protected const FIELD_IMAGE_SETS_FRONT = 'imageSetsFront';
    protected const FIELD_IMAGE_SETS = 'imageSets';
    protected const FIELD_IMAGE_SET_NAME = 'name';
    protected const FIELD_ID_PRODUCT_IMAGE_SET = 'idProductImageSet';
    protected const FIELD_PRODUCT_IMAGES = 'productImages';
    protected const FIELD_LOCALE = 'locale';
    protected const FIELD_ID_LOCALE = 'idLocale';
    protected const FIELD_LOCALE_NAME = 'localeName';
    protected const FIELD_ORIGINAL_INDEX = 'originalIndex';

    /**
     * @return string[]
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

        $frontImageSetsData = $product[self::FIELD_IMAGE_SETS_FRONT] ?? [];

        $newImageSets = $this->findNewImageSets($frontImageSetsData);
        $oldImageSets = $this->findOldImageSetsIndexedByOriginalIndex($frontImageSetsData);

        unset($product[self::FIELD_IMAGE_SETS_FRONT]);

        ksort($oldImageSets);
        $product[self::FIELD_IMAGE_SETS] = array_merge($oldImageSets, $newImageSets);

        $event->setData($product);
    }

    /**
     * @param array $imageSetsFront
     *
     * @return array
     */
    protected function findNewImageSets(array $imageSetsFront): array
    {
        $newImageSets = [];

        foreach ($imageSetsFront as $imageSets) {
            [$idLocale, $localeName, $imageSets] = $this->parseFrontendImageSetsData($imageSets);

            foreach ($imageSets as $imageSet) {
                if (isset($imageSet[self::FIELD_ID_PRODUCT_IMAGE_SET])) {
                    continue;
                }

                $newImageSets[] = [
                    self::FIELD_IMAGE_SET_NAME => $imageSet[self::FIELD_IMAGE_SET_NAME],
                    self::FIELD_PRODUCT_IMAGES => $imageSet[self::FIELD_PRODUCT_IMAGES],
                    self::FIELD_LOCALE => $idLocale,
                ];
            }
        }

        return $newImageSets;
    }

    /**
     * @param array $imageSetsFront
     *
     * @return array
     */
    protected function findOldImageSetsIndexedByOriginalIndex(array $imageSetsFront): array
    {
        $oldImageSets = [];

        foreach ($imageSetsFront as $imageSets) {
            [$idLocale, $localeName, $imageSets] = $this->parseFrontendImageSetsData($imageSets);

            foreach ($imageSets as $imageSet) {
                if (!isset($imageSet[self::FIELD_ID_PRODUCT_IMAGE_SET])) {
                    continue;
                }

                $oldImageSets[$imageSet[self::FIELD_ORIGINAL_INDEX]] = [
                    self::FIELD_IMAGE_SET_NAME => $imageSet[self::FIELD_IMAGE_SET_NAME],
                    self::FIELD_ID_PRODUCT_IMAGE_SET => $imageSet[self::FIELD_ID_PRODUCT_IMAGE_SET],
                    self::FIELD_PRODUCT_IMAGES => $imageSet[self::FIELD_PRODUCT_IMAGES],
                    self::FIELD_LOCALE => $idLocale,
                ];
            }
        }

        return $oldImageSets;
    }

    /**
     * @param array $imageSetsData
     *
     * @return array
     */
    protected function parseFrontendImageSetsData(array $imageSetsData): array
    {
        $idLocale = $imageSetsData[self::FIELD_ID_LOCALE] ?? '';
        $localeName = $imageSetsData[self::FIELD_LOCALE_NAME] ?? '';

        unset($imageSetsData[self::FIELD_ID_LOCALE], $imageSetsData[self::FIELD_LOCALE_NAME]);

        return [$idLocale, $localeName, $imageSetsData];
    }
}
