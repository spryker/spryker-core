<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Search;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpClientInterface getClient()
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class ProductConcreteCatalogSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats SearchHttp Concrete Products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        return $this->getClient()->formatProductConcreteCatalogHttpSearchResult($searchResult);
    }
}
