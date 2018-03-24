<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit\ProductMeasurementBaseUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGenerator;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValue;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\QuantityProductMeasurementSalesUnitValueValidator;
use Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface getRepository()
 */
class ProductMeasurementUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit\ProductMeasurementBaseUnitReaderInterface
     */
    public function createProductMeasurementBaseUnitReader()
    {
        return new ProductMeasurementBaseUnitReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface
     */
    public function createProductMeasurementSalesUnitValue()
    {
        return new ProductMeasurementSalesUnitValue();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface
     */
    public function createProductMeasurementSalesUnitReader()
    {
        return new ProductMeasurementSalesUnitReader(
            $this->getRepository(),
            $this->getUtilUnitConversionService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGeneratorInterface
     */
    public function createProductMeasurementSalesUnitItemGroupKeyGenerator()
    {
        return new ProductMeasurementSalesUnitGroupKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\QuantityProductMeasurementSalesUnitValueValidatorInterface
     */
    public function createQuantityProductMeasurementSalesUnitValueValidator()
    {
        return new QuantityProductMeasurementSalesUnitValueValidator(
            $this->createProductMeasurementSalesUnitValue(),
            $this->createProductMeasurementSalesUnitReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilUnitConversionServiceInterface
     */
    public function getUtilUnitConversionService()
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::SERVICE_UTIL_UNIT_CONVERSION);
    }
}
