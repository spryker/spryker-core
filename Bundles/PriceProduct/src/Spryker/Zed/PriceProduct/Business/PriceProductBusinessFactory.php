<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProduct\Business\Internal\Install;
use Spryker\Zed\PriceProduct\Business\Model\BulkWriter;
use Spryker\Zed\PriceProduct\Business\Model\PriceGrouper;
use Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilder;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeMapper;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReader;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReader;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReader;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapper;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use Spryker\Zed\PriceProduct\Business\Model\Writer;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface getQueryContainer()
 */
class PriceProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    public function createReaderModel()
    {
        return new Reader(
            $this->getProductFacade(),
            $this->createPriceTypeReader(),
            $this->createPriceProductConcreteReader(),
            $this->createPriceProductAbstractReader(),
            $this->createProductCriteriaBuilder(),
            $this->createPriceProductMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\WriterInterface
     */
    public function createWriterModel()
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getConfig(),
            $this->getProductFacade(),
            $this->createPriceTypeReader(),
            $this->createPriceProductStoreWriter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\BulkWriterInterface
     */
    public function createBulkWriterModel()
    {
        return new BulkWriter(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getConfig(),
            $this->getProductFacade(),
            $this->createPriceTypeReader(),
            $this->createPriceProductStoreWriter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    public function createPriceTypeReader()
    {
        return new PriceProductTypeReader(
            $this->getQueryContainer(),
            $this->createPriceTypeMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface
     */
    public function createPriceProductMapper()
    {
        return new PriceProductMapper(
            $this->getCurrencyFacade(),
            $this->createPriceTypeMapper(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    public function createPriceTypeMapper()
    {
        return new PriceProductTypeMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface
     */
    public function createPriceProductAbstractReader()
    {
        return new PriceProductAbstractReader(
            $this->getQueryContainer(),
            $this->createPriceProductMapper(),
            $this->getProductFacade(),
            $this->createProductCriteriaBuilder(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface
     */
    public function createProductCriteriaBuilder()
    {
        return new PriceProductCriteriaBuilder(
            $this->getCurrencyFacade(),
            $this->getPriceFacade(),
            $this->getStoreFacade(),
            $this->createPriceTypeReader()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface
     */
    public function createPriceProductConcreteReader()
    {
        return new PriceProductConcreteReader(
            $this->getQueryContainer(),
            $this->createPriceProductMapper(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Internal\InstallInterface
     */
    public function createInstaller()
    {
        return new Install($this->createPriceTypeWriter(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceGrouperInterface
     */
    public function createPriceGrouper()
    {
        return new PriceGrouper($this->createReaderModel(), $this->createPriceProductMapper());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface
     */
    public function createPriceTypeWriter()
    {
        return new PriceTypeWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractWriterInterface
     */
    public function createPriceProductAbstractWriter()
    {
        return new PriceProductAbstractWriter(
            $this->createPriceTypeReader(),
            $this->getQueryContainer(),
            $this->createPriceProductStoreWriter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteWriterInterface
     */
    public function createPriceProductConcreteWriter()
    {
        return new PriceProductConcreteWriter(
            $this->createPriceTypeReader(),
            $this->getQueryContainer(),
            $this->createPriceProductStoreWriter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    protected function createPriceProductStoreWriter()
    {
        return new PriceProductStoreWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    public function getModuleConfig()
    {
        return parent::getConfig();
    }
}
