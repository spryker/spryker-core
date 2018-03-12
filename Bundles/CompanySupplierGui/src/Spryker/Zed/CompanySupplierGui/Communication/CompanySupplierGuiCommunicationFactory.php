<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication;

use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm;
use Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider\CompanySupplierFormDataProvider;
use Spryker\Zed\CompanySupplierGui\CompanySupplierGuiDependencyProvider;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
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
}
