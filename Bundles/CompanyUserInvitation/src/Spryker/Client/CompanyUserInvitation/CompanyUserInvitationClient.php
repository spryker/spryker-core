<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation;

use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyUserInvitation\CompanyUserInvitationFactory getFactory()
 */
class CompanyUserInvitationClient extends AbstractClient implements CompanyUserInvitationClientInterface
{
    /**
     * @api
     *
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(string $filePath): CompanyUserInvitationImportResultTransfer
    {
        return $this->getFactory()
            ->createZedCompanyUserInvitationStub($filePath)
            ->importInvitations();
    }
}
