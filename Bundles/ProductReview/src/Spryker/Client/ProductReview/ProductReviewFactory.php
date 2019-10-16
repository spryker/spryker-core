<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductReview\Plugin\Elasticsearch\Query\ProductReviewsQueryPlugin;
use Spryker\Client\ProductReview\Storage\ProductAbstractReviewStorageReader;
use Spryker\Client\ProductReview\Zed\ProductReviewStub;
use Spryker\Shared\ProductReview\KeyBuilder\ProductAbstractReviewResourceKeyBuilder;

class ProductReviewFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductReview\Zed\ProductReviewStubInterface
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
    public function createProductReviewsQueryPlugin(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        $productReviewsQueryPlugin = new ProductReviewsQueryPlugin($productReviewSearchRequestTransfer);

        return $this->getSearchClient()->expandQuery(
            $productReviewsQueryPlugin,
            $this->getProductReviewsQueryExpanderPlugins(),
            $productReviewSearchRequestTransfer->getRequestParams()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createAllProductReviewsQueryPlugin(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        $productReviewsQueryPlugin = new ProductReviewsQueryPlugin($productReviewSearchRequestTransfer);

        return $this->getSearchClient()->expandQuery(
            $productReviewsQueryPlugin,
            $this->getAllProductReviewsQueryExpanderPlugins(),
            $productReviewSearchRequestTransfer->getRequestParams()
        );
    }

    /**
     * @return \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToSearchInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getProductReviewsQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::PRODUCT_REVIEWS_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getAllProductReviewsQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::PRODUCT_ALL_REVIEWS_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getProductReviewsSearchResultFormatterPlugins()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::PRODUCT_REVIEWS_SEARCH_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder()
    {
        $paginationConfigBuilder = $this->getProvidedDependency(ProductReviewDependencyProvider::PAGINATION_CONFIG_BUILDER_PLUGIN);
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
     * @deprecated Use getProductReviewConfig() instead.
     *
     * @return \Spryker\Client\ProductReview\ProductReviewConfig
     */
    public function getConfig()
    {
        return $this->getProductReviewConfig();
    }

    /**
     * Exposes the protected getConfig() method as public method.
     *
     * @return \Spryker\Client\ProductReview\ProductReviewConfig
     */
    public function getProductReviewConfig()
    {
        /** @var \Spryker\Client\ProductReview\ProductReviewConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToZedRequestInterface
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
