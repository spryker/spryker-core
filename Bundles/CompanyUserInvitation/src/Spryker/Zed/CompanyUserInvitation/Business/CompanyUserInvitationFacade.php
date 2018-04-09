<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationBusinessFactory getFactory()
 */
class CompanyUserInvitationFacade extends AbstractFacade implements CompanyUserInvitationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer): CompanyUserInvitationImportResultTransfer
    {
        return $this->getFactory()->createInvitationImporter()->importInvitations($companyUserInvitationImportRequestTransfer);
    }
}
