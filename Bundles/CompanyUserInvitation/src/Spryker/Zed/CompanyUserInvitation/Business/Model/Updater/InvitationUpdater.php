<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Updater;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationUpdater implements InvitationUpdaterInterface
{
    /**
     * @var array
     */
    protected $invitationStatusCache = [];

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    private $repository;

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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     * @param string $status
     *
     * @return void
     */
    public function updateStatus(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer,
        string $status
    ): void {
        foreach ($companyUserInvitationCollectionTransfer->getInvitations() as $companyUserInvitationTransfer) {
            $companyUserInvitationTransfer->setFkCompanyUserInvitationStatus(
                $this->getIdCompanyUserInvitationStatus($status)
            );
            $this->entityManager->saveCompanyUserInvitation($companyUserInvitationTransfer);
        }
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
}
