<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class ProductSuggestionSearchHttpResultFormatterPlugin extends ProductSearchHttpResultFormatterPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'product_abstract';

    /**
     * {@inheritDoc}
     * - Formats abstract products in result.
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer|mixed $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        $abstractProducts = $searchResult->getMatchedItems();
        $abstractProducts = $this->extendWithProductIds($abstractProducts);
        $abstractProducts = $this->filterNotFoundInAbstractProducts($abstractProducts);
        $abstractProducts = $this->getFactory()->createResultProductMapper()->mapSearchHttpProductsToOriginalProducts($searchResult, $abstractProducts);

        return $abstractProducts;
    }
}
