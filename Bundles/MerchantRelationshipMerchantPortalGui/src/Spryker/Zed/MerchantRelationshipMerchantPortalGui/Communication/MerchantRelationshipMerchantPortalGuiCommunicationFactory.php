<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilder;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\MerchantRelationshipResponseBuilder;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\MerchantRelationshipResponseBuilderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\DataProvider\MerchantDashboardCardDataProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\DataProvider\MerchantDashboardCardDataProviderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Deleter\MerchantRelationshipDeleter;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Deleter\MerchantRelationshipDeleterInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\DataProvider\MerchantRelationshipFormDataProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\DataTransformer\AssigneeCompanyBusinessUnitsDataTransformer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\MerchantRelationshipDeleteForm;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\MerchantRelationshipForm;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Grouper\CompanyBusinessUnitAddressGrouper;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Grouper\CompanyBusinessUnitAddressGrouperInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProviderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProviderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProviderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipGuiTableDataProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapper;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapperInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Updater\MerchantRelationshipUpdater;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Updater\MerchantRelationshipUpdaterInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

/**
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class MerchantRelationshipMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProviderInterface
     */
    public function createMerchantRelationshipGuiTableConfigurationProvider(): MerchantRelationshipGuiTableConfigurationProviderInterface
    {
        return new MerchantRelationshipGuiTableConfigurationProvider(
            $this->getConfig(),
            $this->createMerchantRelationshipReader(),
            $this->getGuiTableFactory(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProviderInterface
     */
    public function createMerchantRelationshipDashboardGuiTableConfigurationProvider(): MerchantRelationshipDashboardGuiTableConfigurationProviderInterface
    {
        return new MerchantRelationshipDashboardGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createMerchantRelationshipDashboardGuiTableDataProvider(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createMerchantRelationshipGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new MerchantRelationshipGuiTableDataProvider(
            $this->createMerchantRelationshipGuiTableMapper(),
            $this->getMerchantRelationshipFacade(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantRelationshipDashboardGuiTableDataProviderInterface
     */
    public function createMerchantRelationshipDashboardGuiTableDataProvider(): MerchantRelationshipDashboardGuiTableDataProviderInterface
    {
        return new MerchantRelationshipDashboardGuiTableDataProvider($this->getConfig());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createMerchantRelationshipForm(MerchantRelationshipTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantRelationshipForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantRelationshipDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantRelationshipDeleteForm::class);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\DataProvider\MerchantRelationshipFormDataProvider
     */
    public function createMerchantRelationshipFormDataProvider(): MerchantRelationshipFormDataProvider
    {
        return new MerchantRelationshipFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createAssigneeCompanyBusinessUnitsDataTransformer(): DataTransformerInterface
    {
        return new AssigneeCompanyBusinessUnitsDataTransformer();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getConfig(),
            $this->getMerchantRelationshipFacade(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Grouper\CompanyBusinessUnitAddressGrouperInterface
     */
    public function createCompanyBusinessUnitAddressGrouper(): CompanyBusinessUnitAddressGrouperInterface
    {
        return new CompanyBusinessUnitAddressGrouper($this->createCompanyBusinessUnitAddressBuilder());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Updater\MerchantRelationshipUpdaterInterface
     */
    public function createMerchantRelationshipUpdater(): MerchantRelationshipUpdaterInterface
    {
        return new MerchantRelationshipUpdater($this->getMerchantRelationshipFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Deleter\MerchantRelationshipDeleterInterface
     */
    public function createMerchantRelationshipDeleter(): MerchantRelationshipDeleterInterface
    {
        return new MerchantRelationshipDeleter($this->getMerchantRelationshipFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\MerchantRelationshipResponseBuilderInterface
     */
    public function createMerchantRelationshipResponseBuilder(): MerchantRelationshipResponseBuilderInterface
    {
        return new MerchantRelationshipResponseBuilder(
            $this->getZedUiFactory(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilderInterface
     */
    public function createCompanyBusinessUnitAddressBuilder(): CompanyBusinessUnitAddressBuilderInterface
    {
        return new CompanyBusinessUnitAddressBuilder();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\Mapper\MerchantRelationshipGuiTableMapperInterface
     */
    public function createMerchantRelationshipGuiTableMapper(): MerchantRelationshipGuiTableMapperInterface
    {
        return new MerchantRelationshipGuiTableMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\DataProvider\MerchantDashboardCardDataProviderInterface
     */
    public function createMerchantDashboardCardDataProvider(): MerchantDashboardCardDataProviderInterface
    {
        return new MerchantDashboardCardDataProvider(
            $this->createMerchantRelationshipReader(),
            $this->getMerchantUserFacade(),
            $this->getTwigEnvironment(),
            $this->createMerchantRelationshipDashboardGuiTableConfigurationProvider(),
            $this->getMerchantRelationshipMerchantDashboardCardExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin\MerchantRelationshipMerchantDashboardCardExpanderPluginInterface>
     */
    public function getMerchantRelationshipMerchantDashboardCardExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_RELATIONSHIP_MERCHANT_DASHBOARD_CARD_EXPANDER);
    }
}
