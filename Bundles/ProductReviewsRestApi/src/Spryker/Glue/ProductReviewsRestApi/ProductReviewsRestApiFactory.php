<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Creator\ProductReviewCreator;
use Spryker\Glue\ProductReviewsRestApi\Processor\Creator\ProductReviewCreatorInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewResourceRelationshipExpander;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsAbstractProductsResourceExpander;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsAbstractProductsResourceExpanderInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsConcreteProductsResourceExpander;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsConcreteProductsResourceExpanderInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapper;
use Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReader;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;

class ProductReviewsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsConcreteProductsResourceExpanderInterface
     */
    public function createProductReviewsConcreteProductsResourceExpander(): ProductReviewsConcreteProductsResourceExpanderInterface
    {
        return new ProductReviewsConcreteProductsResourceExpander($this->getProductReviewStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsAbstractProductsResourceExpanderInterface
     */
    public function createProductReviewsAbstractProductsResourceExpander(): ProductReviewsAbstractProductsResourceExpanderInterface
    {
        return new ProductReviewsAbstractProductsResourceExpander($this->getProductReviewStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewResourceRelationshipExpanderInterface
     */
    public function createProductReviewResourceRelationshipExpander(): ProductReviewResourceRelationshipExpanderInterface
    {
        return new ProductReviewResourceRelationshipExpander($this->createProductReviewReader());
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface
     */
    public function createProductReviewReader(): ProductReviewReaderInterface
    {
        return new ProductReviewReader(
            $this->createProductReviewMapper(),
            $this->getResourceBuilder(),
            $this->getProductStorageClient(),
            $this->getProductReviewClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Creator\ProductReviewCreatorInterface
     */
    public function createProductReviewCreator(): ProductReviewCreatorInterface
    {
        return new ProductReviewCreator(
            $this->getResourceBuilder(),
            $this->getProductReviewClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Processor\Mapper\ProductReviewMapperInterface
     */
    public function createProductReviewMapper(): ProductReviewMapperInterface
    {
        return new ProductReviewMapper();
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface
     */
    public function getProductReviewStorageClient(): ProductReviewsRestApiToProductReviewStorageClientInterface
    {
        return $this->getProvidedDependency(ProductReviewsRestApiDependencyProvider::CLIENT_PRODUCT_REVIEW_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductReviewsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductReviewsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    public function getProductReviewClient(): ProductReviewsRestApiToProductReviewClientInterface
    {
        return $this->getProvidedDependency(ProductReviewsRestApiDependencyProvider::CLIENT_PRODUCT_REVIEW);
    }
}
