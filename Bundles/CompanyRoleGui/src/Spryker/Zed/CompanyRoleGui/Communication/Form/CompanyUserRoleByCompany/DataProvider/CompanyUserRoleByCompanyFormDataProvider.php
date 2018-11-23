<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleByCompany\DataProvider;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleByCompany\CompanyUserRoleByCompanyForm;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;

class CompanyUserRoleByCompanyFormDataProvider
{
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
        $defaultCompanyRole = $this->companyRoleFacade->findDefaultCompanyRoleByIdCompany($companyUserTransfer->getCompany()->getIdCompany());
        $companyRoleCollectionWithDefaultCompanyRole = (new CompanyRoleCollectionTransfer())
            ->addRole($defaultCompanyRole);

        $companyUserTransfer->setCompanyRoleCollection($companyRoleCollectionWithDefaultCompanyRole);

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function getOptions(CompanyUserTransfer $companyUserTransfer): array
    {
        $companyRoleChoicesValues = $this->prepareCompanyRoleAttributeMap($companyUserTransfer);

        return [
            CompanyUserRoleByCompanyForm::OPTION_COMPANY_ROLE_CHOICES => $companyRoleChoicesValues,
        ];
    }

    /**
     * Retrieves the list of roles for the same company.
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array [[roleName => idRole]],
     *                Where roleName: "<idRole> - <RoleName>"
     */
    protected function prepareCompanyRoleAttributeMap(CompanyUserTransfer $companyUserTransfer): array
    {
        $companyRoleChoicesValues = [];

        $companyRoleCollection = $this->companyRoleFacade->getCompanyRoleCollection(
            (new CompanyRoleCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompany()->getIdCompany())
        );

        foreach ($companyRoleCollection->getRoles() as $companyRoleTransfer) {
            $roleName = $this->generateCompanyRoleName($companyRoleTransfer);
            $companyRoleChoicesValues[$roleName] = $companyRoleTransfer->getIdCompanyRole();
        }

        return $companyRoleChoicesValues;
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
