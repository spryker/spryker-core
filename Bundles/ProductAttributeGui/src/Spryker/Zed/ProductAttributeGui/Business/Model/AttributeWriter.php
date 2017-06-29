<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig;

class AttributeWriter implements AttributeWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface
     */
    protected $reader;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface $reader
     */
    public function __construct(AttributeReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param int $idProductAbstract
     * @param array $data
     *
     * @return void
     */
    public function updateProductAbstractAttributes($idProductAbstract, array $data)
    {
        $attributes = [];
        $keysToRemove = [];

        foreach ($data as $attribute) {
            $localeCode = $attribute[ProductAttributeGuiConfig::LOCALE_CODE];
            $key = $attribute[static::KEY];
            $value = trim($attribute['value']);

            if ($value !== '') {
                $attributes[$localeCode][$key] = $value;
            } else {
                $keysToRemove[$localeCode][] = $key;
            }
        }

        $attributesToSave = [];
        $productAbstractAttributes = $this->getProductAbstractAttributeValues($idProductAbstract);

        foreach ($attributes as $localeCode => $attributeData) {
            $currentAttributes = [];
            if (array_key_exists($localeCode, $productAbstractAttributes)) {
                $currentAttributes = $productAbstractAttributes[$localeCode];
            }

            $attributesToSave[$localeCode] = array_merge($currentAttributes, $attributeData);

            if (array_key_exists($localeCode, $keysToRemove)) {
                $attributesToSave[$localeCode] = array_filter($attributeData, function ($key) use ($keysToRemove, $localeCode) {
                    return in_array($key, $keysToRemove[$localeCode]) === false;
                }, ARRAY_FILTER_USE_KEY);
            }
        }

        $attributes = $attributesToSave[ProductAttributeGuiConfig::DEFAULT_LOCALE];
        unset($attributesToSave[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $attributesJson = json_encode($attributes);

        $productAbstractEntity = $this->getProductAbstractEntity($idProductAbstract);
        $productAbstractEntity->setAttributes($attributesJson);
        $productAbstractEntity->save();

        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            foreach ($attributesToSave as $localeCode => $attributeData) {
                if ($localizedAttributeEntity->getFkLocale() !== (int)$localeCode) {
                    continue;
                }

                $attributesJson = json_encode($attributeData);
                $localizedAttributeEntity->setAttributes($attributesJson);
                $localizedAttributeEntity->save();
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $attributeData = [];
        $keysToRemove = [];

        foreach ($attributes as $attribute) {
            $localeCode = $attribute[ProductAttributeGuiConfig::LOCALE_CODE];
            $key = $attribute[ProductAttributeGuiConfig::KEY];
            $value = trim($attribute['value']);

            if ($value !== '') {
                $attributeData[$localeCode][$key] = $value;
            } else {
                $keysToRemove[$localeCode][] = $key;
            }
        }

        $productAbstractAttributes = $attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE];
        unset($attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);
        $attributesJson = json_encode($productAbstractAttributes);

        $productAbstractEntity = $this->reader->getProductAbstractEntity($idProductAbstract);
        $productAbstractEntity->setAttributes($attributesJson);
        $productAbstractEntity->save();

        $this->saveAbstractLocalizedAttributes($productAbstractEntity, $attributeData);

        return $productAbstractEntity;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param array $attributes
     *
     * @return void
     */
    protected function saveAbstractLocalizedAttributes(SpyProductAbstract $productAbstractEntity, array $attributes)
    {
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            foreach ($attributes as $localeCode => $attributeData) {
                if ($localizedAttributeEntity->getFkLocale() !== (int)$localeCode) {
                    continue;
                }

                $attributesJson = json_encode($attributeData);
                $localizedAttributeEntity->setAttributes($attributesJson);
                $localizedAttributeEntity->save();
            }
        }
    }

}
