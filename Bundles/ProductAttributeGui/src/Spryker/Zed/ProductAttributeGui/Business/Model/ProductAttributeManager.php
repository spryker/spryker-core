<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ProductAttributeManager
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
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @return void
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        $abstractAttributes = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
            ->endUse()
            ->find();

        print_r($abstractAttributes->toArray());
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
