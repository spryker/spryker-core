<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReader;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\UpSellingProductReader;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\UpSellingProductReaderInterface;

class UpSellingProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getCartsRestApiClient());
    }

    /**
     * @return \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\UpSellingProductReaderInterface
     */
    public function createUpSellingProductReader(): UpSellingProductReaderInterface
    {
        return new UpSellingProductReader(
            $this->createQuoteReader(),
            $this->getProductRelationStorageClient(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface
     */
    public function getProductRelationStorageClient(): UpSellingProductsRestApiToProductRelationStorageClientInterface
    {
        return $this->getProvidedDependency(UpSellingProductsRestApiDependencyProvider::CLIENT_PRODUCT_RELATION_STORAGE);
    }

    /**
     * @return \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): UpSellingProductsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(UpSellingProductsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToCartsRestApiClientInterface
     */
    public function getCartsRestApiClient(): UpSellingProductsRestApiToCartsRestApiClientInterface
    {
        return $this->getProvidedDependency(UpSellingProductsRestApiDependencyProvider::CLIENT_CARTS_REST_API);
    }
}
