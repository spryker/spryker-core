<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;

interface CmsSlotBlockProductCategoryGuiRepositoryInterface
{
    /**
     * @param int[]|null $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getProductAbstracts(?array $productAbstractIds = []): array;

    /**
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getPaginatedProductAbstractSuggestions(
        string $suggestion,
        PaginationTransfer $paginationTransfer
    ): ProductAbstractSuggestionCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategories(): CategoryCollectionTransfer;
}
