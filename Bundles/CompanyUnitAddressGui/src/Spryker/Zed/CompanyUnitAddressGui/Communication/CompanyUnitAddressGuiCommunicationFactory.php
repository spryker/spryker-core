<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication;

use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\CompanyUnitAddressForm;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable;
use Spryker\Zed\CompanyUnitAddressGui\CompanyUnitAddressGuiDependencyProvider;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface;
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
            $this->getCompanyUnitAddressQueryContainer()
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
     * @param int $idCompanyUnitAddress
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyUnitAddressForm(int $idCompanyUnitAddress): FormInterface
    {
        $companyUnitAddressDataProvider = $this->createCompanyUnitAddressDataProvider();

        return $this->getFormFactory()->create(
            CompanyUnitAddressForm::class,
            $companyUnitAddressDataProvider->getData($idCompanyUnitAddress),
            $companyUnitAddressDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface[]
     */
    public function getCompanyUnitAddressFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_FORM);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider
     */
    public function createCompanyUnitAddressDataProvider(): CompanyUnitAddressFormDataProvider
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressFacade()
        );
    }
}
