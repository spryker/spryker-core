<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Updater;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class InvitationUpdater implements InvitationUpdaterInterface
{
    use PermissionAwareTrait;

    /**
     * @var array
     */
    protected $invitationStatusCache = [];

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface $entityManager
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer
     */
    public function updateStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResponseTransfer {
        $companyUserInvitationTransfer = $companyUserInvitationUpdateStatusRequestTransfer->getCompanyUserInvitation();
        $companyUserInvitationUpdateStatusResponseTransfer = (new CompanyUserInvitationUpdateStatusResponseTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserInvitationUpdateStatusRequestTransfer->getIdCompanyUser())
            || !$this->repository->findCompanyUserInvitationById($companyUserInvitationTransfer)) {
            return $companyUserInvitationUpdateStatusResponseTransfer;
        }

        $companyUserInvitationTransfer->setFkCompanyUserInvitationStatus(
            $this->getIdCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer->getStatusKey())
        );

        if ($companyUserInvitationUpdateStatusRequestTransfer->getStatusKey() === CompanyUserInvitationConfig::INVITATION_STATUS_DELETED) {
            $this->anonymize($companyUserInvitationTransfer);
        }

        $companyUserInvitationTransfer = $this->entityManager->saveCompanyUserInvitation($companyUserInvitationTransfer);
        $companyUserInvitationUpdateStatusResponseTransfer
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIsSuccess(true);

        return $companyUserInvitationUpdateStatusResponseTransfer;
    }

    /**
     * @param string $statusKey
     *
     * @return int
     */
    protected function getIdCompanyUserInvitationStatus(string $statusKey): int
    {
        if (!isset($this->invitationStatusCache[$statusKey])) {
            $companyUserInvitationStatusTransfer = $this->repository->findCompanyUserInvitationStatusByStatusKey($statusKey);
            $this->invitationStatusCache[$companyUserInvitationStatusTransfer->getStatusKey()]
                = $companyUserInvitationStatusTransfer->getIdCompanyUserInvitationStatus();
        }

        return $this->invitationStatusCache[$statusKey];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return void
     */
    protected function anonymize(CompanyUserInvitationTransfer $companyUserInvitationTransfer): void
    {
        $companyUserInvitationTransfer->setEmail($this->generateRandomEmail());
    }

    /**
     * @return string
     */
    protected function generateRandomEmail()
    {
        return sprintf(
            '%s@%s.%s',
            strtolower(md5(mt_rand())),
            strtolower(md5(mt_rand())),
            strtolower(md5(mt_rand()))
        );
    }
}
