<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider;

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
    /**
     * @var string
     */
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
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        $availablePermissions = $this->prepareAvailablePermissions(
            $this->permissionFacade->findMergedRegisteredNonInfrastructuralPermissions(),
        );

        return [
            CompanyRoleCreateForm::OPTION_PERMISSION_CHOICES => $availablePermissions,
        ];
    }

    /**
     * @return array<int>
     */
    public function prepareAvailableCompanies(): array
    {
        $preparedCompanies = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $companyTransfer) {
            $key = sprintf(
                '%s (ID: %d)',
                $companyTransfer->getName(),
                $companyTransfer->getIdCompany(),
            );
            $preparedCompanies[$key] = $companyTransfer->getIdCompany();
        }

        return $preparedCompanies;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return array<int>
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
            static::GLOSSARY_KEY_PREFIX_PERMISSION_NAME . $permissionTransfer->getKey(),
        );
    }
}
