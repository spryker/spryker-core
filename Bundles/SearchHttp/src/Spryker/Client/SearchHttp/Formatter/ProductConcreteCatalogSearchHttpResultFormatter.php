<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Formatter;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

class ProductConcreteCatalogSearchHttpResultFormatter implements ProductConcreteCatalogSearchHttpResultFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function formatResult(SuggestionsSearchHttpResponseTransfer $searchResult): array
    {
        $result = [];
        foreach ($searchResult->getMatchedItems() as $product) {
            $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())->fromArray($product, true);
            $productConcretePageSearchTransfer->setAbstractSku($product['product_abstract_sku']);
            $result[] = $productConcretePageSearchTransfer;
        }

        return $result;
    }
}
