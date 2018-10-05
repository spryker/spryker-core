<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter;

use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class InvitationDeleter implements InvitationDeleterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     */
    public function __construct(
        CompanyUserInvitationEntityManagerInterface $entityManager,
        CompanyUserInvitationRepositoryInterface $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer
     */
    public function delete(
        CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
    ): CompanyUserInvitationDeleteResponseTransfer {
        $companyUserInvitationTransfer = $companyUserInvitationDeleteRequestTransfer->getCompanyUserInvitation();
        $companyUserInvitationDeleteResponseTransfer = (new CompanyUserInvitationDeleteResponseTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserInvitationDeleteRequestTransfer->getIdCompanyUser())
                || !$this->repository->findCompanyUserInvitationById($companyUserInvitationTransfer)) {
            return $companyUserInvitationDeleteResponseTransfer;
        }

        $this->entityManager->deleteCompanyUserInvitationById($companyUserInvitationTransfer->getIdCompanyUserInvitation());
        $companyUserInvitationDeleteResponseTransfer->setIsSuccess(true);

        return $companyUserInvitationDeleteResponseTransfer;
    }
}
