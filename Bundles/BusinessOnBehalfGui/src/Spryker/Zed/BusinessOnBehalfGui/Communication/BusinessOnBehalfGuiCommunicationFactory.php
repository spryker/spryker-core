<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication;

use Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiDependencyProvider;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CompanyUserTableButtonCreator;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CompanyUserTableButtonCreatorInterface;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CustomerTableButtonCreator;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CustomerTableButtonCreatorInterface;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class BusinessOnBehalfGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider
     */
    public function createCustomerCompanyAttachFormDataProvider(): CustomerBusinessUnitAttachFormDataProvider
    {
        return new CustomerBusinessUnitAttachFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): BusinessOnBehalfGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): BusinessOnBehalfGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): BusinessOnBehalfGuiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param int $idCustomer
     * @param int $idCompany
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerBusinessUnitAttachForm(int $idCustomer, int $idCompany): FormInterface
    {
        $dataProvider = $this->createCustomerCompanyAttachFormDataProvider();

        return $this->getFormFactory()->create(
            CustomerBusinessUnitAttachForm::class,
            $dataProvider->getData($idCustomer, $idCompany),
            $dataProvider->getOptions($idCompany)
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGuiExtension\Dependency\Plugin\CustomerBusinessUnitAttachFormExpanderPluginInterface[]
     */
    public function getCustomerBusinessUnitAttachFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(
            BusinessOnBehalfGuiDependencyProvider::PLUGINS_CUSTOMER_BUSINESS_UNIT_ATTACH_FORM_EXPANDER
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CompanyUserTableButtonCreatorInterface
     */
    public function createCompanyUserTableButtonCreator(): CompanyUserTableButtonCreatorInterface
    {
        return new CompanyUserTableButtonCreator();
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\CustomerTableButtonCreatorInterface
     */
    public function createCustomerTableButtonCreator(): CustomerTableButtonCreatorInterface
    {
        return new CustomerTableButtonCreator(
            $this->getCompanyUserFacade()
        );
    }
}
