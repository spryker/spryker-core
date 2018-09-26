<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleChoiceFormType;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;

class CompanyUserRoleFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';

    /**
     * @var \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @param \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface $companyRoleFacade
     */
    public function __construct(CompanyRoleGuiToCompanyRoleFacadeInterface $companyRoleFacade)
    {
        $this->companyRoleFacade = $companyRoleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $companyUserTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        [$choicesValues, $choicesAttributes] = $this->prepareCompanyRoleAttributeMap();

        return [
            CompanyUserRoleChoiceFormType::OPTION_VALUES_ROLES_CHOICES => $choicesValues,
            CompanyUserRoleChoiceFormType::OPTION_ATTRIBUTES_ROLES_CHOICES => $choicesAttributes,
        ];
    }

    /**
     * Retrieves the list of roles for the same company.
     *
     * @return array [[roleKey => idRole], [roleKey => ['data-id-company' => idCompany]]]
     *                Where roleKey: "<idRole> - <RoleName>"
     */
    protected function prepareCompanyRoleAttributeMap(): array
    {
        $values = [];
        $attributes = [];
        $companyRoleCollection = $this->companyRoleFacade->getCompanyRoleCollection(
            (new CompanyRoleCriteriaFilterTransfer())
        );

        foreach ($companyRoleCollection->getRoles() as $roleTransfer) {
            $roleKey = sprintf('%s - %s', $roleTransfer->getIdCompanyRole(), $roleTransfer->getName());
            $values[$roleKey] = $roleTransfer->getIdCompanyRole();
            $attributes[$roleKey] = [static::OPTION_ATTRIBUTE_DATA => $roleTransfer->getFkCompany()];
        }

        return [$values, $attributes];
    }
}
