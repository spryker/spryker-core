<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\CartChangeExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\CartChangeExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitOrderHydrator;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitOrderHydratorInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstaller;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstallerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\AmountSalesUnitOrderItemExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\AmountSalesUnitOrderItemExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct\ProductPackagingUnitLeadProductReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct\ProductPackagingUnitLeadProductReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstallerInterface
     */
    public function createProductPackagingUnitTypeInstaller(): ProductPackagingUnitTypeInstallerInterface
    {
        return new ProductPackagingUnitTypeInstaller(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface
     */
    public function createProductPackagingUnitTypeReader(): ProductPackagingUnitTypeReaderInterface
    {
        return new ProductPackagingUnitTypeReader(
            $this->getRepository(),
            $this->createProductPackagingUnitTypeTranslationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriterInterface
     */
    public function createProductPackagingUnitTypeWriter(): ProductPackagingUnitTypeWriterInterface
    {
        return new ProductPackagingUnitTypeWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createProductPackagingUnitTypeTranslationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct\ProductPackagingUnitLeadProductReaderInterface
     */
    public function createProductPackagingUnitLeadProductReader(): ProductPackagingUnitLeadProductReaderInterface
    {
        return new ProductPackagingUnitLeadProductReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface
     */
    public function createProductPackagingUnitTypeTranslationReader(): ProductPackagingUnitTypeTranslationReaderInterface
    {
        return new ProductPackagingUnitTypeTranslationReader(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationWriterInterface
     */
    public function createProductPackagingUnitTypeTranslationWriter(): ProductPackagingUnitTypeTranslationWriterInterface
    {
        return new ProductPackagingUnitTypeTranslationWriter(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductPackagingUnitToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductPackagingUnitToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\CartChangeExpanderInterface
     */
    public function createCartChangeExpander(): CartChangeExpanderInterface
    {
        return new CartChangeExpander(
            $this->createProductPackagingUnitReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    public function createProductPackagingUnitReader(): ProductPackagingUnitReaderInterface
    {
        return new ProductPackagingUnitReader(
            $this->getRepository(),
            $this->getProductMeasurementUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    public function getProductMeasurementUnitFacade(): ProductPackagingUnitToProductMeasurementUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_PRODUCT_MEASUREMENT_UNIT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator\AmountSalesUnitOrderHydratorInterface
     */
    public function createAmountSalesUnitOrderHydrator(): AmountSalesUnitOrderHydratorInterface
    {
        return new AmountSalesUnitOrderHydrator(
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface
     */
    public function getSalesQueryContainer(): ProductPackagingUnitToSalesQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\AmountSalesUnitOrderItemExpanderInterface
     */
    public function createAmountSalesUnitOrderItemExpander(): AmountSalesUnitOrderItemExpanderInterface
    {
        return new AmountSalesUnitOrderItemExpander();
    }
}
