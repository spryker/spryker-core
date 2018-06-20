<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\CartChangeExpander;
use Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange\CartChangeExpanderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstaller;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Installer\ProductPackagingUnitTypeInstallerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct\ProductPackagingUnitLeadProductReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct\ProductPackagingUnitLeadProductReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriter;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeWriterInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleInterface;
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
            $this->createProductPackagingUnitTypeTranslationsReader()
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
            $this->createProductPackagingUnitTypeTranslationsWriter()
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
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReaderInterface
     */
    public function createProductPackagingUnitTypeTranslationsReader(): ProductPackagingUnitTypeTranslationsReaderInterface
    {
        return new ProductPackagingUnitTypeTranslationsReader(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsWriterInterface
     */
    public function createProductPackagingUnitTypeTranslationsWriter(): ProductPackagingUnitTypeTranslationsWriterInterface
    {
        return new ProductPackagingUnitTypeTranslationsWriter(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleInterface
     */
    public function getLocaleFacade(): ProductPackagingUnitToLocaleInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryInterface
     */
    public function getGlossaryFacade(): ProductPackagingUnitToGlossaryInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    public function createProductPackagingUnitReader(): ProductPackagingUnitReaderInterface
    {
        return new ProductPackagingUnitReader(
            $this->getRepository()
        );
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
}
