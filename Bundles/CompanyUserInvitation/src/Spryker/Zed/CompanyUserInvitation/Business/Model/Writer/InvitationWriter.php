<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Writer;

use Exception;
use Generated\Shared\Transfer\CompanyUserInvitationCreateResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;

class InvitationWriter implements InvitationWriterInterface
{
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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCreateResultTransfer
     */
    public function create(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationCreateResultTransfer {
        $companyUserInvitationCreateResultTransfer = (new CompanyUserInvitationCreateResultTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer);

        if (!$this->invitationValidator->isValidInvitation($companyUserInvitationTransfer)) {
            $companyUserInvitationCreateResultTransfer
                ->setSuccess(false)
                ->setErrorMessage($this->invitationValidator->getLastErrorMessage());

            return $companyUserInvitationCreateResultTransfer;
        }

        $invitationTransfer = $this->invitationHydrator->hydrate($companyUserInvitationTransfer);
        $companyUserInvitationTransfer = $this->entityManager->saveCompanyUserInvitation($invitationTransfer);
        $companyUserInvitationCreateResultTransfer
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setSuccess(true);

        return $companyUserInvitationCreateResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationDeleteResultTransfer
     */
    public function delete(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationDeleteResultTransfer {
        $companyUserInvitationDeleteResultTransfer = (new CompanyUserInvitationDeleteResultTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer);

        try {
            $this->entityManager->deleteCompanyUserInvitationById(
                $companyUserInvitationTransfer->getIdCompanyUserInvitation()
            );
            $companyUserInvitationDeleteResultTransfer->setSuccess(true);
        } catch (Exception $e) {
            $companyUserInvitationDeleteResultTransfer->setSuccess(false);
        }

        return $companyUserInvitationDeleteResultTransfer;
    }
}
