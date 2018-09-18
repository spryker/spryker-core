<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpander;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpander;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReader;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReaderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReader;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReaderInterface;

class ProductImageSetsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface
     */
    public function createAbstractProductImageSetsMapper(): AbstractProductImageSetsMapperInterface
    {
        return new AbstractProductImageSetsMapper();
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface
     */
    public function createConcreteProductImageSetsMapper(): ConcreteProductImageSetsMapperInterface
    {
        return new ConcreteProductImageSetsMapper();
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReaderInterface
     */
    public function createAbstractProductImageSetsReader(): AbstractProductImageSetsReaderInterface
    {
        return new AbstractProductImageSetsReader(
            $this->getProductStorageClient(),
            $this->getProductImageStorageClient(),
            $this->getResourceBuilder(),
            $this->createAbstractProductImageSetsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\ConcreteProductImageSetsReaderInterface
     */
    public function createConcreteProductImageSetsReader(): ConcreteProductImageSetsReaderInterface
    {
        return new ConcreteProductImageSetsReader(
            $this->getProductStorageClient(),
            $this->getProductImageStorageClient(),
            $this->getResourceBuilder(),
            $this->createConcreteProductImageSetsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
     */
    public function createAbstractProductsProductImageSetsResourceRelationshipExpander(): AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
    {
        return new AbstractProductsProductImageSetsResourceRelationshipExpander($this->createAbstractProductImageSetsReader());
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface
     */
    public function createConcreteProductsProductImageSetsResourceRelationshipExpander(): ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface
    {
        return new ConcreteProductsProductImageSetsResourceRelationshipExpander($this->createConcreteProductImageSetsReader());
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductImageSetsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductImageSetsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageStorageClientInterface
     */
    public function getProductImageStorageClient(): ProductImageSetsRestApiToProductImageStorageClientInterface
    {
        return $this->getProvidedDependency(ProductImageSetsRestApiDependencyProvider::CLIENT_PRODUCT_IMAGE_STORAGE);
    }
}
