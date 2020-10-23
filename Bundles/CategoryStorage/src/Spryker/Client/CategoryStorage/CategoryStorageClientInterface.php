<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use ArrayObject;
use Elastica\ResultSet;

interface CategoryStorageClientInterface
{
    /**
     * Specification:
     *  - Return category node storage data by locale name.
     *
     * @api
     *
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale);

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
     * - Returns formatted category node tree with docCount information.
     *
     * @api
     *
     * @param \Elastica\ResultSet $searchResult
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    public function formatResultSetToCategoryTreeFilter(ResultSet $searchResult): ArrayObject;
}
