<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsAbstractProductsResourceExpander;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsAbstractProductsResourceExpanderInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsConcreteProductsResourceExpander;
use Spryker\Glue\ProductReviewsRestApi\Processor\Expander\ProductReviewsConcreteProductsResourceExpanderInterface;

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
     * @return \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface
     */
    public function getProductReviewStorageClient(): ProductReviewsRestApiToProductReviewStorageClientInterface
    {
        return $this->getProvidedDependency(ProductReviewsRestApiDependencyProvider::CLIENT_PRODUCT_REVIEW_STORAGE);
    }
}
