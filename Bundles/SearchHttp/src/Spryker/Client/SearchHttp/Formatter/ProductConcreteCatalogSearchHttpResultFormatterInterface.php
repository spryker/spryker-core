<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Formatter;

use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

interface ProductConcreteCatalogSearchHttpResultFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function formatResult(SuggestionsSearchHttpResponseTransfer $searchResult): array;
}
