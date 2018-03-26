<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication;

use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm;
use Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider\CompanySupplierFormDataProvider;
use Spryker\Zed\CompanySupplierGui\Communication\Table\ProductSupplierTable;
use Spryker\Zed\CompanySupplierGui\CompanySupplierGuiDependencyProvider;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer\CompanySupplierGuiToCompanySupplierQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanySupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    public function getCompanySupplierFacade(): CompanySupplierGuiToCompanySupplierFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_COMPANY_SUPPLIER);
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer\CompanySupplierGuiToCompanySupplierQueryContainerInterface
     */
    protected function getCompanySupplierQueryContainer(): CompanySupplierGuiToCompanySupplierQueryContainerInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::QUERY_CONTAINER_COMPANY_SUPPLIER);
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm
     */
    public function createCompanySupplierForm(): CompanySupplierForm
    {
        return new CompanySupplierForm();
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider\CompanySupplierFormDataProvider
     */
    public function createCompanySupplierFormDataProvider(): CompanySupplierFormDataProvider
    {
        return new CompanySupplierFormDataProvider(
            $this->getCompanySupplierFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Communication\Table\ProductSupplierTable
     */
    public function createProductSuppliersTable(int $idCompany): ProductSupplierTable
    {
        return new ProductSupplierTable(
            $idCompany,
            $this->getCompanySupplierQueryContainer(),
            $this->getMoneyFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeInterface
     */
    protected function getMoneyFacade(): CompanySupplierGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeInterface
     */
    protected function getStoreFacade(): CompanySupplierGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CompanySupplierGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_CURRENCY);
    }
}
