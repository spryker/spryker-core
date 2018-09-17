<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch\ProductConcreteReader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface
     */
    protected $productConcretePageSearchQueryPlugin;

    /***
     * @var \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected $productConcretePageSearchQueryExpanderPlugins;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected $productConcretePageSearchResultFormatterPlugins;

    /**
     * @param \Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface $searchClient
     * @param \Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface $productConcretePageSearchQueryPlugin
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $productConcretePageSearchQueryExpanderPlugins
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $productConcretePageSearchResultFormatterPlugins
     */
    public function __construct(
        ProductPageSearchToSearchClientInterface $searchClient,
        QueryInterface $productConcretePageSearchQueryPlugin,
        array $productConcretePageSearchQueryExpanderPlugins,
        array $productConcretePageSearchResultFormatterPlugins
    ) {
        $this->searchClient = $searchClient;
        $this->productConcretePageSearchQueryPlugin = $productConcretePageSearchQueryPlugin;
        $this->productConcretePageSearchQueryExpanderPlugins = $productConcretePageSearchQueryExpanderPlugins;
        $this->productConcretePageSearchResultFormatterPlugins = $productConcretePageSearchResultFormatterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $this->buildProductConcretePageSearchQueryPlugin($productConcreteCriteriaFilterTransfer);
        $this->expandQuery($productConcreteCriteriaFilterTransfer->getRequestParams() ?? []);

        return $this->searchClient->search($this->productConcretePageSearchQueryPlugin, $this->productConcretePageSearchResultFormatterPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return void
     */
    protected function buildProductConcretePageSearchQueryPlugin(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): void
    {
        if ($productConcreteCriteriaFilterTransfer->getSearchString()) {
            $this->productConcretePageSearchQueryPlugin->setSearchString($productConcreteCriteriaFilterTransfer->getSearchString());
        }

        if ($productConcreteCriteriaFilterTransfer->getLimit()) {
            $this->productConcretePageSearchQueryPlugin->setLimit($productConcreteCriteriaFilterTransfer->getLimit());
        }

        $this->productConcretePageSearchQueryPlugin->buildQuery();
    }

    /**
     * @param array $requestParams
     *
     * @return void
     */
    protected function expandQuery(array $requestParams = [])
    {
        $this->searchClient->expandQuery(
            $this->productConcretePageSearchQueryPlugin,
            $this->productConcretePageSearchQueryExpanderPlugins,
            $requestParams
        );
    }
}
