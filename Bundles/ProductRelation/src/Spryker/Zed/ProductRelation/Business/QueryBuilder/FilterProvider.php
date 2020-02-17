<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\QueryBuilder;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class FilterProvider implements FilterProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     */
    public function __construct(ProductRelationRepositoryInterface $productRelationRepository)
    {
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $filters = $this->buildProductFilters();
        $filters = array_merge($filters, $this->buildCategoryFilters());
        $filters = array_merge($filters, $this->buildProductAttributeFilters());

        return $filters;
    }

    /**
     * @return array
     */
    protected function buildCategoryFilters(): array
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
    protected function buildProductFilters(): array
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
     * @return string[]
     */
    protected function getTextOperators(): array
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
    protected function buildProductAttributeFilters(): array
    {
        $productAttributeKeys = $this->findProductAttributes();

        $filters = [];
        foreach ($productAttributeKeys as $productAttributeKeyTransfer) {
            $filters[] = [
                'id' => $this->buildAttributeKey($productAttributeKeyTransfer->getKey()),
                'field' => SpyProductAbstractTableMap::COL_ATTRIBUTES,
                'label' => $productAttributeKeyTransfer->getKey(),
                'type' => 'string',
                'input' => 'text',
                'optgroup' => 'attributes',
                'operators' => $this->getTextOperators(),
            ];
        }

        return $filters;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer[]
     */
    protected function findProductAttributes(): array
    {
        return $this->productRelationRepository->findProductAttributes();
    }

    /**
     * @param string $persistedAttributeKey
     *
     * @return string
     */
    protected function buildAttributeKey($persistedAttributeKey): string
    {
        return 'product.json.' . $persistedAttributeKey;
    }
}
