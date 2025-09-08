<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderInterface;
use Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderWithCache;
use Spryker\Zed\PriceProduct\Business\Deleter\PriceProductDeleter;
use Spryker\Zed\PriceProduct\Business\Deleter\PriceProductDeleterInterface;
use Spryker\Zed\PriceProduct\Business\Expander\Wishlist\PriceProductWishlistItemExpander;
use Spryker\Zed\PriceProduct\Business\Expander\Wishlist\PriceProductWishlistItemExpanderInterface;
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
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader\PriceProductReaderPluginExecutor;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutor;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutorInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use Spryker\Zed\PriceProduct\Business\Model\ReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Writer;
use Spryker\Zed\PriceProduct\Business\Model\WriterInterface;
use Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductDefaultRemover;
use Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductDefaultRemoverInterface;
use Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductRemover;
use Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductRemoverInterface;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidCurrencyAssignedToStoreConstraint;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidUniqueStoreCurrencyCollectionConstraint;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidUniqueStoreCurrencyGrossNetConstraint;
use Spryker\Zed\PriceProduct\Business\Validator\ConstraintProvider\PriceProductConstraintProvider;
use Spryker\Zed\PriceProduct\Business\Validator\ConstraintProvider\PriceProductConstraintProviderInterface;
use Spryker\Zed\PriceProduct\Business\Validator\PriceProductValidator;
use Spryker\Zed\PriceProduct\Business\Validator\PriceProductValidatorInterface;
use Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToEventInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Service\PriceProductToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;
use Symfony\Component\Validator\Constraint;

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
            $this->createPriceProductMapper(),
            $this->getConfig(),
            $this->getPriceProductService(),
            $this->getRepository(),
            $this->createPriceProductExpander(),
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
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Currency\CurrencyReaderInterface
     */
    public function createCurrencyReader(): CurrencyReaderInterface
    {
        return new CurrencyReaderWithCache(
            $this->getCurrencyFacade(),
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
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader\PriceProductReaderPluginExecutor
     */
    public function createPriceProductReaderPluginExecutor(): PriceProductReaderPluginExecutor
    {
        return new PriceProductReaderPluginExecutor(
            $this->getPriceProductPricesExtractorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface
     */
    public function createPriceProductExpander(): PriceProductExpanderInterface
    {
        return new PriceProductExpander(
            $this->getPriceProductDimensionExpanderStrategyPlugins(),
            $this->getConfig(),
            $this->getPriceProductService(),
            $this->getCurrencyFacade(),
            $this->getStoreFacade(),
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
            $this->createPriceProductExpander(),
            $this->createPriceProductReaderPluginExecutor(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface
     */
    public function createProductCriteriaBuilder(): PriceProductCriteriaBuilderInterface
    {
        return new PriceProductCriteriaBuilder(
            $this->createCurrencyReader(),
            $this->getPriceFacade(),
            $this->getStoreFacade(),
            $this->createPriceTypeReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface
     */
    public function createPriceProductConcreteReader(): PriceProductConcreteReaderInterface
    {
        return new PriceProductConcreteReader(
            $this->getQueryContainer(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getPriceProductService(),
            $this->createPriceProductExpander(),
            $this->createPriceProductReaderPluginExecutor(),
            $this->getPriceProductExternalProviderPlugins(),
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
        return new PriceGrouper(
            $this->createReaderModel(),
            $this->createPriceProductMapper(),
            $this->getConfig(),
        );
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
            $this->getEntityManager(),
            $this->getConfig(),
            $this->createPriceProductStoreWriter(),
            $this->getPriceDimensionAbstractSaverPlugins(),
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
            $this->getEntityManager(),
            $this->getConfig(),
            $this->createPriceProductStoreWriter(),
            $this->getEventFacade(),
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
        return new PriceProductStoreWriter(
            $this->getQueryContainer(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPriceProductStoreWriterPluginExecutor(),
            $this->getConfig(),
            $this->createPriceProductDefaultWriter(),
            $this->createPriceDataChecksumGenerator(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutorInterface
     */
    public function createPriceProductStoreWriterPluginExecutor(): PriceProductStoreWriterPluginExecutorInterface
    {
        return new PriceProductStoreWriterPluginExecutor(
            $this->getPriceProductStorePreDeletePlugins(),
            $this->getPriceDimensionAbstractSaverPlugins(),
            $this->getPriceDimensionConcreteSaverPlugins(),
            $this->getOrphanPriceProductStoreRemovalVoterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    public function createPriceProductDefaultWriter(): PriceProductDefaultWriterInterface
    {
        return new PriceProductDefaultWriter(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductRemoverInterface
     */
    public function createPriceProductRemover(): PriceProductRemoverInterface
    {
        return new PriceProductRemover(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPriceProductStoreWriterPluginExecutor(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProduct\PriceProductDefaultRemoverInterface
     */
    public function createPriceProductDefaultRemover(): PriceProductDefaultRemoverInterface
    {
        return new PriceProductDefaultRemover(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Deleter\PriceProductDeleterInterface
     */
    public function createPriceProductDeleter(): PriceProductDeleterInterface
    {
        return new PriceProductDeleter(
            $this->getEntityManager(),
            $this->getPriceProductCollectionDeletePlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductCollectionDeletePluginInterface>
     */
    public function getPriceProductCollectionDeletePlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGINS_PRICE_PRODUCT_COLLECTION_DELETE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Validator\PriceProductValidatorInterface
     */
    public function createPriceProductValidator(): PriceProductValidatorInterface
    {
        return new PriceProductValidator(
            $this->createPriceProductConstraintProvider(),
            $this->getValidationAdapter(),
            $this->getPriceProductValidatorPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductValidatorPluginInterface>
     */
    public function getPriceProductValidatorPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_VALIDATOR);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Validator\ConstraintProvider\PriceProductConstraintProviderInterface
     */
    public function createPriceProductConstraintProvider(): PriceProductConstraintProviderInterface
    {
        return new PriceProductConstraintProvider(
            [
            $this->createValidUniqueStoreCurrencyGrossNetConstraint(),
            $this->createValidCurrencyAssignedToStoreConstraint(),
            ],
            $this->createValidUniqueStoreCurrencyCollectionConstraint(),
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidUniqueStoreCurrencyCollectionConstraint(): Constraint
    {
        return new ValidUniqueStoreCurrencyCollectionConstraint(
            $this->getPriceProductService(),
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidUniqueStoreCurrencyGrossNetConstraint(): Constraint
    {
        return new ValidUniqueStoreCurrencyGrossNetConstraint($this->getRepository());
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidCurrencyAssignedToStoreConstraint(): Constraint
    {
        return new ValidCurrencyAssignedToStoreConstraint($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Expander\Wishlist\PriceProductWishlistItemExpanderInterface
     */
    public function createPriceProductWishlsitItemExpander(): PriceProductWishlistItemExpanderInterface
    {
        return new PriceProductWishlistItemExpander(
            $this->createPriceProductConcreteReader(),
            $this->getConfig(),
            $this->getStoreFacade(),
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
        /** @var \Spryker\Zed\PriceProduct\PriceProductConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    public function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Service\PriceProductToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface>
     */
    public function getPriceDimensionAbstractSaverPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_ABSTRACT_SAVER);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface>
     */
    public function getPriceDimensionConcreteSaverPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_CONCRETE_SAVER);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface>
     */
    public function getOrphanPriceProductStoreRemovalVoterPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGINS_ORPHAN_PRICE_PRODUCT_STORE_REMOVAL_VOTER);
    }

    /**
     * @return array<\Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface>
     */
    public function getPriceProductDimensionExpanderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DIMENSION_TRANSFER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductReaderPricesExtractorPluginInterface>
     */
    public function getPriceProductPricesExtractorPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_PRICES_EXTRACTOR);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductExternalProviderPluginInterface>
     */
    public function getPriceProductExternalProviderPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_EXTERNAL_PROVIDER);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface>
     */
    public function getPriceProductStorePreDeletePlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_STORE_PRE_DELETE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface
     */
    public function getValidationAdapter(): PriceProductToValidationAdapterInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::EXTERNAL_ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToEventInterface
     */
    protected function getEventFacade(): PriceProductToEventInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_EVENT);
    }
}
