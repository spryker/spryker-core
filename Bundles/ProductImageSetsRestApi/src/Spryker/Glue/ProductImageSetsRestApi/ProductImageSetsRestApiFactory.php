<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\AbstractProductsImageSets\AbstractProductImageSetsReader;
use Spryker\Glue\ProductImageSetsRestApi\Processor\AbstractProductsImageSets\AbstractProductImageSetsReaderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\ConcreteProductsImageSets\ConcreteProductImageSetsReader;
use Spryker\Glue\ProductImageSetsRestApi\Processor\ConcreteProductsImageSets\ConcreteProductImageSetsReaderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface;

class ProductImageSetsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface
     */
    public function getProductImageStorageClient(): ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface
    {
        return $this->getProvidedDependency(ProductImageSetsRestApiDependencyProvider::CLIENT_PRODUCT_IMAGE_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface
     */
    public function createAbstractProductImageSetsMapper(): AbstractProductImageSetsMapperInterface
    {
        return new AbstractProductImageSetsMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface
     */
    public function createConcreteProductImageSetsMapper(): ConcreteProductImageSetsMapperInterface
    {
        return new ConcreteProductImageSetsMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\AbstractProductsImageSets\AbstractProductImageSetsReaderInterface
     */
    public function createAbstractProductImageSetsReader(): AbstractProductImageSetsReaderInterface
    {
        return new AbstractProductImageSetsReader(
            $this->getProductImageStorageClient(),
            $this->getResourceBuilder(),
            $this->createAbstractProductImageSetsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\ConcreteProductsImageSets\ConcreteProductImageSetsReaderInterface
     */
    public function createConcreteProductImageSetsReader(): ConcreteProductImageSetsReaderInterface
    {
        return new ConcreteProductImageSetsReader(
            $this->getProductImageStorageClient(),
            $this->getResourceBuilder(),
            $this->createConcreteProductImageSetsMapper()
        );
    }
}
