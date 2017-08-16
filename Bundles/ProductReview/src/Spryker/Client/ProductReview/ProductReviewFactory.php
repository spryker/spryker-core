<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\Query\ProductReviewsQueryPlugin;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander\PaginatedProductReviewsQueryExpanderPlugin;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander\RatingAggregationQueryExpanderPlugin;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\PaginatedProductReviewsResultFormatter;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\ProductReviewsResultFormatterPlugin;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\RatingAggregationResultFormatter;
use Spryker\Client\ProductReview\Storage\ProductAbstractReviewStorageReader;
use Spryker\Client\ProductReview\Zed\ProductReviewStub;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\Search\SearchClient;
use Spryker\Shared\ProductReview\KeyBuilder\ProductAbstractReviewResourceKeyBuilder;

class ProductReviewFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductReview\Zed\ProductReviewStub
     */
    public function createProductReviewStub()
    {
        return new ProductReviewStub($this->getZedRequestClient());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getProductReviewsQueryPlugin(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        $productReviewsQueryPlugin = new ProductReviewsQueryPlugin($productReviewSearchRequestTransfer);

        return $this->getSearchClient()->expandQuery(
            $productReviewsQueryPlugin,
            $this->getProductReviewsQueryExpanderPlugins(),
            $productReviewSearchRequestTransfer->getRequestParams()
        );
    }

    /**
     * @return \Spryker\Client\Search\SearchClient TODO: fix typehint to bridge interface
     */
    public function getSearchClient()
    {
        // TODO: get from dependency provider
        return new SearchClient();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getProductReviewsQueryExpanderPlugins()
    {
        // TODO: get from dependency provider
        return [
            new LocalizedQueryExpanderPlugin(),
            new PaginatedProductReviewsQueryExpanderPlugin(),
            new RatingAggregationQueryExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getProductReviewsSearchResultFormatterPlugins()
    {
        // TODO: get from dependency provider
        return [
            new ProductReviewsResultFormatterPlugin(),
            new PaginatedProductReviewsResultFormatter(),
            new RatingAggregationResultFormatter(),
        ];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder()
    {
        // TODO: get from dependency provider
        $paginationConfigBuilder = new PaginationConfigBuilder();
        $paginationConfigBuilder->setPagination($this->getConfig()->getPaginationConfig());

        return $paginationConfigBuilder;
    }

    /**
     * @return \Spryker\Client\ProductReview\Storage\ProductAbstractReviewStorageReaderInterface
     */
    public function createProductAbstractReviewStorageReader()
    {
        return new ProductAbstractReviewStorageReader(
            $this->getStorageClient(),
            $this->createProductAbstractReviewResourceKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\ProductReview\ProductReviewConfig|\Spryker\Client\Kernel\AbstractBundleConfig
     */
    public function getConfig()
    {
        return parent::getConfig();
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductAbstractReviewResourceKeyBuilder()
    {
        return new ProductAbstractReviewResourceKeyBuilder();
    }

}
