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
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyUnitAddressGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable
     */
    public function createAddressTable()
    {
        $cmsBlockQuery = $this->getCompanyUnitAddressGuiQueryContainer()
            ->queryCompanyUnitAddress();

        return new CompanyUnitAddressTable(
            $cmsBlockQuery,
            $this->getCompanyUnitAddressGuiQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
     */
    public function getCompanyUnitAddressGuiQueryContainer()
    {
        return $this->getProvidedDependency(
            CompanyUnitAddressGuiDependencyProvider::QUERY_CONTAINER_COMPANY_UNIT_ADDRESS
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
     */
    public function getCompanyUnitAddressFacade()
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
    public function createCompanyUnitAddressForm(int $idCompanyUnitAddress)
    {
        $companyUnitAddressDataProvider = $this->createCompanyUnitAddressDataProvider();

        return $this->getFormFactory()->create(
            CompanyUnitAddressForm::class,
            $companyUnitAddressDataProvider->getData($idCompanyUnitAddress),
            $companyUnitAddressDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin\EditCompanyUnitAddressExpanderPluginInterface[]
     */
    public function getCompanyUnitAddressFormPlugins()
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::PLUGINS_COMPANY_UNIT_ADDRESS_FORM);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Form\DataProvider\CompanyUnitAddressFormDataProvider
     */
    protected function createCompanyUnitAddressDataProvider()
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressGuiQueryContainer(),
            $this->getCompanyUnitAddressFacade()
        );
    }
}
