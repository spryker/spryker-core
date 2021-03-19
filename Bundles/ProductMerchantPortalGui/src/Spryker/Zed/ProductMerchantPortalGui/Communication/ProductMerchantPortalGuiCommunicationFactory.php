<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilder;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteBulkForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\PriceProductTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\PriceProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductValidityFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 */
class ProductMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface
     */
    public function createProductAbstractGuiTableConfigurationProvider(): ProductAbstractGuiTableConfigurationProviderInterface
    {
        return new ProductAbstractGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createCategoryFilterOptionsProvider(),
            $this->createStoreFilterOptionsProvider(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductAbstractTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductAbstractTableDataProvider(
            $this->getRepository(),
            $this->getLocaleFacade(),
            $this->getMerchantUserFacade(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilderInterface
     */
    public function createProductAbstractNameBuilder(): ProductAbstractNameBuilderInterface
    {
        return new ProductAbstractNameBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface
     */
    public function createCategoryFilterOptionsProvider(): CategoryFilterOptionsProviderInterface
    {
        return new CategoryFilterOptionsProvider(
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getConfig()
        );
    }

    /**
     * @phpstan-param array<mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductAbstractForm(?ProductAbstractTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductAbstractForm::class, $data, $options);
    }

    /**
     * @phpstan-param array<mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateProductAbstractForm(?ProductAbstractTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateProductAbstractForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface
     */
    public function createProductAbstractFormDataProvider(): ProductAbstractFormDataProviderInterface
    {
        return new ProductAbstractFormDataProvider(
            $this->getMerchantProductFacade(),
            $this->getStoreFacade(),
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getProductCategoryFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface
     */
    public function createPriceProductAbstractGuiTableConfigurationProvider(): PriceProductAbstractGuiTableConfigurationProviderInterface
    {
        return new PriceProductAbstractGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->createStoreFilterOptionsProvider(),
            $this->createCurrencyFilterConfigurationProvider()
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createPriceProductAbstractTableDataProvider(int $idProductAbstract): GuiTableDataProviderInterface
    {
        return new PriceProductAbstractTableDataProvider(
            $idProductAbstract,
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    public function createStoreFilterOptionsProvider(): StoreFilterOptionsProviderInterface
    {
        return new StoreFilterOptionsProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface
     */
    public function createCurrencyFilterConfigurationProvider(): CurrencyFilterConfigurationProviderInterface
    {
        return new CurrencyFilterConfigurationProvider($this->getCurrencyFacade());
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceProductTransformer(int $idProductAbstract): DataTransformerInterface
    {
        return new PriceProductTransformer(
            $idProductAbstract,
            $this->getPriceProductFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    public function createPriceProductMapper(): PriceProductMapperInterface
    {
        return new PriceProductMapper(
            $this->getPriceProductFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface
     */
    public function createProductAttributeGuiTableConfigurationProvider(): ProductAttributeGuiTableConfigurationProviderInterface
    {
        return new ProductAttributeGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createProductAttributeTableDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface
     */
    public function createProductAttributeTableDataProvider(): ProductAttributeTableDataProviderInterface
    {
        return new ProductAttributeTableDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProviderInterface
     */
    public function createProductGuiTableConfigurationProvider(): ProductGuiTableConfigurationProviderInterface
    {
        return new ProductGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getTranslatorFacade(),
            $this->getProductConcreteTableExpanderPlugins()
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductTableDataProvider(int $idProductAbstract): GuiTableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $idProductAbstract,
            $this->getRepository(),
            $this->getLocaleFacade(),
            $this->getMerchantUserFacade(),
            $this->getTranslatorFacade(),
            $this->getProductConcreteTableExpanderPlugins()
        );
    }

    /**
     * @phpstan-param array<mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductConcreteBulkForm(?ProductConcreteTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductConcreteBulkForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): ProductMerchantPortalGuiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): ProductMerchantPortalGuiToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductMerchantPortalGuiToProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ProductMerchantPortalGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): ProductMerchantPortalGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductMerchantPortalGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductMerchantPortalGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductValidityFacadeInterface
     */
    public function getProductValidityFacade(): ProductMerchantPortalGuiToProductValidityFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_VALIDITY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface[]
     */
    public function getProductAbstractFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface[]
     */
    public function getProductConcreteTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRODUCT_CONCRETE_TABLE_EXPANDER);
    }
}
