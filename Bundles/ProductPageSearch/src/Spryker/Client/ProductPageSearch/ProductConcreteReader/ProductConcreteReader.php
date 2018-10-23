<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch\ProductConcreteReader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface;
use Spryker\Client\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Client\ProductPageSearch\ProductPageSearchConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface
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
     * @param \Spryker\Client\ProductPageSearch\ProductPageSearchConfig $config
     * @param \Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface $searchClient
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $productConcretePageSearchQueryPlugin
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $productConcretePageSearchQueryExpanderPlugins
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $productConcretePageSearchResultFormatterPlugins
     */
    public function __construct(
        ProductPageSearchConfig $config,
        ProductPageSearchToSearchClientInterface $searchClient,
        QueryInterface $productConcretePageSearchQueryPlugin,
        array $productConcretePageSearchQueryExpanderPlugins,
        array $productConcretePageSearchResultFormatterPlugins
    ) {
        $this->config = $config;
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
        $this->mapFilters($productConcreteCriteriaFilterTransfer);
        $this->expandQuery($productConcreteCriteriaFilterTransfer->getRequestParams() ?? []);

        return $this->searchClient->search($this->productConcretePageSearchQueryPlugin, $this->productConcretePageSearchResultFormatterPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return void
     */
    protected function mapFilters(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): void
    {
        $this->mapSearchString($productConcreteCriteriaFilterTransfer);
        $this->mapLimit($productConcreteCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return void
     */
    protected function mapSearchString(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): void
    {
        if (!$this->productConcretePageSearchQueryPlugin instanceof SearchStringSetterInterface) {
            return;
        }

        if (empty($productConcreteCriteriaFilterTransfer->getSearchString())) {
            return;
        }

        $this->productConcretePageSearchQueryPlugin->setSearchString($productConcreteCriteriaFilterTransfer->getSearchString());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return void
     */
    protected function mapLimit(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): void
    {
        if (!$productConcreteCriteriaFilterTransfer->getLimit()) {
            return;
        }

        $requestParams = $productConcreteCriteriaFilterTransfer->getRequestParams() ?? [];
        $requestParams[$this->config->getItemsPerPageParameterName()] = $productConcreteCriteriaFilterTransfer->getLimit();

        $productConcreteCriteriaFilterTransfer->setRequestParams($requestParams);
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
