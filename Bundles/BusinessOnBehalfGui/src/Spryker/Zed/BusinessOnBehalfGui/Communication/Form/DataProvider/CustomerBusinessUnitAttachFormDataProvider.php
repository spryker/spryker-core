<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface;

class CustomerBusinessUnitAttachFormDataProvider
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        BusinessOnBehalfGuiToCompanyFacadeInterface $companyFacade,
        BusinessOnBehalfGuiToCustomerFacadeInterface $customerFacade
    ) {
        $this->companyFacade = $companyFacade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param int $idCustomer
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(int $idCustomer, int $idCompany): CompanyUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($idCustomer);

        $companyTransfer = (new CompanyTransfer())
            ->setIdCompany($idCompany);

        $customerTransfer = $this->customerFacade->findCustomerById($customerTransfer);
        $companyTransfer = $this->companyFacade->getCompanyById($companyTransfer);

        return (new CompanyUserTransfer())
            ->setCustomer($customerTransfer)
            ->setCompany($companyTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CompanyUserTransfer::class,
        ];
    }
}
