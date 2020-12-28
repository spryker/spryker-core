<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductOfferGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidProductOfferPriceIdsOwnByMerchantConstraint;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\PriceProductOfferTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\ProductOfferStockTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\QuantityTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\StoresTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\DataProvider\ProductOfferPriceGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Twig\Environment;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface
     */
    public function createProductGuiTableConfigurationProvider(): GuiTableConfigurationProviderInterface
    {
        return new ProductGuiTableConfigurationProvider(
            $this->getTranslatorFacade(),
            $this->getGuiTableFactory()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface
     */
    public function createProductOfferGuiTableConfigurationProvider(): GuiTableConfigurationProviderInterface
    {
        return new ProductOfferGuiTableConfigurationProvider(
            $this->getStoreFacade(),
            $this->getTranslatorFacade(),
            $this->getGuiTableFactory()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProviderInterface
     */
    public function createPriceProductOfferUpdateGuiTableConfigurationProvider(): PriceProductOfferUpdateGuiTableConfigurationProviderInterface
    {
        return new PriceProductOfferUpdateGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProviderInterface
     */
    public function createPriceProductOfferCreateGuiTableConfigurationProvider(): PriceProductOfferCreateGuiTableConfigurationProviderInterface
    {
        return new PriceProductOfferCreateGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductGuiTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductOfferGuiTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int|null $idProductOffer
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductOfferPriceTableDataProvider(?int $idProductOffer = null): GuiTableDataProviderInterface
    {
        return new ProductOfferPriceGuiTableDataProvider(
            $this->getMerchantUserFacade(),
            $this->getRepository(),
            $this->getMoneyFacade(),
            $this->getPriceProductFacade(),
            $idProductOffer
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
     * @phpstan-param array<mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOfferForm(?ProductOfferTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductOfferForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface
     */
    public function createProductOfferCreateFormDataProvider(): ProductOfferCreateFormDataProviderInterface
    {
        return new ProductOfferCreateFormDataProvider(
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
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createProductOfferStockTransformer(): DataTransformerInterface
    {
        return new ProductOfferStockTransformer();
    }

    /**
     * @param int|null $idProductOffer
     *
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceProductOfferTransformer(?int $idProductOffer = null): DataTransformerInterface
    {
        return new PriceProductOfferTransformer(
            $this->getUtilEncodingService(),
            $this->getPriceProductFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade(),
            $idProductOffer
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProviderInterface
     */
    public function createOffersDashboardCardProvider(): OffersDashboardCardProviderInterface
    {
        return new OffersDashboardCardProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getRouterFacade(),
            $this->getConfig(),
            $this->getTwigEnvironment()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper
     */
    public function createPriceProductOfferMapper(): PriceProductOfferMapper
    {
        return new PriceProductOfferMapper(
            $this->getPriceProductFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface
     */
    public function createMerchantOrderItemTableExpander(): MerchantOrderItemTableExpanderInterface
    {
        return new MerchantOrderItemTableExpander($this->getProductOfferFacade());
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
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): ProductOfferMerchantPortalGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
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

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface
     */
    public function getValidationAdapter(): ProductOfferMerchantPortalGuiToValidationAdapterInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::EXTERNAL_ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    public function getPriceProductOfferFacade(): ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT_OFFER);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidProductOfferPriceIdsOwnByMerchantConstraint(): SymfonyConstraint
    {
        return new ValidProductOfferPriceIdsOwnByMerchantConstraint($this->getPriceProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ProductOfferMerchantPortalGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MONEY);
    }
}
