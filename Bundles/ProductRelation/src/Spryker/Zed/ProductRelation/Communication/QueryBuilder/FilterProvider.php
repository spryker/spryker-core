<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\QueryBuilder;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class FilterProvider implements FilterProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    public function __construct(ProductRelationQueryContainerInterface $productRelationQueryContainer)
    {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $filters = $this->buildProductFilters();
        $filters = array_merge($filters, $this->buildCategoryFilters());
        $filters = array_merge($filters, $this->buildProductAttributeFilters());

        return $filters;
    }

    /**
     * @return array
     */
    protected function buildCategoryFilters()
    {
        return [
           [
               'id' => 'product_category_name',
               'field' => SpyCategoryAttributeTableMap::COL_NAME,
               'label' => 'category',
               'type' => 'string',
               'input' => 'text',
               'operators' => $this->getTextOperators(),
           ],
        ];
    }

    /**
     * @return array
     */
    protected function buildProductFilters()
    {
        return [
            [
                'id' => 'product_sku',
                'field' => 'product_sku',
                'label' => 'sku',
                'type' => 'string',
                'input' => 'text',
                'operators' => $this->getTextOperators(),
            ],
            [
                'id' => 'product_name',
                'field' => 'product_name',
                'label' => 'name',
                'type' => 'string',
                'input' => 'text',
                'operators' => $this->getTextOperators(),
            ],
            [
                'id' => 'product_created_at',
                'field' => 'product_created_at',
                'label' => 'created at',
                'type' => 'date',
                'input' => 'text',
                'validation' => [
                    'format' => 'yyyy-mm-dd',
                ],
                'plugin' => 'datepicker',
                'plugin_config' => [
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => 'linked',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ],
                'operators' => $this->getTextOperators(),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getTextOperators()
    {
        return [
            'equal',
            'greater',
            'greater_or_equal',
            'less',
            'less_or_equal',
            'in',
        ];
    }

    /**
     * @return array
     */
    protected function buildProductAttributeFilters()
    {
        $productAttributeKeys = $this->findProductAttributes();

        $filters = [];
        foreach ($productAttributeKeys as $productAttributeKeyEntity) {
             $filters[] = [
                 'id' => $this->buildAttributeKey($productAttributeKeyEntity->getKey()),
                 'field' => SpyProductAbstractTableMap::COL_ATTRIBUTES,
                 'label' => $productAttributeKeyEntity->getKey(),
                 'type' => 'string',
                 'input' => 'text',
                 'optgroup' => 'attributes',
                 'operators' => $this->getTextOperators(),
             ];
        }

        return $filters;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findProductAttributes()
    {
        $productAttributeKeys = $this->productRelationQueryContainer
            ->queryProductAttributeKey()
            ->find();

        return $productAttributeKeys;
    }

    /**
     * @param string $persistedAttributeKey
     *
     * @return string
     */
    protected function buildAttributeKey($persistedAttributeKey)
    {
        return 'product.json.' . $persistedAttributeKey;
    }
}
