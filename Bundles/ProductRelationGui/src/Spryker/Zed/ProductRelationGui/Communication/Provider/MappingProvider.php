<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Provider;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;

class MappingProvider implements MappingProviderInterface
{
    protected const PATTERN_PRODUCT_ATTRIBUTE_KEY = 'product.json.%s';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected $productAttributeKeyQuery;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $productAttributeKeyQuery
     */
    public function __construct(SpyProductAttributeKeyQuery $productAttributeKeyQuery)
    {
        $this->productAttributeKeyQuery = $productAttributeKeyQuery;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    public function getMappings(): array
    {
        $mapping = $this->buildProductMapping();
        $mapping = array_merge($mapping, $this->buildProductAttributeMap());

        return $mapping;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    protected function buildProductMapping(): array
    {
        $mapping = [];

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_sku');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractTableMap::COL_SKU,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_name');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_created_at');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractTableMap::COL_CREATED_AT,
            SpyProductTableMap::COL_CREATED_AT,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        return $mapping;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    protected function buildProductAttributeMap(): array
    {
        $productAttributeKeys = $this->productAttributeKeyQuery->find();

        $attributeMap = [];
        foreach ($productAttributeKeys as $productAttributeKeyEntity) {
            $attributeKey = $this->buildAttributeKey($productAttributeKeyEntity->getKey());

            $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
            $propelQueryBuilderCriteriaMappingTransfer->setAlias($attributeKey);
            $propelQueryBuilderCriteriaMappingTransfer->setColumns([
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                SpyProductTableMap::COL_ATTRIBUTES,
                SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES,
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
            ]);

            $attributeMap[] = $propelQueryBuilderCriteriaMappingTransfer;
        }

        return $attributeMap;
    }

    /**
     * @param string $persistedAttributeKey
     *
     * @return string
     */
    protected function buildAttributeKey($persistedAttributeKey): string
    {
        return sprintf(static::PATTERN_PRODUCT_ATTRIBUTE_KEY, $persistedAttributeKey);
    }
}
