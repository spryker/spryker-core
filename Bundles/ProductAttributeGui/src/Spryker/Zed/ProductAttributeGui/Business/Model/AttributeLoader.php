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
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeLoader implements AttributeLoaderInterface
{

    const DEFAULT_LOCALE = '_';

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $query
     *
     * @return array
     */
    public function load(Criteria $query)
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
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria|\Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryMetaAttributes(array $productAttributes)
    {
        $keys = $this->extractKeys($productAttributes);
        $query = $this->productManagementQueryContainer
            ->queryProductAttributeKey()
            ->leftJoinSpyProductManagementAttribute()
            ->filterByKey_In($keys)
            ->setIgnoreCase(true);

        return $query;
    }

    /**
     * @param array $productAttributes
     * @param bool|null $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductAttributeValues(array $productAttributes = [], $isSuper = null)
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

        $query = $this->updateQueryWithAttributeCriteria($query, $productAttributes);

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
    protected function updateQueryWithAttributeCriteria(SpyProductAttributeKeyQuery $query, array $productAttributes)
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
        $keys = $this->extractKeys($productAttributes);

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
     * @return array
     */
    protected function extractKeys(array $productAttributes)
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
    protected function extractValues($productAttributes)
    {
        $values = [];
        foreach ($productAttributes as $idLocale => $localizedAttributes) {
            $values = array_merge($values, array_values($localizedAttributes));
        }

        return array_unique($values);
    }

}
