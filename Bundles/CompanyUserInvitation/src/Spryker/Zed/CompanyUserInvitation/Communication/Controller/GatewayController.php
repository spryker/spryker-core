<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationBusinessFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importCompanyUserInvitationsAction(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportResultTransfer {
        return $this->getFacade()->importCompanyUserInvitations($companyUserInvitationCollectionTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer
     */
    public function sendCompanyUserInvitationAction(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationSendResultTransfer {
        return $this->getFacade()->sendCompanyUserInvitation($companyUserInvitationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer
     */
    public function sendCompanyUserInvitationsAction(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResultTransfer {
        return $this->getFacade()->sendCompanyUserInvitations($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer
     */
    public function updateCompanyUserInvitationStatusAction(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResultTransfer {
        return $this->getFacade()->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer|null
     */
    public function findCompanyUserInvitationByHashAction(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): ?CompanyUserInvitationTransfer {
        return $this->getFacade()->findCompanyUserInvitationByHash($companyUserInvitationTransfer);
    }
}
