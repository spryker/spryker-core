<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
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
                    (int)$idLocale
                ],
                [
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                    SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE
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
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()
            ->createProductAttributeKeyQuery()
            ->joinSpyProductManagementAttribute();
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

}
