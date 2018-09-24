<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
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
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(?int $idCompanyUser = null): CompanyUserTransfer
    {
        $companyUserTransfer = $this->createCompanyUserTransfer();

        if (!$idCompanyUser) {
            return $companyUserTransfer;
        }

        return $this->companyUserFacade->getCompanyUserById($companyUserTransfer->setIdCompanyUser($idCompanyUser));
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerCompanyAttachForm::FIELD_COMPANY => $this->createCompanyList(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(): CompanyUserTransfer
    {
        return new CompanyUserTransfer();
    }

    /**
     * @return array
     */
    protected function createCompanyList()
    {
        $companyCollection = $this->companyFacade->getCompanies();
        $companies = [];

        foreach ($companyCollection->getCompanies() as $companyTransfer) {
            $companies[$companyTransfer->getIdCompany()] = $companyTransfer->getName();
        }

        return $companies;
    }
}
