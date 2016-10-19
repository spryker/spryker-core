<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Persistence;

use Orm\Zed\Util\Persistence\Map\SpyUtilAttributeValueTableMap;
use Orm\Zed\Util\Persistence\Map\SpyUtilAttributeValueTranslationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Util\Persistence\UtilPersistenceFactory getFactory()
 */
class UtilQueryContainer extends AbstractQueryContainer implements UtilQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeQuery
     */
    public function queryUtilAttribute()
    {
        return $this->getFactory()->createUtilAttributeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValue()
    {
        return $this->getFactory()->createUtilAttributeValueQuery();
    }

    /**
     * @api
     *
     * @param int $idUtilAttribute
     * @param int $idLocale
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValueWithTranslation($idUtilAttribute, $idLocale)
    {
        return $this->getFactory()
            ->createUtilAttributeValueQuery()
            ->clearSelectColumns()
            ->filterByFkUtilAttribute($idUtilAttribute)
            ->addJoin(
                [
                    SpyUtilAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    (int)$idLocale
                ],
                [
                    SpyUtilAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    SpyUtilAttributeValueTranslationTableMap::COL_FK_LOCALE
                ],
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyUtilAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'id_product_management_attribute_value')
            ->withColumn(SpyUtilAttributeValueTableMap::COL_VALUE, 'value')
            ->withColumn($idLocale, 'fk_locale')
            ->withColumn(SpyUtilAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation');
    }

    /**
     * @api
     *
     * @param int $idUtilAttribute
     * @param int $idLocale
     * @param string|null $attributeValueOrTranslation
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFindAttributeByValueOrTranslation($idUtilAttribute, $idLocale, $attributeValueOrTranslation = null)
    {
        $query = $this->queryUtilAttributeValueWithTranslation($idUtilAttribute, $idLocale);

        if ($attributeValueOrTranslation !== null) {
            $query->where(
                'LOWER(' . SpyUtilAttributeValueTranslationTableMap::COL_TRANSLATION . ') = ?',
                mb_strtolower($attributeValueOrTranslation),
                \PDO::PARAM_STR
            )
            ->_or()
            ->where(
                'LOWER(' . SpyUtilAttributeValueTableMap::COL_VALUE . ') = ?',
                mb_strtolower($attributeValueOrTranslation),
                \PDO::PARAM_STR
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
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValueQuery()
    {
        return $this->getFactory()
            ->createUtilAttributeValueQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueTranslationQuery
     */
    public function queryUtilAttributeValueTranslation()
    {
        return $this->getFactory()
            ->createUtilAttributeValueTranslationQuery();
    }

    /**
     * @api
     *
     * @param int $idUtilAttribute
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueTranslationQuery
     */
    public function queryUtilAttributeValueTranslationById($idUtilAttribute)
    {
        return $this
            ->queryUtilAttributeValueTranslation()
            ->joinSpyUtilAttributeValue()
            ->useSpyUtilAttributeValueQuery()
                ->filterByFkUtilAttribute($idUtilAttribute)
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
            ->useSpyUtilAttributeQuery(null, Criteria::LEFT_JOIN)
                ->filterByIdUtilAttribute(null)
            ->endUse();
    }

}
