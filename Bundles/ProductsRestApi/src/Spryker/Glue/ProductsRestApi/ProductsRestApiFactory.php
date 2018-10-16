<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReader;
use Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReader;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapper;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapper;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface;

class ProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface
     */
    public function createAbstractProductsResourceMapper(): AbstractProductsResourceMapperInterface
    {
        return new AbstractProductsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface
     */
    public function createAbstractProductsReader(): AbstractProductsReaderInterface
    {
        return new AbstractProductsReader(
            $this->getProductStorageClient(),
            $this->getResourceBuilder(),
            $this->createAbstractProductsResourceMapper(),
            $this->createConcreteProductsReader()
        );
    }

    /**
     * @return \Spryker\Glue\ProductsRestApi\Processor\Mapper\ConcreteProductsResourceMapperInterface
     */
    public function createConcreteProductsResourceMapper(): ConcreteProductsResourceMapperInterface
    {
        return new ConcreteProductsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    public function createConcreteProductsReader(): ConcreteProductsReaderInterface
    {
        return new ConcreteProductsReader(
            $this->getProductStorageClient(),
            $this->getResourceBuilder(),
            $this->createConcreteProductsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
