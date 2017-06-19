<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementPersistenceFactory getFactory()
 */
class ProductManagementQueryContainer extends AbstractQueryContainer implements ProductManagementQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute()
    {
        return $this->getFactory()->createProductManagementAttributeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue()
    {
        return $this->getFactory()->createProductManagementAttributeValueQuery();
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale)
    {
        return $this->getFactory()
            ->createProductManagementAttributeValueQuery()
            ->clearSelectColumns()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->addJoin(
                [
                    SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    (int)$idLocale,
                ],
                [
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE,
                ],
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'id_product_management_attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_VALUE, 'value')
            ->withColumn($idLocale, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation');
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string|null $attributeValueOrTranslation
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFindAttributeByValueOrTranslation($idProductManagementAttribute, $idLocale, $attributeValueOrTranslation = null)
    {
        $query = $this->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale);

        if ($attributeValueOrTranslation !== null) {
            $query->where(
                'LOWER(' . SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION . ') = ?',
                mb_strtolower($attributeValueOrTranslation),
                PDO::PARAM_STR
            )
            ->_or()
            ->where(
                'LOWER(' . SpyProductManagementAttributeValueTableMap::COL_VALUE . ') = ?',
                mb_strtolower($attributeValueOrTranslation),
                PDO::PARAM_STR
            );
        }

        return $query;
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
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueQuery()
    {
        return $this->getFactory()
            ->createProductManagementAttributeValueQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslation()
    {
        return $this->getFactory()
            ->createProductManagementAttributeValueTranslationQuery();
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslationById($idProductManagementAttribute)
    {
        return $this
            ->queryProductManagementAttributeValueTranslation()
            ->joinSpyProductManagementAttributeValue()
            ->useSpyProductManagementAttributeValueQuery()
                ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->endUse();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys()
    {
        return $this
            ->queryProductAttributeKey()
            ->addSelectColumn(SpyProductAttributeKeyTableMap::COL_KEY)
            ->useSpyProductManagementAttributeQuery(null, Criteria::LEFT_JOIN)
                ->filterByIdProductManagementAttribute(null)
            ->endUse();
    }

    /**
     * @api
     *
     * @param bool $isSuper
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueTranslationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductAttributeValues($isSuper = false)
    {
        return $this
            ->queryProductManagementAttributeValueTranslation()
            ->joinWithSpyProductManagementAttributeValue()
            ->useSpyProductManagementAttributeValueQuery(null, Criteria::LEFT_JOIN)
                ->joinWithSpyProductManagementAttribute()
                ->useSpyProductManagementAttributeQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithSpyProductAttributeKey()
                    ->useSpyProductAttributeKeyQuery()
                        ->filterByIsSuper($isSuper)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY, 'id_product_attribute_key')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'id_product_management_attribute')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_FK_PRODUCT_ATTRIBUTE_KEY, 'fk_product_attribute_key')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'id_product_management_attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE, 'fk_product_management_attribute')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE_TRANSLATION, 'id_product_management_attribute_value_translation')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'fk_product_management_attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE, 'fk_product_management_attribute_fk_locale')
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, 'key')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_VALUE, 'value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation')
            ->orderBy(SpyProductAttributeKeyTableMap::COL_KEY);
    }

}
