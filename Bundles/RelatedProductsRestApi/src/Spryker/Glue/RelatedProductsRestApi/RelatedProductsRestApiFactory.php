<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapper;
use Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapperInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\Reader\RelatedProductReader;
use Spryker\Glue\RelatedProductsRestApi\Processor\Reader\RelatedProductReaderInterface;

class RelatedProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Processor\Reader\RelatedProductReaderInterface
     */
    public function createRelatedProductReader(): RelatedProductReaderInterface
    {
        return new RelatedProductReader(
            $this->getProductStorageClient(),
            $this->getProductRelationStorageClient(),
            $this->getResourceBuilder(),
            $this->createRelatedProductsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapperInterface
     */
    public function createRelatedProductsResourceMapper(): RelatedProductsResourceMapperInterface
    {
        return new RelatedProductsResourceMapper();
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
}
