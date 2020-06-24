<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Expander\ProductAbstractByContentProductAbstractListExpander;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Expander\ProductAbstractByContentProductAbstractListExpanderInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReader;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReaderInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReader;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReaderInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilder;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentProductAbstractListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListReaderInterface
     */
    public function createContentProductAbstractListReader(): ContentProductAbstractListReaderInterface
    {
        return new ContentProductAbstractListReader(
            $this->getContentProductClient(),
            $this->createContentProductAbstractListRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader\ContentProductAbstractListProductReaderInterface
     */
    public function createContentProductAbstractListProductReader(): ContentProductAbstractListProductReaderInterface
    {
        return new ContentProductAbstractListProductReader(
            $this->getContentProductClient(),
            $this->createContentProductAbstractListRestResponseBuilder(),
            $this->getStoreClient(),
            $this->getProductRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface
     */
    public function createContentProductAbstractListRestResponseBuilder(): ContentProductAbstractListRestResponseBuilderInterface
    {
        return new ContentProductAbstractListRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->getProductRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Expander\ProductAbstractByContentProductAbstractListExpanderInterface
     */
    public function createProductAbstractByContentProductAbstractListExpander(): ProductAbstractByContentProductAbstractListExpanderInterface
    {
        return new ProductAbstractByContentProductAbstractListExpander(
            $this->createContentProductAbstractListProductReader()
        );
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    public function getContentProductClient(): ContentProductAbstractListsRestApiToContentProductClientInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::CLIENT_CONTENT_PRODUCT);
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    public function getProductRestApiResource(): ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::RESOURCE_PRODUCTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface
     */
    public function getStoreClient(): ContentProductAbstractListsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractListsRestApiDependencyProvider::CLIENT_STORE);
    }
}
