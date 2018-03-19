<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageWriter;
use Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductMeasurementUnitStorageWriter;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()
 */
class ProductMeasurementUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductMeasurementUnitStorageWriterInterface
     */
    public function createProductMeasurementUnitStorageWriter()
    {
        return new ProductMeasurementUnitStorageWriter();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Business\Model\ProductConcreteMeasurementUnitStorageWriterInterface
     */
    public function createProductConcreteMeasurementUnitStorageWriter()
    {
        return new ProductConcreteMeasurementUnitStorageWriter();
    }
}
