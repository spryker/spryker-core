<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReader;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReaderInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageWriter;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageWriterInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductMeasurementUnitStorageWriter;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductMeasurementUnitStorageWriterInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface getEntityManager()
 */
class ProductMeasurementUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductMeasurementUnitStorageWriterInterface
     */
    public function createProductMeasurementUnitStorageWriter(): ProductMeasurementUnitStorageWriterInterface
    {
        return new ProductMeasurementUnitStorageWriter(
            $this->getProductMeasurementUnitFacade(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageWriterInterface
     */
    public function createProductConcreteMeasurementUnitStorageWriter(): ProductConcreteMeasurementUnitStorageWriterInterface
    {
        return new ProductConcreteMeasurementUnitStorageWriter(
            $this->getProductMeasurementUnitFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createProductConcreteMeasurementUnitStorageReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageReaderInterface
     */
    public function createProductConcreteMeasurementUnitStorageReader(): ProductConcreteMeasurementUnitStorageReaderInterface
    {
        return new ProductConcreteMeasurementUnitStorageReader(
            $this->getProductMeasurementUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
     */
    public function getProductMeasurementUnitFacade(): ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::FACADE_PRODUCT_MEASUREMENT_UNIT);
    }
}
