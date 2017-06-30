<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

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
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $attributeData = $this->getAttributesDataToSave($attributes);
        $attributesJson = $this->getNonLocalizedAttributesAsJson($attributeData);
        unset($attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $productAbstractEntity = $this->reader->getProductAbstractEntity($idProductAbstract);
        $productAbstractEntity->setAttributes($attributesJson);
        $productAbstractEntity->save();

        $this->saveLocalizedAttributes($productAbstractEntity->getSpyProductAbstractLocalizedAttributess(), $attributeData);
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $attributeData = $this->getAttributesDataToSave($attributes);
        $attributesJson = $this->getNonLocalizedAttributesAsJson($attributeData);
        unset($attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $productEntity = $this->reader->getProductEntity($idProduct);
        $productEntity->setAttributes($attributesJson);
        $productEntity->save();

        $this->saveLocalizedAttributes($productEntity->getSpyProductLocalizedAttributess(), $attributeData);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $localizedAttributeEntityCollection $localizedAttributeEntityCollection
     * @param array $attributes
     *
     * @return void
     */
    protected function saveLocalizedAttributes($localizedAttributeEntityCollection, array $attributes)
    {
        foreach ($localizedAttributeEntityCollection as $localizedAttributeEntity) {
            foreach ($attributes as $localeCode => $attributeData) {
                if ($localizedAttributeEntity->getFkLocale() !== (int)$localeCode) {
                    continue;
                }

                $attributesJson = $this->reader->encodeJsonAttributes($attributeData);
                $localizedAttributeEntity->setAttributes($attributesJson);
                $localizedAttributeEntity->save();
            }
        }
    }

    /**
     * @param array $attributes
     * @param bool $returnKeysToRemove
     *
     * @return array
     */
    protected function getAttributesDataToSave(array $attributes, $returnKeysToRemove = false)
    {
        $attributeData = [];
        $keysToRemove = [];

        foreach ($attributes as $attribute) {
            $localeCode = $attribute[ProductAttributeGuiConfig::LOCALE_CODE];
            $key = $attribute[ProductAttributeGuiConfig::KEY];
            $value = trim($attribute['value']);

            if ($value !== '') {
                $attributeData[$localeCode][$key] = $value;
            }
            else {
                $keysToRemove[$localeCode][] = $key;
            }
        }

        if ($returnKeysToRemove) {
            return $keysToRemove;
        }

        return $attributeData;
    }

    /**
     * @param array $attributeData
     *
     * @return string
     */
    protected function getNonLocalizedAttributesAsJson(array $attributeData)
    {
        $productAbstractAttributes = $attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE];
        unset($attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $attributesJson = json_encode($productAbstractAttributes);

        return $attributesJson;
    }

}
