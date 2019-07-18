<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface;

class CustomerBusinessUnitAttachFormDataProvider
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        BusinessOnBehalfGuiToCompanyFacadeInterface $companyFacade,
        BusinessOnBehalfGuiToCustomerFacadeInterface $customerFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
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
        $companyUserTransfer = new CompanyUserTransfer();

        $companyTransfer = $this->companyFacade->findCompanyById($idCompany);

        if (!$companyTransfer) {
            return $companyUserTransfer;
        }

        $companyUserTransfer->setFkCompany($idCompany);

        $customerTransfer = $this->customerFacade->findCustomerById(
            (new CustomerTransfer())->setIdCustomer($idCustomer)
        );

        if (!$customerTransfer) {
            return $companyUserTransfer;
        }

        return $companyUserTransfer
            ->setFkCustomer($idCustomer)
            ->setCompany($companyTransfer)
            ->setCustomer($customerTransfer);
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    public function getOptions(int $idCompany): array
    {
        return [
            CustomerBusinessUnitAttachForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES =>
                $this->getCompanyBusinessUnitChoices($idCompany),
        ];
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    protected function getCompanyBusinessUnitChoices(int $idCompany): array
    {
        $companyBusinessUnitChoicesValues = [];

        $companyBusinessUnitCollection = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($idCompany)
        );

        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $businessUnitName = $this->generateCompanyBusinessUnitName($companyBusinessUnitTransfer);
            $companyBusinessUnitChoicesValues[$businessUnitName] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        return $companyBusinessUnitChoicesValues;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function generateCompanyBusinessUnitName(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(
            '%s (id: %s)',
            $companyBusinessUnitTransfer->getName(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );
    }
}
