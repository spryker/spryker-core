<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementPersistenceFactory getFactory()
 */
class ProductManagementQueryContainer extends AbstractQueryContainer implements ProductManagementQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute()
    {
        return $this->getFactory()->createProductManagementAttributeQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue()
    {
        return $this->getFactory()->createProductManagementAttributeValueQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
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
            ->withColumn((string)$idLocale, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation');
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()->createProductAttributeKeyQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueQuery()
    {
        return $this->getFactory()
            ->createProductManagementAttributeValueQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslation()
    {
        return $this->getFactory()
            ->createProductManagementAttributeValueTranslationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
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
     * {@inheritDoc}
     *
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
}
