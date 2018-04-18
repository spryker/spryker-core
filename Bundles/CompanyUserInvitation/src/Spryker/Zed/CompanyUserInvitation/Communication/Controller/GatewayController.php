<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationBusinessFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer
     */
    public function importCompanyUserInvitationsAction(
        CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
    ): CompanyUserInvitationImportResponseTransfer {
        return $this->getFacade()->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getInvitationCollectionAction(
        CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
    ): CompanyUserInvitationCollectionTransfer {
        return $this->getFacade()->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer
     */
    public function sendCompanyUserInvitationAction(
        CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
    ): CompanyUserInvitationSendResponseTransfer {
        return $this->getFacade()->sendCompanyUserInvitation($companyUserInvitationSendRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer
     */
    public function sendCompanyUserInvitationsAction(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResponseTransfer {
        return $this->getFacade()->sendCompanyUserInvitations($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer
     */
    public function updateCompanyUserInvitationStatusAction(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResponseTransfer {
        return $this->getFacade()->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function getCompanyUserInvitationByHashAction(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationTransfer {
        return $this->getFacade()->getCompanyUserInvitationByHash($companyUserInvitationTransfer);
    }
}
