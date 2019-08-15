<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReader;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReaderInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReader;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReaderInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\AbstractProductAvailabilitiesRelationshipExpander;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\AbstractProductAvailabilitiesRelationshipExpanderInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\ConcreteProductAvailabilitiesRelationshipExpander;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\ConcreteProductAvailabilitiesRelationshipExpanderInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapper;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapper;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface;

class ProductAvailabilitiesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability\AbstractProductAvailabilitiesReaderInterface
     */
    public function createAbstractProductAvailabilitiesReader(): AbstractProductAvailabilitiesReaderInterface
    {
        return new AbstractProductAvailabilitiesReader(
            $this->getProductStorageClient(),
            $this->getAvailabilityStorageClient(),
            $this->getResourceBuilder(),
            $this->createAbstractProductsAvailabilitiesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability\ConcreteProductAvailabilitiesReaderInterface
     */
    public function createConcreteProductsAvailabilitiesReader(): ConcreteProductAvailabilitiesReaderInterface
    {
        return new ConcreteProductAvailabilitiesReader(
            $this->getAvailabilityStorageClient(),
            $this->getProductStorageClient(),
            $this->getResourceBuilder(),
            $this->createConcreteProductsAvailabilitiesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\AbstractProductAvailabilitiesRelationshipExpanderInterface
     */
    public function createAbstractProductAvailabilitiesRelationshipExpander(): AbstractProductAvailabilitiesRelationshipExpanderInterface
    {
        return new AbstractProductAvailabilitiesRelationshipExpander($this->createAbstractProductAvailabilitiesReader());
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Expander\ConcreteProductAvailabilitiesRelationshipExpanderInterface
     */
    public function createConcreteProductAvailabilitiesRelationshipExpander(): ConcreteProductAvailabilitiesRelationshipExpanderInterface
    {
        return new ConcreteProductAvailabilitiesRelationshipExpander($this->createConcreteProductsAvailabilitiesReader());
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    public function createAbstractProductsAvailabilitiesResourceMapper(): AbstractProductAvailabilitiesResourceMapperInterface
    {
        return new AbstractProductAvailabilitiesResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface
     */
    public function createConcreteProductsAvailabilitiesResourceMapper(): ConcreteProductAvailabilitiesResourceMapperInterface
    {
        return new ConcreteProductAvailabilitiesResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
     */
    public function getAvailabilityStorageClient(): ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAvailabilitiesRestApiDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductAvailabilitiesRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAvailabilitiesRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
