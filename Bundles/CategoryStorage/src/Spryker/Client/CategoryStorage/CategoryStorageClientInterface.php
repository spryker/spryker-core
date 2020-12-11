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
     *  - Return category node storage data by locale name.
     *  - Forward compatibility (from next major): only categories assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param string $locale
     * @param string|null $storeName the parameter is going to be required in the next major.
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale, ?string $storeName = null);

    /**
     * Specification:
     *  - Return category node storage data by id category node and locale name.
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName);

    /**
     * Specification:
     * - Returns category nodes storage data by array of id category node and locale name.
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName): array;

    /**
     * Specification:
     * - Returns category nodes with the `docCount` relevant for the result set.
     * - Retrieves category tree from storage by locale name.
     * - Recursively merges each category node in the category tree with `docCount` taken from the `ResultSet` aggregations.
     * - Forward compatibility (from next major): only categories assigned with passed $localeName and $storeName will be returned.
     *
     * @api
     *
     * @param array $docCountAggregation
     * @param string|null $localeName the parameter is going to be required in the next major.
     * @param string|null $storeName the parameter is going to be required in the next major.
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, ?string $localeName = null, ?string $storeName = null): ArrayObject;
}
