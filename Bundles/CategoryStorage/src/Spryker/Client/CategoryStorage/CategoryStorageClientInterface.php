<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use ArrayObject;

interface CategoryStorageClientInterface
{
    /**
     * Specification:
     *  - Return category node storage data by locale name and store name.
     *
     * @api
     *
     * @param string $locale
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale, string $storeName);

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
    public function getCategoryNodeById($idCategoryNode, $localeName, string $storeName);

    /**
     * Specification:
     * - Returns category nodes storage data by array of id category node, locale name and store name.
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds($categoryNodeIds, $localeName, string $storeName): array;

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
     * @return \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]|\ArrayObject
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, string $localeName, string $storeName): ArrayObject;
}
