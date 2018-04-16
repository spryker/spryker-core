<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Importer;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface;

class InvitationImporter implements InvitationImporterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface
     */
    private $invitationWriter;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface $invitationWriter
     */
    public function __construct(InvitationWriterInterface $invitationWriter)
    {
        $this->invitationWriter = $invitationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importCompanyUserInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportResultTransfer {
        $companyUserInvitationImportResultTransfer = new CompanyUserInvitationImportResultTransfer();
        foreach ($companyUserInvitationCollectionTransfer->getInvitations() as $companyUserInvitationTransfer) {
            $companyUserInvitationCreateResultTransfer = $this->invitationWriter->create($companyUserInvitationTransfer);
            if (!$companyUserInvitationCreateResultTransfer->getSuccess()) {
                $companyUserInvitationImportResultTransfer->addError(
                    $companyUserInvitationCreateResultTransfer->getErrorMessage()
                );
            }
        }

        return $companyUserInvitationImportResultTransfer;
    }
}
