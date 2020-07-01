<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferCreateForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferUpdateForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\QuantityTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\StoresTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable
     */
    public function createProductTable(): ProductTable
    {
        return new ProductTable(
            $this->getTranslatorFacade(),
            $this->createProductTableDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface
     */
    public function createProductTableDataProvider(): TableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->createGuiTableDataRequestBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable
     */
    public function createProductOfferTable(): AbstractTable
    {
        return new ProductOfferTable(
            $this->getTranslatorFacade(),
            $this->createProductOfferTableDataProvider(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): TableDataProviderInterface
    {
        return new ProductOfferTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->createGuiTableDataRequestBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    public function createProductNameBuilder(): ProductNameBuilderInterface
    {
        return new ProductNameBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface
     */
    public function createGuiTableDataRequestBuilder(): GuiTableDataRequestBuilderInterface
    {
        return new GuiTableDataRequestBuilder(
            $this->getUtilEncodingService(),
            $this->getLocaleFacade(),
            $this->getFilterValueNormalizerPlugins()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOfferCreateForm(?ProductOfferTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductOfferCreateForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOfferUpdateForm(?ProductOfferTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductOfferUpdateForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface
     */
    public function createProductOfferCreateFormDataProvider(): ProductOfferCreateFormDataProviderInterface
    {
        return new ProductOfferCreateFormDataProvider(
            $this->getCurrencyFacade(),
            $this->getPriceProductFacade(),
            $this->getProductFacade(),
            $this->getMerchantUserFacade(),
            $this->getMerchantStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProviderInterface
     */
    public function createProductOfferUpdateFormDataProvider(): ProductOfferUpdateFormDataProviderInterface
    {
        return new ProductOfferUpdateFormDataProvider(
            $this->getCurrencyFacade(),
            $this->getPriceProductFacade(),
            $this->getProductFacade(),
            $this->getProductOfferFacade(),
            $this->getMerchantStockFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStoresTransformer(): DataTransformerInterface
    {
        return new StoresTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createQuantityTransformer(): DataTransformerInterface
    {
        return new QuantityTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[]
     */
    public function getFilterValueNormalizerPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PLUGINS_FILTER_VALUE_NORMALIZER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductOfferMerchantPortalGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
