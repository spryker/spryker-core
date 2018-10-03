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
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface $companyFacade
     */
    public function __construct(
        CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserGuiToCompanyFacadeInterface $companyFacade
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->companyFacade = $companyFacade;
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

        return (new CompanyUserTransfer())
            ->setCustomer($customerTransfer);
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
