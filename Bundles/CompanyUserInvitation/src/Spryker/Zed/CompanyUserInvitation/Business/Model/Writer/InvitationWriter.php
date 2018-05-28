<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Writer;

use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class InvitationWriter implements InvitationWriterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface
     */
    protected $invitationValidator;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface
     */
    protected $invitationHydrator;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface $invitationValidator
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface $invitationHydrator
     */
    public function __construct(
        CompanyUserInvitationEntityManagerInterface $entityManager,
        InvitationValidatorInterface $invitationValidator,
        InvitationHydratorInterface $invitationHydrator
    ) {
        $this->entityManager = $entityManager;
        $this->invitationValidator = $invitationValidator;
        $this->invitationHydrator = $invitationHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer
     */
    public function create(
        CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
    ): CompanyUserInvitationCreateResponseTransfer {
        $companyUserInvitationTransfer = $companyUserInvitationCreateRequestTransfer->getCompanyUserInvitation();
        $companyUserInvitationCreateResponseTransfer = (new CompanyUserInvitationCreateResponseTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserInvitationCreateRequestTransfer->getIdCompanyUser())) {
            return $companyUserInvitationCreateResponseTransfer;
        }

        if (!$this->invitationValidator->isValidInvitation($companyUserInvitationTransfer)) {
            $companyUserInvitationCreateResponseTransfer->setError($this->invitationValidator->getLastErrorMessage());

            return $companyUserInvitationCreateResponseTransfer;
        }

        $invitationTransfer = $this->invitationHydrator->hydrate($companyUserInvitationTransfer);
        $companyUserInvitationTransfer = $this->entityManager->saveCompanyUserInvitation($invitationTransfer);
        $companyUserInvitationCreateResponseTransfer
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIsSuccess(true);

        return $companyUserInvitationCreateResponseTransfer;
    }
}
