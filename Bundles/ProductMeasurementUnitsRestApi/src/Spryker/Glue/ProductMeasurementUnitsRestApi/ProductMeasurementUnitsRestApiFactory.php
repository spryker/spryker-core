<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\CartItemExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\CartItemExpanderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitBySalesUnitResourceRelationshipExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitByProductConcreteResourceRelationshipExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitByProductConcreteResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitsByCartItemResourceRelationshipExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitsByCartItemResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapper;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapperInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\ProductMeasurementUnitReader;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\ProductMeasurementUnitReaderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\SalesUnitReader;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\SalesUnitReaderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilder;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilder;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslator;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface;

/**
 * @method \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig getConfig()
 */
class ProductMeasurementUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\ProductMeasurementUnitReaderInterface
     */
    public function createProductMeasurementUnitReader(): ProductMeasurementUnitReaderInterface
    {
        return new ProductMeasurementUnitReader(
            $this->createProductMEasurementUnitRestResponseBuilder(),
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitNameTranslator()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader\SalesUnitReaderInterface
     */
    public function createSalesUnitReader(): SalesUnitReaderInterface
    {
        return new SalesUnitReader(
            $this->createSalesUnitRestResponseBuilder(),
            $this->getProductMeasurementUnitStorageClient(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface
     */
    public function createProductMeasurementUnitByProductConcreteResourceRelationshipExpander(): ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface
    {
        return new ProductMeasurementUnitByProductConcreteResourceRelationshipExpander(
            $this->createProductMeasurementUnitRestResponseBuilder(),
            $this->getProductStorageClient(),
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitNameTranslator()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitByProductConcreteResourceRelationshipExpanderInterface
     */
    public function createSalesUnitByProductConcreteResourceRelationshipExpander(): SalesUnitByProductConcreteResourceRelationshipExpanderInterface
    {
        return new SalesUnitByProductConcreteResourceRelationshipExpander(
            $this->createSalesUnitRestResponseBuilder(),
            $this->getProductStorageClient(),
            $this->getProductMeasurementUnitStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface
     */
    public function createProductMeasurementUnitBySalesUnitResourceRelationshipExpander(): ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface
    {
        return new ProductMeasurementUnitBySalesUnitResourceRelationshipExpander(
            $this->createProductMeasurementUnitRestResponseBuilder(),
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitNameTranslator()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\SalesUnitsByCartItemResourceRelationshipExpanderInterface
     */
    public function createSalesUnitsByCartItemResourceRelationshipExpander(): SalesUnitsByCartItemResourceRelationshipExpanderInterface
    {
        return new SalesUnitsByCartItemResourceRelationshipExpander(
            $this->createSalesUnitRestResponseBuilder(),
            $this->getProductStorageClient(),
            $this->getProductMeasurementUnitStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\CartItemExpanderInterface
     */
    public function createCartItemExpander(): CartItemExpanderInterface
    {
        return new CartItemExpander();
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator\ProductMeasurementUnitNameTranslatorInterface
     */
    public function createProductMeasurementUnitNameTranslator(): ProductMeasurementUnitNameTranslatorInterface
    {
        return new ProductMeasurementUnitNameTranslator($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface
     */
    public function createProductMeasurementUnitRestResponseBuilder(): ProductMeasurementUnitRestResponseBuilderInterface
    {
        return new ProductMeasurementUnitRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface
     */
    public function createSalesUnitRestResponseBuilder(): SalesUnitRestResponseBuilderInterface
    {
        return new SalesUnitRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createSalesUnitMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper\SalesUnitMapperInterface
     */
    public function createSalesUnitMapper(): SalesUnitMapperInterface
    {
        return new SalesUnitMapper();
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductMeasurementUnitsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitsRestApiDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
