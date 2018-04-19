<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\PostRegistration;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class CompanyUserCreator implements CompanyUserCreatorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface|\Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface
     */
    protected $invitationUpdater;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface $invitationUpdater
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade,
        InvitationUpdaterInterface $invitationUpdater
    ) {
        $this->repository = $repository;
        $this->companyUserFacade = $companyUserFacade;
        $this->invitationUpdater = $invitationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function create(CustomerTransfer $customerTransfer): void
    {
        $companyUserInvitationTransfer = $this->getCompanyUserInvitationTransfer($customerTransfer);

        if (!$this->isValidCompanyUserInvitationStatus($companyUserInvitationTransfer)) {
            return;
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setFkCompany($companyUserInvitationTransfer->getCompanyId())
            ->setFkCompanyBusinessUnit($companyUserInvitationTransfer->getFkCompanyBusinessUnit())
            ->setCustomer($customerTransfer);

        if ($this->companyUserFacade->update($companyUserTransfer)->getIsSuccessful()) {
            $companyUserInvitationUpdateStatusRequestTransfer = $this->getCompanyUserInvitationUpdateStatusRequestTransfer(
                $companyUserInvitationTransfer
            );
            $this->invitationUpdater->updateStatus($companyUserInvitationUpdateStatusRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer
     */
    protected function getCompanyUserInvitationUpdateStatusRequestTransfer(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationUpdateStatusRequestTransfer {
        return (new CompanyUserInvitationUpdateStatusRequestTransfer())
        ->setIdCompanyUser($companyUserInvitationTransfer->getFkCompanyUser())
        ->setCompanyUserInvitation($companyUserInvitationTransfer)
        ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function getCompanyUserInvitationTransfer(CustomerTransfer $customerTransfer): CompanyUserInvitationTransfer
    {
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setHash($customerTransfer->getCompanyUserInvitationHash());

        return $this->repository->getCompanyUserInvitationByHash($companyUserInvitationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return bool
     */
    protected function isValidCompanyUserInvitationStatus(CompanyUserInvitationTransfer $companyUserInvitationTransfer): bool
    {
        return $companyUserInvitationTransfer->getIdCompanyUserInvitation()
            && $companyUserInvitationTransfer->getCompanyUserInvitationStatusKey() === CompanyUserInvitationConstants::INVITATION_STATUS_PENDING;
    }
}
