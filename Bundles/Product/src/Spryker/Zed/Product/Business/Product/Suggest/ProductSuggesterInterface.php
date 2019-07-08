<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Suggest;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;

interface ProductSuggesterInterface
{
    /**
     * @param string $suggestion
     * @param int|null $limit
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $suggestion, ?int $limit = null): array;

    /**
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getPaginatedProductAbstractSuggestions(string $suggestion, PaginationTransfer $paginationTransfer): ProductAbstractSuggestionCollectionTransfer;

    /**
     * @param string $suggestion
     * @param int|null $limit
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $suggestion, ?int $limit = null): array;
}
