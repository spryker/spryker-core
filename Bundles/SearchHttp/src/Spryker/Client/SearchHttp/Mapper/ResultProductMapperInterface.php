<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Mapper;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

interface ResultProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer|\Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchHttpResponseTransfer
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    public function mapSearchHttpProductsToOriginalProducts(
        SearchHttpResponseTransfer|SuggestionsSearchHttpResponseTransfer $searchHttpResponseTransfer,
        array $products
    ): array;
}
