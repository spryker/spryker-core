<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Updater;

use Exception;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer;
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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer
     */
    public function updateStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResultTransfer {
        $companyUserInvitationUpdateStatusResultTransfer = new CompanyUserInvitationUpdateStatusResultTransfer();
        try {
            $idCompanyUserInvitationStatus = $this->getIdCompanyUserInvitationStatus(
                $companyUserInvitationUpdateStatusRequestTransfer->getStatusKey()
            );
            $companyUserInvitationTransfer = $companyUserInvitationUpdateStatusRequestTransfer->getCompanyUserInvitation();
            $companyUserInvitationTransfer->setFkCompanyUserInvitationStatus($idCompanyUserInvitationStatus);

            $this->entityManager->saveCompanyUserInvitation($companyUserInvitationTransfer);
            $companyUserInvitationUpdateStatusResultTransfer->setSuccess(true);
        } catch (Exception $e) {
            $companyUserInvitationUpdateStatusResultTransfer->setSuccess(false);
        }

        return $companyUserInvitationUpdateStatusResultTransfer;
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
