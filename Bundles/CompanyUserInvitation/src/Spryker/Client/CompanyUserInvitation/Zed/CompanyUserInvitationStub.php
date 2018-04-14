<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Zed;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class CompanyUserInvitationStub implements CompanyUserInvitationStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importCompanyUserInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportResultTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/import-company-user-invitations',
            $companyUserInvitationCollectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationCollectionTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/get-invitation-collection',
            $criteriaFilterTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationSendResultTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/send-company-user-invitation',
            $companyUserInvitationTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResultTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/send-company-user-invitations',
            $companyUserTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResultTransfer
     */
    public function updateCompanyUserInvitationStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResultTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/update-company-user-invitation-status',
            $companyUserInvitationUpdateStatusRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer|null
     */
    public function findCompanyUserInvitationByHash(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): ?CompanyUserInvitationTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/find-company-user-invitation-by-hash',
            $companyUserInvitationTransfer
        );
    }
}
