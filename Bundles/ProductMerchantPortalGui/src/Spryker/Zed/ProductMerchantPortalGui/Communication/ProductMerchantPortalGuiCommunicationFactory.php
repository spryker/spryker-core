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
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilder;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Builder\ProductAbstractNameBuilderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiDependencyProvider;
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
            $this->getStoreFacade(),
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
}
