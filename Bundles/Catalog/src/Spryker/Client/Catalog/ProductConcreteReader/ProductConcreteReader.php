<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\ProductConcreteReader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Catalog\CatalogConfig;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\Search\SearchClientInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Client\Catalog\CatalogConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
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
     * @param \Spryker\Client\Catalog\CatalogConfig $config
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $productConcretePageSearchQueryPlugin
     * @param array $productConcretePageSearchQueryExpanderPlugins
     * @param array $productConcretePageSearchResultFormatterPlugins
     */
    public function __construct(
        CatalogConfig $config,
        SearchClientInterface $searchClient,
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
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
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
    protected function expandQuery(array $requestParams = []): void
    {
        $this->searchClient->expandQuery(
            $this->productConcretePageSearchQueryPlugin,
            $this->productConcretePageSearchQueryExpanderPlugins,
            $requestParams
        );
    }
}
