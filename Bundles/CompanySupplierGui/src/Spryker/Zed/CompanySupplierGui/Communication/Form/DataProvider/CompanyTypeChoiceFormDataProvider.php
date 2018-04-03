<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider;

use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanyTypeChoiceFormType;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;

class CompanyTypeChoiceFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    /**
     * @param \Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct(
        CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
    ) {
        $this->companySupplierFacade = $companySupplierFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CompanyTypeChoiceFormType::OPTION_VALUES_COMPANY_TYPE_CHOICES => $this->getCompanyTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getCompanyTypes(): array
    {
        $companyTypeCollection = $this->companySupplierFacade->getCompanyTypes();

        $result = [];
        foreach ($companyTypeCollection->getCompanyTypes() as $companyType) {
            $result[$companyType->getName()] = $companyType->getIdCompanyType();
        }

        return $result;
    }
}
