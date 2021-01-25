<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\RestApiResource\RelatedProductsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct\RelatedProductReader;
use Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct\RelatedProductReaderInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilder;
use Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilderInterface;

class RelatedProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct\RelatedProductReaderInterface
     */
    public function createRelatedProductReader(): RelatedProductReaderInterface
    {
        return new RelatedProductReader(
            $this->getProductStorageClient(),
            $this->getProductRelationStorageClient(),
            $this->createRelatedProductRestResponseBuilder(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilderInterface
     */
    public function createRelatedProductRestResponseBuilder(): RelatedProductRestResponseBuilderInterface
    {
        return new RelatedProductRestResponseBuilder(
            $this->getProductsRestApiResource(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface
     */
    public function getProductRelationStorageClient(): RelatedProductsRestApiToProductRelationStorageClientInterface
    {
        return $this->getProvidedDependency(RelatedProductsRestApiDependencyProvider::CLIENT_PRODUCT_RELATION_STORAGE);
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): RelatedProductsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(RelatedProductsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Dependency\RestApiResource\RelatedProductsRestApiToProductsRestApiResourceInterface
     */
    public function getProductsRestApiResource(): RelatedProductsRestApiToProductsRestApiResourceInterface
    {
        return $this->getProvidedDependency(RelatedProductsRestApiDependencyProvider::RESOURCE_PRODUCTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientInterface
     */
    public function getStoreClient(): RelatedProductsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(RelatedProductsRestApiDependencyProvider::CLIENT_STORE);
    }
}
