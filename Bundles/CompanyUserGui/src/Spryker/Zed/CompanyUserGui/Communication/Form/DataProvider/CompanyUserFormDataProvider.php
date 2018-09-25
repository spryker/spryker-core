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
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToLocaleFacadeInterface;

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
     * @var \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CompanyUserGuiToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserGuiToCompanyFacadeInterface $companyFacade,
        CompanyUserGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->companyFacade = $companyFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(?int $idCompanyUser = null): CompanyUserTransfer
    {
        return $this->findCompanyUserTransfer($idCompanyUser);
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
            CompanyUserCustomerForm::OPTION_LOCALE_CHOICES => $this->getLocaleChoices(),
        ];
    }

    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function findCompanyUserTransfer(?int $idCompanyUser = null): CompanyUserTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();

        if ($idCompanyUser !== null) {
            $companyUserTransfer->setIdCompanyUser($idCompanyUser);

            return $this->companyUserFacade->getCompanyUserById($companyUserTransfer);
        }

        return $companyUserTransfer;
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
    protected function getSalutationChoices()
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutationSet, $salutationSet);
    }

    /**
     * @return array
     */
    protected function getGenderChoices()
    {
        $genderSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_GENDER);

        return array_combine($genderSet, $genderSet);
    }

    /**
     * @return array
     */
    protected function getLocaleChoices()
    {
        return $this->localeFacade->getAvailableLocales();
    }
}
