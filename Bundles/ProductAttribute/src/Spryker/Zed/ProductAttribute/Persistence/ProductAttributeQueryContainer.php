<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributePersistenceFactory getFactory()
 */
class ProductAttributeQueryContainer extends AbstractQueryContainer implements ProductAttributeQueryContainerInterface
{
    public const KEY = 'product_attribute_key';
    public const IS_SUPER = 'is_super';
    public const ATTRIBUTE_ID = 'attribute_id';
    public const ALLOW_INPUT = 'allow_input';
    public const INPUT_TYPE = 'input_type';
    public const ID_PRODUCT_ATTRIBUTE_KEY = 'id_product_attribute_key';
    public const LOCALE_CODE = 'locale_code';

    /**
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute()
    {
        return $this->getFactory()->createProductManagementAttributeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue()
    {
        return $this->getFactory()->createProductManagementAttributeValueQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()->createProductAttributeKeyQuery();
    }

    /**
     * @api
     *
     * @param string[] $keys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKeys(array $keys)
    {
        return $this->getFactory()
            ->createProductAttributeKeyQuery()
            ->filterByKey_In($keys);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueQuery()
    {
        return $this->getFactory()->createProductManagementAttributeValueQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslation()
    {
        return $this->getFactory()->createProductManagementAttributeValueTranslationQuery();
    }

    /**
     * @api
     *
     * @param array $attributeKeys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryMetaAttributesByKeys(array $attributeKeys)
    {
        return $this->queryProductAttributeKey()
            ->leftJoinSpyProductManagementAttribute()
            ->filterByKey_In($attributeKeys)
            ->setIgnoreCase(true);
    }

    /**
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function querySuggestKeys($searchText, $limit = 10)
    {
        $query = $this->queryProductAttributeKey()
            ->filterByIsSuper(false)
            ->useSpyProductManagementAttributeQuery()
            ->endUse()
            ->limit($limit);

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int|null $offset
     * @param int $limit
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueWithTranslation(
        $idProductManagementAttribute,
        $idLocale,
        $searchText = '',
        $offset = null,
        $limit = 10
    ) {

        $query = $this->getFactory()
            ->createProductManagementAttributeValueQuery()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->addJoin([
                SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                (int)$idLocale,
            ], [
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE,
                ], Criteria::LEFT_JOIN)
            ->clearSelectColumns()
            ->withColumn($idLocale, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation');

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductManagementAttributeValueTableMap::COL_VALUE . ') LIKE ?', $term, PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        if ($offset !== null) {
            $query->setOffset($offset);
        }

        $query->setLimit($limit);

        return $query;
    }

    /**
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys($searchText = '', $limit = 10)
    {
        $query = $this->queryProductAttributeKey()
            ->addSelectColumn(SpyProductAttributeKeyTableMap::COL_KEY)
            ->useSpyProductManagementAttributeQuery(null, Criteria::LEFT_JOIN)
            ->filterByIdProductManagementAttribute(null)
            ->endUse();

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslationById($idProductManagementAttribute)
    {
        return $this->queryProductManagementAttributeValueTranslation()
            ->joinSpyProductManagementAttributeValue()
            ->useSpyProductManagementAttributeValueQuery()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->endUse();
    }

    /**
     * @api
     *
     * @param array $attributes
     * @param bool|null $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryAttributeValues(array $attributes = [], $isSuper = null)
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $query */
        $query = $this->queryProductAttributeKey()
            ->useSpyProductManagementAttributeQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductManagementAttributeValueQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductManagementAttributeValueTranslationQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        $query = $this->appendAttributeValuesCriteria($query, $attributes);

        if ($isSuper !== null) {
            $query->filterByIsSuper($isSuper);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function queryProductManagementAttributeById($idProductManagementAttribute)
    {
        return $this
            ->queryProductManagementAttribute()
            ->findOneByIdProductManagementAttribute($idProductManagementAttribute);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductAttributeCollection()
    {
        return $this
            ->queryProductManagementAttribute()
            ->innerJoinSpyProductAttributeKey();
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueByAttributeId($idProductManagementAttribute)
    {
        return $this
            ->queryProductManagementAttributeValue()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute);
    }

    /**
     * $attributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $query
     * @param array $attributes
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function appendAttributeValuesCriteria(SpyProductAttributeKeyQuery $query, array $attributes)
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultCriterion */
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultLocalizedCriterion */
        $defaultCriterion = null;
        $defaultLocalizedCriterion = null;
        $criteria = new Criteria();

        foreach ($attributes as $idLocale => $localizedAttributes) {
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
            $attributes,
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
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $criterionToAppend
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null $defaultCriterion
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function appendOrCriterion($criterionToAppend, ?AbstractCriterion $defaultCriterion = null)
    {
        if ($defaultCriterion === null) {
            return $criterionToAppend;
        }

        $defaultCriterion->addOr($criterionToAppend);

        return $defaultCriterion;
    }

    /**
     * @param array $keys
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $defaultCriterion
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function createAttributeKeysInCriterion(array $keys, Criteria $criteria, AbstractCriterion $defaultCriterion)
    {
        $productAttributeKeyCriterion = $criteria->getNewCriterion(
            SpyProductAttributeKeyTableMap::COL_KEY,
            $keys,
            Criteria::IN
        );
        $productAttributeKeyCriterion->addAnd($defaultCriterion);

        return $productAttributeKeyCriterion;
    }
}
