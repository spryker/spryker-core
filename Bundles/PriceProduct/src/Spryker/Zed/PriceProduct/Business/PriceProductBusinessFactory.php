<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProduct\Business\Internal\Install;
use Spryker\Zed\PriceProduct\Business\Internal\InstallInterface;
use Spryker\Zed\PriceProduct\Business\Model\BulkWriter;
use Spryker\Zed\PriceProduct\Business\Model\BulkWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceData\PriceDataChecksumGenerator;
use Spryker\Zed\PriceProduct\Business\Model\PriceData\PriceDataChecksumGeneratorInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceGrouper;
use Spryker\Zed\PriceProduct\Business\Model\PriceGrouperInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilder;
use Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeMapper;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReader;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriter;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReader;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReader;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpander;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapper;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use Spryker\Zed\PriceProduct\Business\Model\ReaderInterface;

use Spryker\Zed\PriceProduct\Business\Model\Writer;
use Spryker\Zed\PriceProduct\Business\Model\WriterInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface getEntityManager()
 */
class PriceProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    public function createReaderModel(): ReaderInterface
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
    public function createWriterModel(): WriterInterface
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getConfig(),
            $this->getProductFacade(),
            $this->createPriceTypeReader(),
            $this->createPriceProductStoreWriter(),
            $this->createPriceProductDefaultWriter(),
            $this->getPriceDimensionAbstractSaverPlugins(),
            $this->getPriceDimensionConcreteSaverPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\BulkWriterInterface
     */
    public function createBulkWriterModel(): BulkWriterInterface
    {
        return new BulkWriter(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getConfig(),
            $this->getProductFacade(),
            $this->createPriceTypeReader(),
            $this->createPriceProductStoreWriter(),
            $this->createPriceProductDefaultWriter(),
            $this->getPriceDimensionAbstractSaverPlugins(),
            $this->getPriceDimensionConcreteSaverPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    public function createPriceTypeReader(): PriceProductTypeReaderInterface
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
    public function createPriceProductMapper(): PriceProductMapperInterface
    {
        return new PriceProductMapper(
            $this->getCurrencyFacade(),
            $this->createPriceTypeMapper(),
            $this->getPriceFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface
     */
    public function createPriceProductExpander(): PriceProductExpanderInterface
    {
        return new PriceProductExpander(
            $this->getPriceProductDimensionExpanderStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    public function createPriceTypeMapper(): ProductPriceTypeMapperInterface
    {
        return new PriceProductTypeMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface
     */
    public function createPriceProductAbstractReader(): PriceProductAbstractReaderInterface
    {
        return new PriceProductAbstractReader(
            $this->getQueryContainer(),
            $this->createPriceProductMapper(),
            $this->getProductFacade(),
            $this->createProductCriteriaBuilder(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getPriceProductService(),
            $this->createPriceProductExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface
     */
    public function createProductCriteriaBuilder(): PriceProductCriteriaBuilderInterface
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
    public function createPriceProductConcreteReader(): PriceProductConcreteReaderInterface
    {
        return new PriceProductConcreteReader(
            $this->getQueryContainer(),
            $this->createPriceProductMapper(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getPriceProductService(),
            $this->createPriceProductExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Internal\InstallInterface
     */
    public function createInstaller(): InstallInterface
    {
        return new Install($this->createPriceTypeWriter(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceGrouperInterface
     */
    public function createPriceGrouper(): PriceGrouperInterface
    {
        return new PriceGrouper($this->createReaderModel(), $this->createPriceProductMapper());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface
     */
    public function createPriceTypeWriter(): PriceTypeWriterInterface
    {
        return new PriceTypeWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractWriterInterface
     */
    public function createPriceProductAbstractWriter(): PriceProductAbstractWriterInterface
    {
        return new PriceProductAbstractWriter(
            $this->createPriceTypeReader(),
            $this->getQueryContainer(),
            $this->createPriceProductDefaultWriter(),
            $this->getPriceDimensionAbstractSaverPlugins(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteWriterInterface
     */
    public function createPriceProductConcreteWriter(): PriceProductConcreteWriterInterface
    {
        return new PriceProductConcreteWriter(
            $this->createPriceTypeReader(),
            $this->getQueryContainer(),
            $this->createPriceProductDefaultWriter(),
            $this->getPriceDimensionConcreteSaverPlugins(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceData\PriceDataChecksumGeneratorInterface
     */
    public function createPriceDataChecksumGenerator(): PriceDataChecksumGeneratorInterface
    {
        return new PriceDataChecksumGenerator();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    public function createPriceProductStoreWriter(): PriceProductStoreWriterInterface
    {
        return new PriceProductStoreWriter($this->getQueryContainer(), $this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    public function createPriceProductDefaultWriter(): PriceProductDefaultWriterInterface
    {
        return new PriceProductDefaultWriter(
            $this->createPriceProductStoreWriter(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface
     */
    public function getProductFacade(): PriceProductToProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface
     */
    public function getTouchFacade(): PriceProductToTouchFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceProductToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    public function getPriceFacade(): PriceProductToPriceFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    public function getModuleConfig(): PriceProductConfig
    {
        return parent::getConfig();
    }

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    public function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    public function getPriceDimensionAbstractSaverPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_ABSTRACT_SAVER);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    public function getPriceDimensionConcreteSaverPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_CONCRETE_SAVER);
    }

    /**
     * @return \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface[]
     */
    public function getPriceProductDimensionExpanderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DIMENSION_TRANSFER_EXPANDER);
    }
}
