<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Dependency\Facade;

class CompanySupplierGuiToCompanySupplierFacadeBridge implements CompanySupplierGuiToCompanySupplierFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    /**
     * @param \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct($companySupplierFacade)
    {
        $this->companySupplierFacade = $companySupplierFacade;
    }

    /**
     * @return array
     */
    public function getAllSuppliers(): array
    {
        return $this->companySupplierFacade->getAllSuppliers();
    }
}
