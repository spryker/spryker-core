<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Validator;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationValidator implements InvitationValidatorInterface
{
    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array
     */
    protected $businessUnitNameCache = [];

    /**
     * @var array
     */
    protected $emailCache = [];

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->repository = $repository;
        $this->companyUserFacade = $companyUserFacade;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    public function isValidInvitation(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        return $this->isValidBusinessUnit($invitationTransfer) && $this->isValidEmail($invitationTransfer);
    }

    /**
     * @return string
     */
    public function getLastErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    protected function isValidBusinessUnit(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        if (!$this->businessUnitNameCache) {
            $this->populateBusinessUnitCache($invitationTransfer);
        }

        if (!in_array($invitationTransfer->getCompanyBusinessUnitName(), $this->businessUnitNameCache)) {
            $this->errorMessage = sprintf('Business Unit %s is not valid', $invitationTransfer->getCompanyBusinessUnitName());

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    protected function isValidEmail(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        if (!$this->emailCache) {
            $this->populateEmailCache($invitationTransfer);
        }

        if (in_array($invitationTransfer->getEmail(), $this->emailCache)) {
            $this->errorMessage = sprintf('Invitation for %s is already imported', $invitationTransfer->getEmail());

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return void
     */
    protected function populateBusinessUnitCache(CompanyUserInvitationTransfer $invitationTransfer)
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($invitationTransfer->getFkCompanyUser());
        $companyBusinessUnitCriteriaFilter = new CompanyBusinessUnitCriteriaFilterTransfer();
        $companyBusinessUnitCriteriaFilter->setIdCompany($companyUserTransfer->getFkCompany());
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            $companyBusinessUnitCriteriaFilter
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $this->businessUnitNameCache[] = $companyBusinessUnitTransfer->getName();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return void
     */
    protected function populateEmailCache(CompanyUserInvitationTransfer $invitationTransfer)
    {
        $companyUserInvitationCriteriaFilterTransfer = new CompanyUserInvitationCriteriaFilterTransfer();
        $companyUserInvitationCriteriaFilterTransfer->setFkCompanyUser($invitationTransfer->getFkCompanyUser());
        $companyUserInvitationCollection = $this->repository->getCompanyUserInvitationCollection(
            $companyUserInvitationCriteriaFilterTransfer
        );

        foreach ($companyUserInvitationCollection->getInvitations() as $companyUserInvitationTransfer) {
            $this->emailCache[] = $companyUserInvitationTransfer->getEmail();
        }
    }
}
