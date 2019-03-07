<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleCreateForm;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface;

class CompanyRoleCreateDataProvider
{
    protected const GLOSSARY_KEY_PREFIX_PERMISSION_NAME = 'permission.name.';

    /**
     * @var \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @var \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface $companyRoleFacade
     * @param \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        CompanyRoleGuiToCompanyFacadeInterface $companyFacade,
        CompanyRoleGuiToCompanyRoleFacadeInterface $companyRoleFacade,
        CompanyRoleGuiToGlossaryFacadeInterface $glossaryFacade,
        CompanyRoleGuiToPermissionFacadeInterface $permissionFacade
    ) {
        $this->companyFacade = $companyFacade;
        $this->companyRoleFacade = $companyRoleFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getData(?CompanyRoleTransfer $companyRoleTransfer = null): CompanyRoleTransfer
    {
        if ($companyRoleTransfer !== null) {
            $companyRoleTransfer = $this->companyRoleFacade->findCompanyRoleById($companyRoleTransfer);
        }

        if ($companyRoleTransfer) {
            $companyRoleTransfer->setCompanyUserCollection(null);

            return $companyRoleTransfer;
        }

        return new CompanyRoleTransfer();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $availablePermissions = $this->prepareAvailablePermissions(
            $this->permissionFacade->findMergedRegisteredNonInfrastructuralPermissions()
        );

        $availableCompanies = $this->prepareAvailableCompanies(
            $this->companyFacade->getCompanies()
        );

        return [
            CompanyRoleCreateForm::OPTION_COMPANY_CHOICES => $availableCompanies,
            CompanyRoleCreateForm::OPTION_PERMISSION_CHOICES => $availablePermissions,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyCollectionTransfer $companyCollectionTransfer
     *
     * @return array
     */
    protected function prepareAvailableCompanies(CompanyCollectionTransfer $companyCollectionTransfer): array
    {
        $preparedCompanies = [];

        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $preparedCompanies[$companyTransfer->getName()] = $companyTransfer->getIdCompany();
        }

        return $preparedCompanies;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return array
     */
    protected function prepareAvailablePermissions(PermissionCollectionTransfer $permissionCollectionTransfer): array
    {
        $preparedPermissions = [];

        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            $permissionName = $this->getPermissionVerboseName($permissionTransfer);

            $preparedPermissions[$permissionName] = $permissionTransfer->getIdPermission();
        }

        return $preparedPermissions;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return string
     */
    protected function getPermissionVerboseName(PermissionTransfer $permissionTransfer): string
    {
        $permissionTransfer->requireKey();

        return $this->glossaryFacade->translate(
            static::GLOSSARY_KEY_PREFIX_PERMISSION_NAME . $permissionTransfer->getKey()
        );
    }
}
