<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeKeyManager;
use Spryker\Zed\Product\Business\Attribute\AttributeLoader;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;
use Spryker\Zed\Product\Business\Exporter\ProductEventBusExporter;
use Spryker\Zed\Product\Business\Exporter\ProductExporterInterface;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\Mapper\ProductAttributeMapper;
use Spryker\Zed\Product\Business\Product\Mapper\ProductAttributeMapperInterface;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAbstractLocalizedAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductLocalizedAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductSearchMetadataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductStoreDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMerger;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface;
use Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGenerator;
use Spryker\Zed\Product\Business\Product\NameGenerator\ProductConcreteNameGenerator;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractAfterCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractAfterUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractBeforeCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractBeforeUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractReadObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteAfterCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteAfterUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteBeforeCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteBeforeUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteReadObserverPluginManager;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductConcreteActivator;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusChecker;
use Spryker\Zed\Product\Business\Product\Status\ProductConcreteStatusChecker;
use Spryker\Zed\Product\Business\Product\Status\ProductConcreteStatusCheckerInterface;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReader;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriter;
use Spryker\Zed\Product\Business\Product\Suggest\ProductSuggester;
use Spryker\Zed\Product\Business\Product\Suggest\ProductSuggesterInterface;
use Spryker\Zed\Product\Business\Product\Touch\ProductAbstractTouch;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouch;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTrigger;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductAbstractAfterUpdateUrlObserver;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGenerator;
use Spryker\Zed\Product\Business\Product\Variant\VariantGenerator;
use Spryker\Zed\Product\Business\Publisher\ProductMessageBrokerPublisher;
use Spryker\Zed\Product\Business\Publisher\ProductPublisherInterface;
use Spryker\Zed\Product\Business\Reader\ProductAbstractReader;
use Spryker\Zed\Product\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\Product\Business\Reader\ProductConcreteReader;
use Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\Business\Writer\ProductConcreteWriter;
use Spryker\Zed\Product\Business\Writer\ProductConcreteWriterInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterface;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\Product\Persistence\ProductEntityManagerInterface getEntityManager()
 */
class ProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    public function createProductAbstractManager()
    {
        $productAbstractManager = new ProductAbstractManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createSkuGenerator(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper(),
            $this->createProductAbstractStoreRelationReader(),
            $this->createProductAbstractStoreRelationWriter(),
            $this->getProductAbstractPreCreatePlugins(),
            $this->createProductEventTrigger(),
            $this->getRepository(),
            $this->createProductAttributesMapper(),
        );

        $productAbstractManager->setEventFacade($this->getEventFacade());
        $productAbstractManager->attachBeforeCreateObserver($this->createProductAbstractBeforeCreateObserverPluginManager());
        $productAbstractManager->attachAfterCreateObserver($this->createProductAbstractAfterCreateObserverPluginManager());
        $productAbstractManager->attachBeforeUpdateObserver($this->createProductAbstractBeforeUpdateObserverPluginManager());
        $productAbstractManager->attachAfterUpdateObserver($this->createProductAbstractAfterUpdateObserverPluginManager());
        $productAbstractManager->attachAfterUpdateObserver($this->createProductAbstractAfterUpdateUrlObserver());
        $productAbstractManager->attachReadObserver($this->createProductAbstractReadObserverPluginManager());

        return $productAbstractManager;
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    public function createProductConcreteManager()
    {
        $productConcreteManager = new ProductConcreteManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper(),
            $this->getRepository(),
            $this->getProductConcreteExpanderPlugins(),
            $this->createProductEventTrigger(),
        );

        $productConcreteManager->setEventFacade($this->getEventFacade());
        $productConcreteManager->attachBeforeCreateObserver($this->createProductConcreteBeforeCreateObserverPluginManager());
        $productConcreteManager->attachAfterCreateObserver($this->createProductConcreteAfterCreateObserverPluginManager());
        $productConcreteManager->attachBeforeUpdateObserver($this->createProductConcreteBeforeUpdateObserverPluginManager());
        $productConcreteManager->attachAfterUpdateObserver($this->createProductConcreteAfterUpdateObserverPluginManager());
        $productConcreteManager->attachReadObserver($this->createProductConcreteReadObserverPluginManager());

        return $productConcreteManager;
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface
     */
    public function createProductConcreteActivator()
    {
        return new ProductConcreteActivator(
            $this->createProductAbstractStatusChecker(),
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->createProductUrlManager(),
            $this->createProductConcreteTouch(),
            $this->getQueryContainer(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    public function createProductUrlManager()
    {
        return new ProductUrlManager(
            $this->getUrlFacade(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->getQueryContainer(),
            $this->createProductUrlGenerator(),
            $this->createProductEventTrigger(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface
     */
    public function createProductUrlGenerator()
    {
        return new ProductUrlGenerator(
            $this->createProductAbstractNameGenerator(),
            $this->getLocaleFacade(),
            $this->getUtilTextService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    public function createProductVariantGenerator()
    {
        return new VariantGenerator(
            $this->getUrlFacade(),
            $this->createSkuGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface
     */
    public function createProductAbstractStatusChecker()
    {
        return new ProductAbstractStatusChecker($this->getQueryContainer(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Status\ProductConcreteStatusCheckerInterface
     */
    public function createProductConcreteStatusChecker(): ProductConcreteStatusCheckerInterface
    {
        return new ProductConcreteStatusChecker($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    public function createSkuGenerator()
    {
        return new SkuGenerator($this->getUtilTextService(), $this->createSkuIncrementGenerator());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGeneratorInterface
     */
    public function createSkuIncrementGenerator(): SkuIncrementGeneratorInterface
    {
        return new SkuIncrementGenerator($this->createProductConcreteManager());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGeneratorInterface
     */
    public function createAttributePermutationGenerator()
    {
        return new AttributePermutationGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeKeyManagerInterface
     */
    public function createAttributeKeyManager()
    {
        return new AttributeKeyManager($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    public function createAttributeEncoder()
    {
        return new AttributeEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    public function createProductTransferMapper()
    {
        return new ProductTransferMapper($this->createAttributeEncoder());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface
     */
    public function createAttributeMerger()
    {
        return new AttributeMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeLoaderInterface
     */
    public function createAttributeLoader()
    {
        return new AttributeLoader(
            $this->getQueryContainer(),
            $this->createAttributeMerger(),
            $this->createAttributeEncoder(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Touch\ProductAbstractTouchInterface
     */
    public function createProductAbstractTouch()
    {
        return new ProductAbstractTouch(
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->createProductAbstractStatusChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface
     */
    public function createProductConcreteTouch()
    {
        return new ProductConcreteTouch(
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->createProductAbstractStatusChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGeneratorInterface
     */
    public function createProductAbstractNameGenerator()
    {
        return new ProductAbstractNameGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\NameGenerator\ProductConcreteNameGeneratorInterface
     */
    public function createProductConcreteNameGenerator()
    {
        return new ProductConcreteNameGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    public function createProductAbstractStoreRelationReader()
    {
        return new ProductAbstractStoreRelationReader(
            $this->getQueryContainer(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriterInterface
     */
    public function createProductAbstractStoreRelationWriter()
    {
        return new ProductAbstractStoreRelationWriter(
            $this->getQueryContainer(),
            $this->createProductAbstractStoreRelationReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface
     */
    protected function createProductAbstractAssertion()
    {
        return new ProductAbstractAssertion(
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface
     */
    protected function createProductConcreteAssertion()
    {
        return new ProductConcreteAssertion(
            $this->getQueryContainer(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductAbstractPostCreatePlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface>
     */
    public function getProductAbstractPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductAbstractExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface>
     */
    protected function getProductAbstractReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_READ);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface>
     */
    public function getProductAbstractExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductConcreteExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface>
     */
    protected function getProductConcreteReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_READ);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface
     */
    protected function createProductAbstractBeforeCreateObserverPluginManager()
    {
        return new ProductAbstractBeforeCreateObserverPluginManager(
            $this->getProductAbstractBeforeCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface
     */
    protected function createProductAbstractAfterCreateObserverPluginManager()
    {
        return new ProductAbstractAfterCreateObserverPluginManager(
            $this->getProductAbstractAfterCreatePlugins(),
            $this->getProductAbstractPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractBeforeUpdateObserverPluginManager()
    {
        return new ProductAbstractBeforeUpdateObserverPluginManager($this->getProductAbstractBeforeUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractAfterUpdateObserverPluginManager()
    {
        return new ProductAbstractAfterUpdateObserverPluginManager($this->getProductAbstractAfterUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractAfterUpdateUrlObserver()
    {
        return new ProductAbstractAfterUpdateUrlObserver($this->createProductAbstractStatusChecker(), $this->createProductUrlManager());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface
     */
    protected function createProductAbstractReadObserverPluginManager()
    {
        return new ProductAbstractReadObserverPluginManager(
            $this->getProductAbstractReadPlugins(),
            $this->getProductAbstractExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface
     */
    protected function createProductConcreteBeforeCreateObserverPluginManager()
    {
        return new ProductConcreteBeforeCreateObserverPluginManager($this->getProductConcreteBeforeCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface
     */
    protected function createProductConcreteAfterCreateObserverPluginManager()
    {
        return new ProductConcreteAfterCreateObserverPluginManager($this->getProductConcreteAfterCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface
     */
    protected function createProductConcreteBeforeUpdateObserverPluginManager()
    {
        return new ProductConcreteBeforeUpdateObserverPluginManager($this->getProductConcreteBeforeUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface
     */
    protected function createProductConcreteAfterUpdateObserverPluginManager()
    {
        return new ProductConcreteAfterUpdateObserverPluginManager($this->getProductConcreteAfterUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteReadObserverInterface
     */
    protected function createProductConcreteReadObserverPluginManager()
    {
        return new ProductConcreteReadObserverPluginManager($this->getProductConcreteReadPlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Suggest\ProductSuggesterInterface
     */
    public function createProductSuggester(): ProductSuggesterInterface
    {
        return new ProductSuggester(
            $this->getConfig(),
            $this->getRepository(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Writer\ProductConcreteWriterInterface
     */
    public function createProductConcreteWriter(): ProductConcreteWriterInterface
    {
        return new ProductConcreteWriter(
            $this->createProductConcreteManager(),
            $this->getEntityManager(),
            $this->createProductConcreteAssertion(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface>
     */
    public function getProductAbstractPreCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_PRE_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Exporter\ProductExporterInterface
     */
    public function createProductEventBusExporter(): ProductExporterInterface
    {
        return new ProductEventBusExporter(
            $this->getEventFacade(),
            $this->getStoreFacade(),
            $this->createProductConcreteManager(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->createProductConcreteManager(),
            $this->createProductAbstractManager(),
            $this->createProductUrlManager(),
            $this->createProductConcreteMerger(),
            $this->getRepository(),
            $this->getProductConcreteExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Publisher\ProductPublisherInterface
     */
    public function createProductMessageBrokerPublisher(): ProductPublisherInterface
    {
        return new ProductMessageBrokerPublisher(
            $this->createProductConcreteReader(),
            $this->getMessageBrokerFacade(),
            $this->getRepository(),
            $this->getConfig(),
            $this->createProductEventTrigger(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterface
     */
    public function getMessageBrokerFacade(): ProductToMessageBrokerInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface
     */
    public function createProductConcreteMerger(): ProductConcreteMergerInterface
    {
        return new ProductConcreteMerger(
            $this->getProductDataMergers(),
            $this->getProductConcreteMergerPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface
     */
    public function createProductEventTrigger(): ProductEventTriggerInterface
    {
        return new ProductEventTrigger(
            $this->getEventFacade(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface>
     */
    public function getProductConcreteMergerPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_MERGER);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Reader\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getRepository(),
            $this->getProductAbstractCollectionPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface>
     */
    public function getProductAbstractCollectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_COLLECTION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface>
     */
    public function getProductDataMergers(): array
    {
        return [
            $this->createProductAbstractLocalizedAttributesDataMerger(),
            $this->createProductAttributesDataMerger(),
            $this->createProductLocalizedAttributesDataMerger(),
            $this->createProductSearchMetadataMerger(),
            $this->createProductStoreDataMerger(),
        ];
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface
     */
    public function createProductAbstractLocalizedAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductAbstractLocalizedAttributesDataMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface
     */
    public function createProductAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductAttributesDataMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface
     */
    public function createProductLocalizedAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductLocalizedAttributesDataMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface
     */
    public function createProductSearchMetadataMerger(): ProductDataMergerInterface
    {
        return new ProductSearchMetadataMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface
     */
    public function createProductStoreDataMerger(): ProductDataMergerInterface
    {
        return new ProductStoreDataMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributesMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper(
            $this->createAttributeEncoder(),
        );
    }
}
