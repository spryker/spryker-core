<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Action\ActionInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Action\DeletePricesAction;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Action\SavePricesAction;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilder;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CurrencyFilterConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\StoreFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\StoreFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\PriceProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeTableDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\PriceProductTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface;
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
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface
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
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProviderInterface
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
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface
     */
    public function createProductAbstractFormDataProvider(): ProductAbstractFormDataProviderInterface
    {
        return new ProductAbstractFormDataProvider(
            $this->getMerchantProductFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface
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
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    public function createStoreFilterOptionsProvider(): StoreFilterOptionsProviderInterface
    {
        return new StoreFilterOptionsProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface
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
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper
     */
    public function createPriceProductMapper(): PriceProductMapper
    {
        return new PriceProductMapper($this->getPriceProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Action\ActionInterface
     */
    public function createSavePricesAction(): ActionInterface
    {
        return new SavePricesAction(
            $this->getPriceProductFacade(),
            $this->getMoneyFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Action\ActionInterface
     */
    public function createDeletePricesAction(): ActionInterface
    {
        return new DeletePricesAction(
            $this->getMerchantUserFacade(),
            $this->getMerchantProductFacade(),
            $this->getPriceProductFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface
     */
    public function createProductAttributeGuiTableConfigurationProvider(): ProductAttributeGuiTableConfigurationProviderInterface
    {
        return new ProductAttributeGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createProductAttributeTableDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeTableDataProviderInterface
     */
    public function createProductAttributeTableDataProvider(): ProductAttributeTableDataProviderInterface
    {
        return new ProductAttributeTableDataProvider();
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
}
