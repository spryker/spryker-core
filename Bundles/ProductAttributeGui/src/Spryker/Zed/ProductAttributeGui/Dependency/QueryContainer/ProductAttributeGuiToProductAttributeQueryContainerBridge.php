<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery;

class ProductAttributeGuiToProductAttributeQueryContainerBridge implements ProductAttributeGuiToProductAttributeQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface $productAttributeQueryContainer
     */
    public function __construct($productAttributeQueryContainer)
    {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey(): SpyProductAttributeKeyQuery
    {
        return $this->productAttributeQueryContainer
            ->queryProductAttributeKey();
    }

    /**
     * @param array<string> $keys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKeys(array $keys): SpyProductAttributeKeyQuery
    {
        return $this->productAttributeQueryContainer
            ->queryProductAttributeKeyByKeys($keys);
    }

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute(): SpyProductManagementAttributeQuery
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttribute();
    }

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue(): SpyProductManagementAttributeValueQuery
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValue();
    }

    /**
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
    ): SpyProductManagementAttributeValueQuery {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueWithTranslation(
                $idProductManagementAttribute,
                $idLocale,
                $searchText,
                $offset,
                $limit,
            );
    }
}
