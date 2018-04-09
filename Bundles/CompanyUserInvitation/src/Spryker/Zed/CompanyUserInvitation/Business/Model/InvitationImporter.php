<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model;

use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface;

class InvitationImporter implements InvitationImporterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    private $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\InvitationValidatorInterface
     */
    private $invitationValidator;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\InvitationHydratorInterface
     */
    private $invitationHydrator;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\InvitationValidatorInterface $invitationValidator
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\InvitationHydratorInterface $invitationHydrator
     */
    public function __construct(
        CompanyUserInvitationEntityManagerInterface $entityManager,
        CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade,
        InvitationValidatorInterface $invitationValidator,
        InvitationHydratorInterface $invitationHydrator
    ) {
        $this->entityManager = $entityManager;
        $this->companyUserFacade = $companyUserFacade;
        $this->invitationValidator = $invitationValidator;
        $this->invitationHydrator = $invitationHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $importRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(CompanyUserInvitationImportRequestTransfer $importRequestTransfer): CompanyUserInvitationImportResultTransfer
    {
        $importResultTransfer = new CompanyUserInvitationImportResultTransfer;
        $companyUserTransfer = $this->getCompanyUser($importRequestTransfer->getCustomer());

        foreach ($importRequestTransfer->getInvitationCollection()->getInvitations() as $invitationTransfer) {
            $invitationTransfer->setCompanyUser($companyUserTransfer);
            if ($this->invitationValidator->isValidInvitation($invitationTransfer)) {
                $invitationTransfer = $this->invitationHydrator->hydrate($invitationTransfer);
                $invitationTransfer = $this->entityManager->saveCompanyUserInvitation($invitationTransfer);
                continue;
            }

            $invitationTransfer->setImportError($this->invitationValidator->getValidationError());
            $importResultTransfer->addFailedInvitation($invitationTransfer);
        }

        return $importResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return null|\Spryker\Zed\CompanyUser\Business\Model\CompanyUser
     */
    protected function getCompanyUser(CustomerTransfer $customer): ?CompanyUserTransfer
    {
        return $this->companyUserFacade->findCompanyUserByCustomerId($customer);
    }
}
