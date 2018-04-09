<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationValidator implements InvitationValidatorInterface
{
    /**
     * @var array
     */
    protected $businessUnitCache;

    /**
     * @var array
     */
    protected $emailCache;

    /**
     * @var string
     */
    protected $validationError;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    private $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface
     */
    private $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    public function isValidInvitation(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        return $this->isValidBusinessUnit($invitationTransfer) && $this->isUniqueEmail($invitationTransfer);
    }

    /**
     * @return string
     */
    public function getValidationError(): string
    {
        return $this->validationError;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    protected function isValidBusinessUnit(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        if (!$this->businessUnitCache) {
            $this->populateBusinessUnitCache($invitationTransfer);
        }

        $businessUnitName = $invitationTransfer->getCompanyBusinessUnit()->getName();
        if (!in_array($businessUnitName, $this->businessUnitCache)) {
            $this->validationError = sprintf('Business Unit %s is not valid', $businessUnitName);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    protected function isUniqueEmail(CompanyUserInvitationTransfer $invitationTransfer): bool
    {
        if (!$this->emailCache) {
            $this->populateEmailCache($invitationTransfer);
        }

        $email = $invitationTransfer->getEmail();
        if (in_array($email, $this->emailCache)) {
            $this->validationError = sprintf('Email %s is already used', $email);

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
        $companyBusinessUnitCriteriaFilter = new CompanyBusinessUnitCriteriaFilterTransfer();
        $companyBusinessUnitCriteriaFilter->setIdCompany($invitationTransfer->getCompanyUser()->getFkCompany());
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            $companyBusinessUnitCriteriaFilter
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $this->businessUnitCache[] = $companyBusinessUnitTransfer->getName();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return void
     */
    private function populateEmailCache(CompanyUserInvitationTransfer $invitationTransfer)
    {
        $companyUserInvitationCriteriaFilterTransfer = new CompanyUserInvitationCriteriaFilterTransfer();
        $companyUserInvitationCriteriaFilterTransfer->setFkCompanyUser($invitationTransfer->getCompanyUser()->getIdCompanyUser());
        $companyUserInvitationCollection = $this->repository->getCompanyUserInvitationCollection(
            $companyUserInvitationCriteriaFilterTransfer
        );

        foreach ($companyUserInvitationCollection->getInvitations() as $companyUserInvitationTransfer) {
            $this->emailCache[] = $companyUserInvitationTransfer->getEmail();
        }
    }
}
