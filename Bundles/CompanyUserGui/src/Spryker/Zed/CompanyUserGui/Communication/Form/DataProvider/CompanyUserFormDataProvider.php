<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserCustomerForm;
use Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserForm;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;

class CompanyUserFormDataProvider
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
        return $this->getCompanyUserTransfer($idCompanyUser);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CompanyUserTransfer::class,
            CompanyUserForm::OPTION_COMPANY_CHOICES => $this->prepareCompanyChoices(),
            CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            CompanyUserCustomerForm::OPTION_GENDER_CHOICES => $this->getGenderChoices(),
        ];
    }

    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCompanyUserTransfer(?int $idCompanyUser = null): CompanyUserTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();

        if ($idCompanyUser === null) {
            return $companyUserTransfer;
        }

        return $this->companyUserFacade->findCompanyUserById($idCompanyUser) ?? $companyUserTransfer;
    }

    /**
     * @return int[] [company name => company id]
     */
    protected function prepareCompanyChoices(): array
    {
        $result = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $company) {
            $result[$company->getName()] = $company->getIdCompany();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSalutationChoices(): array
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutationSet, $salutationSet);
    }

    /**
     * @return array
     */
    protected function getGenderChoices(): array
    {
        $genderSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_GENDER);

        return array_combine($genderSet, $genderSet);
    }
}
