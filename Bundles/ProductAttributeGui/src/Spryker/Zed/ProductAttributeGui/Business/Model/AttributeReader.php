<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductAttributeGui\Dependency\Service\ProductAttributeGuiToUtilEncodingInterface;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeReader implements AttributeReaderInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\Service\ProductAttributeGuiToUtilEncodingInterface
     */
    protected $serviceEncoding;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\Service\ProductAttributeGuiToUtilEncodingInterface $serviceEncoding
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductAttributeGuiToUtilEncodingInterface $serviceEncoding
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->serviceEncoding = $serviceEncoding;
    }

    /**
     * @param array $localizedAttributes
     *
     * @return string
     */
    public function encodeJsonAttributes(array $localizedAttributes)
    {
        return (string)$this->serviceEncoding->encodeJson($localizedAttributes);
    }

    /**
     * @param string $localizedAttributesJson
     *
     * @return array
     */
    public function decodeJsonAttributes($localizedAttributesJson)
    {
        return (array)$this->serviceEncoding->decodeJson($localizedAttributesJson, true);
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function getMetaAttributesByValues(array $values)
    {
        $query = $this->queryMetaAttributes($values);
        $query->setFormatter(new ArrayFormatter());
        $data = $query->find();

        $results = [];
        foreach ($data as $entity) {
            unset($entity[ProductAttributeGuiConfig::ID_PRODUCT_ATTRIBUTE_KEY]);
            $results[$entity[ProductAttributeGuiConfig::KEY]] = $entity;
        }

        return $results;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function getAttributesByValues(array $values)
    {
        $query = $this->queryAttributeValues($values);
        return $this->loadPdoStatement($query);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function getProductAbstractEntity($idProductAbstract)
    {
        return $this->productQueryContainer->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->joinSpyProductAbstractLocalizedAttributes()
            ->findOne();
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function getProductEntity($idProduct)
    {
        return $this->productQueryContainer->queryProduct()
            ->filterByIdProduct($idProduct)
            ->joinSpyProductLocalizedAttributes()
            ->findOne();
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10)
    {
        $results = [];
        $query = $this->querySuggestKeys($searchText, $limit);

        foreach ($query->find() as $entity) {
            unset($entity[ProductAttributeGuiConfig::ID_PRODUCT_ATTRIBUTE_KEY]);
            $results[] = $entity;
        }

        return $results;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $productAttributes)
    {
        $keys = [];
        foreach ($productAttributes as $idLocale => $localizedAttributes) {
            $keys = array_merge($keys, array_keys($localizedAttributes));
        }

        return array_unique($keys);
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $productAttributes)
    {
        $values = [];
        foreach ($productAttributes as $idLocale => $localizedAttributes) {
            $values = array_merge($values, array_values($localizedAttributes));
        }

        return array_unique($values);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $query
     *
     * @return array
     */
    protected function loadPdoStatement(Criteria $query)
    {
        $pdoStatement = $query->doSelect();

        $results = [];
        while ($data = $pdoStatement->fetch()) {
            $item = $this->hydrateAttributeItem($data);
            $results[] = $item;
        }

        return $results;
    }

    /**
     * @param array $productAttributes
     * @param bool|null $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function queryAttributeValues(array $productAttributes = [], $isSuper = null)
    {
        $query = $this->productManagementQueryContainer
            ->queryProductAttributeKey()
            ->useSpyProductManagementAttributeQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductManagementAttributeValueQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductManagementAttributeValueTranslationQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY, 'id_product_attribute_key')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'id_product_management_attribute')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'id_product_management_attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE_TRANSLATION, 'id_product_management_attribute_value_translation')
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, 'attribute_key')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_VALUE, 'attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation')
            ->orderBy(SpyProductAttributeKeyTableMap::COL_KEY)
            ->orderBy(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE)
            ->orderBy(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION);

        $query = $this->appendAttributeCriteria($query, $productAttributes);

        if ($isSuper !== null) {
            $query->filterByIsSuper($isSuper);
        }

        return $query;
    }

    /**
     * ProductAttributes format
     * [
     *   [default] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $productAttributes
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function appendAttributeCriteria(SpyProductAttributeKeyQuery $query, array $productAttributes)
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultCriterion */
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultLocalizedCriterion */
        $defaultCriterion = null;
        $defaultLocalizedCriterion = null;
        $criteria = new Criteria();

        foreach ($productAttributes as $idLocale => $localizedAttributes) {
            foreach ($localizedAttributes as $key => $value) {
                $criterionValue = $criteria->getNewCriterion(
                    SpyProductManagementAttributeValueTableMap::COL_VALUE,
                    '%' . mb_strtolower($value) . '%',
                    Criteria::LIKE
                );

                $criterionTranslation = $criteria->getNewCriterion(
                    SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION,
                    '%' . mb_strtolower($value) . '%',
                    Criteria::LIKE
                );

                $criterionValue->addOr($criterionTranslation);

                $defaultCriterion = $this->appendOrCriterion($criterionValue, $defaultCriterion);
            }
        }

        $productAttributeKeyCriterion = $this->createAttributeKeysInCriterion(
            $productAttributes,
            $criteria,
            $defaultCriterion
        );

        $criteria->addAnd($productAttributeKeyCriterion);
        $criteria->setIgnoreCase(true);

        $query->setIgnoreCase(true);
        $query->mergeWith($criteria, Criteria::LOGICAL_AND);

        return $query;
    }

    /**
     * @param array $productAttributes
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultCriterion
     *
     * @return mixed
     */
    protected function createAttributeKeysInCriterion(array $productAttributes, Criteria $criteria, AbstractCriterion $defaultCriterion)
    {
        $keys = $this->extractKeysFromAttributes($productAttributes);

        $productAttributeKeyCriterion = $criteria->getNewCriterion(
            SpyProductAttributeKeyTableMap::COL_KEY,
            $keys,
            Criteria::IN
        );
        $productAttributeKeyCriterion->addAnd($defaultCriterion);

        return $productAttributeKeyCriterion;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function hydrateAttributeItem(array $data)
    {
        $keys = [
            'id_product_attribute_key',
            'key',
            'is_super',
            'id_product_attribute_key',
            'id_product_management_attribute',
            'id_product_management_attribute_value',
            'id_product_management_attribute_value_translation',
            'attribute_key',
            'attribute_value',
            'fk_locale',
            'translation',
            'uppercase_key',
            'uppercase_value',
        ];

        $result = array_combine($keys, array_values($data));

        return $result;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $criterionToAppend
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null $defaultCriterion
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function appendOrCriterion($criterionToAppend, AbstractCriterion $defaultCriterion = null)
    {
        if ($defaultCriterion === null) {
            $defaultCriterion = $criterionToAppend;
        } else {
            $defaultCriterion->addOr($criterionToAppend);
        }

        return $defaultCriterion;
    }

    /**
     * @param array $productAttributes
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria|\Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function queryMetaAttributes(array $productAttributes)
    {
        $keys = $this->extractKeysFromAttributes($productAttributes);

        $query = $this->productManagementQueryContainer
            ->queryProductAttributeKey()
            ->leftJoinSpyProductManagementAttribute()
            ->filterByKey_In($keys)
            ->setIgnoreCase(true)
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeGuiConfig::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeGuiConfig::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeGuiConfig::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeGuiConfig::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeGuiConfig::INPUT_TYPE);

        return $query;
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function querySuggestKeys($searchText, $limit = 10)
    {
        $query = $this->productQueryContainer->queryProductAttributeKey()
            ->filterByIsSuper(false)
            ->useSpyProductManagementAttributeQuery()
            ->endUse()
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeGuiConfig::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeGuiConfig::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeGuiConfig::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeGuiConfig::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeGuiConfig::INPUT_TYPE)
            ->orderByKey()
            ->setFormatter(new ArrayFormatter())
            ->limit($limit);

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }

}
