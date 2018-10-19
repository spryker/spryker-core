<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleForm;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;

class CompanyUserRoleFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';
    protected const OPTION_IS_DEFAULT = 'data-is_default';

    protected const FORMAT_NAME = '%s (id: %s)';

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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function getOptions(CompanyUserTransfer $companyUserTransfer): array
    {
        [$choicesValues, $choicesAttributes] = $this->prepareCompanyRoleAttributeMap($companyUserTransfer);

        return [
            CompanyUserRoleForm::OPTION_VALUES_ROLES_CHOICES => $choicesValues,
            CompanyUserRoleForm::OPTION_ATTRIBUTES_ROLES_CHOICES => $choicesAttributes,
        ];
    }

    /**
     * Retrieves the list of roles for the same company.
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array [[roleKey => idRole], [roleKey => ['data-id-company' => idCompany]]]
     *                Where roleKey: "<idRole> - <RoleName>"
     */
    protected function prepareCompanyRoleAttributeMap(CompanyUserTransfer $companyUserTransfer): array
    {
        $values = [];
        $attributes = [];
        $companyRoleCollection = $this->companyRoleFacade->getCompanyRoleCollection(
            (new CompanyRoleCriteriaFilterTransfer())
        );

        foreach ($companyRoleCollection->getRoles() as $companyRoleTransfer) {
            $roleKey = $this->generateCompanyRoleName($companyRoleTransfer);

            $companyRoleAttributes = [static::OPTION_ATTRIBUTE_DATA => $companyRoleTransfer->getFkCompany()];
            if ($companyRoleTransfer->getIsDefault()
                && $companyUserTransfer->getCompanyRoleCollection() === null
            ) {
                $companyRoleAttributes[static::OPTION_IS_DEFAULT] = true;
            }

            $values[$roleKey] = $companyRoleTransfer->getIdCompanyRole();
            $attributes[$roleKey] = $companyRoleAttributes;
        }

        return [$values, $attributes];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return string
     */
    protected function generateCompanyRoleName(CompanyRoleTransfer $companyRoleTransfer): string
    {
        return sprintf(static::FORMAT_NAME, $companyRoleTransfer->getName(), $companyRoleTransfer->getIdCompanyRole());
    }
}
