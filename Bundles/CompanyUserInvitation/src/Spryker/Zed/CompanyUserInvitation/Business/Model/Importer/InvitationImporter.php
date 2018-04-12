<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Importer;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;

class InvitationImporter implements InvitationImporterInterface
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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer
     */
    public function importInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportReportTransfer {
        $importReportTransfer = new CompanyUserInvitationImportReportTransfer();
        foreach ($companyUserInvitationCollectionTransfer->getInvitations() as $invitationTransfer) {
            if ($this->invitationValidator->isValidInvitation($invitationTransfer)) {
                $invitationTransfer = $this->invitationHydrator->hydrate($invitationTransfer);
                $this->entityManager->saveCompanyUserInvitation($invitationTransfer);
                continue;
            }
            $invitationTransfer->setImportError($this->invitationValidator->getLastErrorMessage());
            $importReportTransfer->addNotImportedInvitation($invitationTransfer);
        }

        return $importReportTransfer;
    }
}
