<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReader;
use Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReader;
use Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapper;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapper;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface;

class ProductPricesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface
     */
    public function getPriceProductStorageClient(): ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPricesRestApiDependencyProvider::CLIENT_PRICE_PRODUCT_RESOURCE_ALIAS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface
     */
    public function createAbstractProductPricesResourceMapper(): AbstractProductPricesResourceMapperInterface
    {
        return new AbstractProductPricesResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface
     */
    public function createConcreteProductPricesResourceMapper(): ConcreteProductPricesResourceMapperInterface
    {
        return new ConcreteProductPricesResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface
     */
    public function createAbstractProductPricesReader(): AbstractProductPricesReaderInterface
    {
        return new AbstractProductPricesReader(
            $this->getPriceProductStorageClient(),
            $this->getResourceBuilder(),
            $this->createAbstractProductPricesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices\ConcreteProductPricesReaderInterface
     */
    public function createConcreteProductPricesReader(): ConcreteProductPricesReaderInterface
    {
        return new ConcreteProductPricesReader(
            $this->getPriceProductStorageClient(),
            $this->getResourceBuilder(),
            $this->createConcreteProductPricesResourceMapper()
        );
    }
}
