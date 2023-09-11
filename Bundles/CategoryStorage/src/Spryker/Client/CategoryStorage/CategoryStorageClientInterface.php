<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

interface CategoryStorageClientInterface
{
    /**
     * Specification:
     *  - Return category node storage data by locale name and store name.
     *
     * @api
     *
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategories(string $localeName, string $storeName): ArrayObject;

    /**
     * Specification:
     *  - Return category node storage data by id category node, locale name and store name.
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer;

    /**
     * Specification:
     * - Retrieves category nodes storage data by array of id category node, locale name and store name.
     * - Returns category nodes indexed by node ids.
     *
     * @api
     *
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array;

    /**
     * Specification:
     * - Returns category nodes with the `docCount` relevant for the result set.
     * - Retrieves category tree from storage by locale name and store name.
     * - Recursively merges each category node in the category tree with `docCount` taken from the `ResultSet` aggregations.
     *
     * @api
     *
     * @param array $docCountAggregation
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, string $localeName, string $storeName): ArrayObject;

    /**
     * Specification:
     * - Requires `ProductAbstractCategoryStorageCollectionTransfer.productAbstractCategory.category.categoryNodeId` to be set.
     * - Expands product categories with their parent category ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer
     */
    public function expandProductCategoriesWithParentIds(
        ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer,
        string $localeName,
        string $storeName
    ): ProductAbstractCategoryStorageCollectionTransfer;

    /**
     * Specification:
     * - Returns category nodes with attached product count.
     * - Retrieves category tree from storage by locale name and store name.
     * - Recursively merges each category node in the category tree with product count taken from the category facet aggregations in $searchResult.
     * - Compatible only with SearchHttpResponseTransfer and used for SearchHttp module search results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function formatSearchHttpCategoryTree(SearchHttpResponseTransfer $searchResult): ArrayObject;

    /**
     * Specification:
     * - Returns categories.
     * - Retrieves category tree from storage by locale name and store name.
     * - Recursively filters each category node in the category tree with category name taken from the categories in $suggestionsSearchHttpResponseTransfer.
     * - Compatible only with SuggestionsSearchHttpResponseTransfer and used for SearchHttp module search results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer
     *
     * @return array<int, mixed>
     */
    public function formatSuggestionsSearchHttpCategory(SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer): array;
}
