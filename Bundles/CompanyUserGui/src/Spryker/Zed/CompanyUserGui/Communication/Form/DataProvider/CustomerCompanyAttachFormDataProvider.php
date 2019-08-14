<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUserGui\Communication\Form\CustomerCompanyAttachForm;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeInterface;

class CustomerCompanyAttachFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserGuiToCompanyFacadeInterface $companyFacade,
        CompanyUserGuiToCustomerFacadeInterface $customerFacade
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->companyFacade = $companyFacade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(int $idCustomer): CompanyUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($idCustomer);

        $customerTransfer = $this->customerFacade->findCustomerById($customerTransfer);

        return (new CompanyUserTransfer())
            ->setCustomer($customerTransfer)
            ->setFkCustomer($customerTransfer->getIdCustomer());
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CompanyUserTransfer::class,
            CustomerCompanyAttachForm::OPTION_COMPANY_CHOICES => $this->createCompanyList(),
        ];
    }

    /**
     * @return int[] [company name => company id]
     */
    protected function createCompanyList(): array
    {
        $companies = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $companyTransfer) {
            $companies[$companyTransfer->getName()] = $companyTransfer->getIdCompany();
        }

        return $companies;
    }
}
