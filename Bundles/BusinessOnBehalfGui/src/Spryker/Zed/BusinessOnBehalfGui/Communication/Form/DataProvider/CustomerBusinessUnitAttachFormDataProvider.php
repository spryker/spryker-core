<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface;

class CustomerBusinessUnitAttachFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';
    protected const OPTION_IS_DEFAULT = 'data-is_default';

    protected const FORMAT_NAME = '%s (id: %s)';

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyRoleFacadeInterface $companyRoleFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        BusinessOnBehalfGuiToCompanyRoleFacadeInterface $companyRoleFacade,
        BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyRoleFacade = $companyRoleFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(int $idCompanyUser): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($idCompanyUser);

        $defaultCompanyRole = $this->companyRoleFacade->findDefaultCompanyRoleByIdCompany($companyUserTransfer->getCompany()->getIdCompany());
        $companyRoleCollectionWithDefaultCompanyRole = (new CompanyRoleCollectionTransfer())
            ->addRole($defaultCompanyRole);

        return (new CompanyUserTransfer())
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setCompany($companyUserTransfer->getCompany())
            ->setCompanyRoleCollection($companyRoleCollectionWithDefaultCompanyRole)
            ->setFkCompany($companyUserTransfer->getCompany()->getIdCompany())
            ->setFkCustomer($companyUserTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function getOptions(CompanyUserTransfer $companyUserTransfer): array
    {
        [$companyBusinessUnitChoicesValues, $companyBusinessUnitChoicesAttributes] = $this->prepareCompanyBusinessUnitAttributeMap($companyUserTransfer);
        [$companyRolesChoicesValues, $companyRolesChoicesAttributes] = $this->prepareCompanyRoleAttributeMap($companyUserTransfer);

        return [
            CustomerBusinessUnitAttachForm::OPTION_VALUES_BUSINESS_UNITS_CHOICES => $companyBusinessUnitChoicesValues,
            CustomerBusinessUnitAttachForm::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES => $companyBusinessUnitChoicesAttributes,
            CustomerBusinessUnitAttachForm::OPTION_VALUES_ROLES_CHOICES => $companyRolesChoicesValues,
            CustomerBusinessUnitAttachForm::OPTION_ATTRIBUTES_ROLES_CHOICES => $companyRolesChoicesAttributes,
            'data_class' => CompanyUserTransfer::class,
        ];
    }

    /**
     * Retrieves the list of units for the same company.
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array [[unitKey => idBusinessUnit], [unitKey => ['data-id-company' => idCompany]]]
     *                Where unitKey: "<idBusinessUnit> - <BusinessUnitName>"
     */
    protected function prepareCompanyBusinessUnitAttributeMap(CompanyUserTransfer $companyUserTransfer): array
    {
        $values = [];
        $attributes = [];

        $companyBusinessUnitCollection = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompany()->getIdCompany())
        );

        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $unitKey = $this->generateCompanyBusinessUnitName($companyBusinessUnitTransfer);

            $values[$unitKey] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
            $attributes[$unitKey] = [static::OPTION_ATTRIBUTE_DATA => $companyBusinessUnitTransfer->getFkCompany()];
        }

        return [$values, $attributes];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function generateCompanyBusinessUnitName(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(static::FORMAT_NAME, $companyBusinessUnitTransfer->getName(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
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
            (new CompanyRoleCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompany()->getIdCompany())
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
