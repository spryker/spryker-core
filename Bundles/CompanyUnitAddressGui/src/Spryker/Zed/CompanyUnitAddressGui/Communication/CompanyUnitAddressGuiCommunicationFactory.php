<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication;

use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyBusinessUnitAddressChoiceFormType;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyUnitAddressForm;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyBusinessUnitAddressFormDataProvider;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutor;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutorInterface;
use Spryker\Zed\CompanyUnitAddressGui\CompanyUnitAddressGuiDependencyProvider;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyUnitAddressGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable
     */
    public function createAddressTable(): CompanyUnitAddressTable
    {
        return new CompanyUnitAddressTable(
            $this->getCompanyUnitAddressQueryContainer(),
            $this->createCompanyUnitAddressTablePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor\CompanyUnitAddressTablePluginExecutorInterface
     */
    protected function createCompanyUnitAddressTablePluginExecutor(): CompanyUnitAddressTablePluginExecutorInterface
    {
        return new CompanyUnitAddressTablePluginExecutor(
            $this->getCompanyUnitAddressTableConfigExpanderPlugins(),
            $this->getCompanyUnitAddressTableHeaderExpanderPlugins(),
            $this->getCompanyUnitAddressTableDataExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
     */
    public function getCompanyUnitAddressQueryContainer(): CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::QUERY_CONTAINER_COMPANY_UNIT_ADDRESS
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
     */
    public function getCompanyUnitAddressFacade(): CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyUnitAddressGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COMPANY
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeInterface
     */
    public function getCountryFacade(): CompanyUnitAddressGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::FACADE_COUNTRY
        );
    }

    /**
     * @param int|null $idCompanyUnitAddress
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyUnitAddressForm(?int $idCompanyUnitAddress = null): FormInterface
    {
        $companyUnitAddressDataProvider = $this->createCompanyUnitAddressDataProvider();

        return $this->getFormFactory()->create(
            CompanyUnitAddressForm::class,
            $companyUnitAddressDataProvider->getData($idCompanyUnitAddress),
            $companyUnitAddressDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface[]
     */
    public function getCompanyUnitAddressFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_FORM);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableExpanderInterface[]
     */
    public function getCompanyUnitAddressTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableExpanderInterface[]
     */
    public function getCompanyUnitAddressTableHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableExpanderInterface[]
     */
    public function getCompanyUnitAddressTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider
     */
    public function createCompanyUnitAddressDataProvider(): CompanyUnitAddressFormDataProvider
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressFacade(),
            $this->getCompanyFacade(),
            $this->getCountryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyBusinessUnitAddressFormDataProvider
     */
    public function createCompanyBusinessUnitAddressChoiceFormDataProvider(): CompanyBusinessUnitAddressFormDataProvider
    {
        return new CompanyBusinessUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyBusinessUnitAddressChoiceFormType
     */
    public function createCompanyBusinessUnitAddressChoiceFormType(): CompanyBusinessUnitAddressChoiceFormType
    {
        return new CompanyBusinessUnitAddressChoiceFormType();
    }
}
