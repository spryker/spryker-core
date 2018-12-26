<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductDiscontinuedRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface;
use Spryker\Glue\ProductDiscontinuedRestApi\Processor\Expander\ConcreteProductsResourceExpander;
use Spryker\Glue\ProductDiscontinuedRestApi\Processor\Expander\ConcreteProductsResourceExpanderInterface;

class ProductDiscontinuedRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductDiscontinuedRestApi\Processor\Expander\ConcreteProductsResourceExpanderInterface
     */
    public function createConcreteProductsResourceExpander(): ConcreteProductsResourceExpanderInterface
    {
        return new ConcreteProductsResourceExpander($this->getProductDiscontinuedStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface
     */
    public function getProductDiscontinuedStorageClient(): ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedRestApiDependencyProvider::CLIENT_PRODUCT_DISCONTINUED_STORAGE);
    }
}
