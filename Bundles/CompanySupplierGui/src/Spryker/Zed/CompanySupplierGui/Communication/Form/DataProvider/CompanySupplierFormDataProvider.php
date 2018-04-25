<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider;

use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;

class CompanySupplierFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    /**
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct(CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade)
    {
        $this->companySupplierFacade = $companySupplierFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CompanySupplierForm::OPTION_VALUES_COMPANY_SUPPLIER => $this->getSuppliersForSelect(),
        ];
    }

    /**
     * @return array
     */
    protected function getSuppliersForSelect(): array
    {
        $result = [];
        /** @var \Generated\Shared\Transfer\SpyCompanyEntityTransfer $supplier */
        foreach ($this->companySupplierFacade->getAllSuppliers()->getSuppliers() as $supplier) {
            $result[$supplier->getName()] = $supplier->getIdCompany();
        }

        return $result;
    }
}
