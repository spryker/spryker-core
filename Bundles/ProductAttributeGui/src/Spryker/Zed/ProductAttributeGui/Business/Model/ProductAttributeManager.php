<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeTableMap;
use PDO;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig;

class ProductAttributeManager implements ProductAttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface
     */
    protected $attributeReader;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeWriterInterface
     */
    protected $attributeWriter;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeWriterInterface $attributeWriter
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        AttributeReaderInterface $attributeReader,
        AttributeWriterInterface $attributeWriter
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->attributeReader = $attributeReader;
        $this->attributeWriter = $attributeWriter;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        $query = $this->attributeReader->queryProductAttributeValues($values);
        $results = $this->attributeReader->load($query);

        return $results;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        $query = $this->attributeReader->queryMetaAttributes($values);

        $query
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, 'key')
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, 'is_super')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'attribute_id')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, 'allow_input')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, 'input_type')
            ->setFormatter(new ArrayFormatter());

        $data = $query->find();

        $results = [];
        foreach ($data as $entity) {
            unset($entity['id_product_attribute_key']);
            $results[$entity['key']] = $entity;
        }

        return $results;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        $productAbstractEntity = $this->getProductAbstractEntity($idProductAbstract);

        $localizedAttributes = [];
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateProductAbstractAttributes($productAbstractEntity, $localizedAttributes);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText, $limit)
    {
        $results = [];
        $query = $this->productQueryContainer
            ->queryProductAttributeKey()
            ->filterByIsSuper(false)
            ->useSpyProductManagementAttributeQuery()
            ->endUse()
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, 'key')
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, 'is_super')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'attribute_id')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, 'allow_input')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, 'input_type')
            ->setFormatter(new ArrayFormatter())
            ->limit($limit);

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        foreach ($query->find() as $entity) {
            unset($entity['id_product_attribute_key']);
            $results[$entity['attribute_id']] = $entity;
        }

        return $results;
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
            $localeCode = $attribute['locale_code'];
            $key = $attribute['key'];
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
     * @param string $localizedAttributesJson
     *
     * @return array
     */
    protected function decodeJsonAttributes($localizedAttributesJson)
    {
        $attributesDecoded = (array)json_decode($localizedAttributesJson, true);  //TODO util

        return $attributesDecoded;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntity($idProductAbstract)
    {
        return $this->productQueryContainer->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->joinSpyProductAbstractLocalizedAttributes()
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAttributeEntity
     * @param array $localizedAttributes
     *
     * @return array
     */
    protected function generateProductAbstractAttributes(SpyProductAbstract $productAttributeEntity, array $localizedAttributes)
    {
        $attributes = $this->decodeJsonAttributes($productAttributeEntity->getAttributes());
        $attributes = [ProductAttributeGuiConfig::DEFAULT_LOCALE => $attributes] + $localizedAttributes;

        return $attributes;
    }

}
