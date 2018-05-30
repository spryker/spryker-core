<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstaller;
use Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstallerInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpanderExpander;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit\ProductMeasurementBaseUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit\ProductMeasurementBaseUnitReaderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGenerator;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGeneratorInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReader;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValue;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface;
use Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface getEntityManager()
 */
class ProductMeasurementUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit\ProductMeasurementBaseUnitReaderInterface
     */
    public function createProductMeasurementBaseUnitReader(): ProductMeasurementBaseUnitReaderInterface
    {
        return new ProductMeasurementBaseUnitReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface
     */
    public function createProductMeasurementSalesUnitValue(): ProductMeasurementSalesUnitValueInterface
    {
        return new ProductMeasurementSalesUnitValue();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface
     */
    public function createProductMeasurementSalesUnitReader(): ProductMeasurementSalesUnitReaderInterface
    {
        return new ProductMeasurementSalesUnitReader(
            $this->getRepository(),
            $this->getUtilMeasurementUnitConversionService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitGroupKeyGeneratorInterface
     */
    public function createProductMeasurementSalesUnitItemGroupKeyGenerator(): ProductMeasurementSalesUnitGroupKeyGeneratorInterface
    {
        return new ProductMeasurementSalesUnitGroupKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
     */
    public function getUtilMeasurementUnitConversionService(): ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitDependencyProvider::SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION);
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\CartChange\CartChangeExpanderInterface
     */
    public function createCartChangeTransferExpander(): CartChangeExpanderInterface
    {
        return new CartChangeExpanderExpander(
            $this->createProductMeasurementSalesUnitReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Installer\ProductMeasurementUnitInstallerInterface
     */
    public function createProductMeasurementUnitInstaller(): ProductMeasurementUnitInstallerInterface
    {
        return new ProductMeasurementUnitInstaller(
            $this->getConfig(),
            $this->getEntityManager()
        );
    }
}
