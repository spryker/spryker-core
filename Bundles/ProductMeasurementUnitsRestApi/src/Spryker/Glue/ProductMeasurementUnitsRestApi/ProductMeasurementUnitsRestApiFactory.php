<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpander;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface;

/**
 * @method \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig getConfig()
 */
class ProductMeasurementUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander\ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface
     */
    public function createProductMeasurementUnitByProductConcreteResourceRelationshipExpander(): ProductMeasurementUnitByProductConcreteResourceRelationshipExpanderInterface
    {
        return new ProductMeasurementUnitByProductConcreteResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->getProductStorageClient(),
            $this->getProductMeasurementUnitStorageClient()
        );
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
}
