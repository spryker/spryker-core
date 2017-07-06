<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig;

class AttributeWriter implements AttributeWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface $reader
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        AttributeReaderInterface $reader,
        ProductAttributeGuiToLocaleInterface $localeFacade,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->reader = $reader;
        $this->localeFacade = $localeFacade;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $formattedAttributeData = $this->getAttributesDataToSave($attributes);

        $attributesJson = $this->getNonLocalizedAttributesAsJson($formattedAttributeData);
        unset($formattedAttributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $productAbstractEntity = $this->reader->getProductAbstractEntity($idProductAbstract);
        $productAbstractEntity->setAttributes($attributesJson);
        $productAbstractEntity->save();

        $query = $this->productQueryContainer->queryProductAbstractLocalizedAttributes($idProductAbstract);
        $this->saveLocalizedAttributes($query, $formattedAttributeData);
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $formattedAttributeData = $this->getAttributesDataToSave($attributes);

        $attributesJson = $this->getNonLocalizedAttributesAsJson($formattedAttributeData);
        unset($formattedAttributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE]);

        $productEntity = $this->reader->getProductEntity($idProduct);
        $productEntity->setAttributes($attributesJson);
        $productEntity->save();

        $query = $this->productQueryContainer->queryProductLocalizedAttributes($idProduct);
        $this->saveLocalizedAttributes($query, $formattedAttributeData);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery|\Propel\Runtime\ActiveQuery\Criteria $query
     * @param array $localizedAttributes
     *
     * @return void
     */
    protected function saveLocalizedAttributes(Criteria $query, array $localizedAttributes)
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $localizedDataToSave = [];
            $idLocale = $localeTransfer->getIdLocale();

            $queryToQuery = clone $query;
            $localizedAttributeEntity = $queryToQuery
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            if (array_key_exists($idLocale, $localizedAttributes)) {
                $localizedDataToSave = $localizedAttributes[$idLocale];
            }

            $attributesJson = $this->reader->encodeJsonAttributes($localizedDataToSave);
            $localizedAttributeEntity->setAttributes($attributesJson);
            $localizedAttributeEntity->save();
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
            } else {
                $keysToRemove[$localeCode][$key] = $key;
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
        $productAbstractAttributes = [];
        if (array_key_exists(ProductAttributeGuiConfig::DEFAULT_LOCALE, $attributeData)) {
            $productAbstractAttributes = $attributeData[ProductAttributeGuiConfig::DEFAULT_LOCALE];
        }

        return $this->reader->encodeJsonAttributes($productAbstractAttributes);
    }

}
