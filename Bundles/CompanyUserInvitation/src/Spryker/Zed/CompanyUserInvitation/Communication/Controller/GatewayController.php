<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer
     */
    public function importInvitationsAction(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportReportTransfer {
        return $this->getFacade()->importInvitations($companyUserInvitationCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getInvitationCollectionAction(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationCollectionTransfer {
        return $this->getFacade()->getCompanyUserInvitationCollection($criteriaFilterTransfer);
    }
}
