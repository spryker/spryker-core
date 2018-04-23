<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Importer;

use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class InvitationImporter implements InvitationImporterInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface
     */
    protected $invitationWriter;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface $invitationWriter
     */
    public function __construct(InvitationWriterInterface $invitationWriter)
    {
        $this->invitationWriter = $invitationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer
     */
    public function importCompanyUserInvitations(
        CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
    ): CompanyUserInvitationImportResponseTransfer {
        $companyUserInvitationImportResponseTransfer = (new CompanyUserInvitationImportResponseTransfer())->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserInvitationImportRequestTransfer->getIdCompanyUser())) {
            return $companyUserInvitationImportResponseTransfer;
        }

        $companyUserInvitationCollection = $companyUserInvitationImportRequestTransfer->getCompanyUserInvitationCollection();

        $invitationCounter = 1;
        foreach ($companyUserInvitationCollection->getCompanyUserInvitations() as $companyUserInvitationTransfer) {
            $companyUserInvitationCreateRequestTransfer = (new CompanyUserInvitationCreateRequestTransfer())
                ->setIdCompanyUser($companyUserInvitationImportRequestTransfer->getIdCompanyUser())
                ->setCompanyUserInvitation($companyUserInvitationTransfer);

            $companyUserInvitationCreateResponseTransfer = $this->invitationWriter->create($companyUserInvitationCreateRequestTransfer);
            if (!$companyUserInvitationCreateResponseTransfer->getIsSuccess()) {
                $companyUserInvitationImportResponseTransfer->addError(
                    sprintf(
                        'Invitation %s : %s',
                        $invitationCounter,
                        $companyUserInvitationCreateResponseTransfer->getError()
                    )
                );
            }
            $invitationCounter++;
        }
        $companyUserInvitationImportResponseTransfer->setIsSuccess(true);

        return $companyUserInvitationImportResponseTransfer;
    }
}
