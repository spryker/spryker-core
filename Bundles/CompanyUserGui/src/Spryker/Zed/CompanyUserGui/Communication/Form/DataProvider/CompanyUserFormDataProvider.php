<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserCustomerForm;
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
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CompanyUserTransfer::class,
            CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            CompanyUserCustomerForm::OPTION_GENDER_CHOICES => $this->getGenderChoices(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function prepareCompanyChoices(): array
    {
        $companyChoices = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $companyTransfer) {
            $companyChoices[$companyTransfer->getName()] = $companyTransfer->getIdCompany();
        }

        return $companyChoices;
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
