<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Zed;

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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer
     */
    public function importCompanyUserInvitations(
        CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
    ): CompanyUserInvitationImportResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer $companyUserInvitationImportResponseTransfer */
        $companyUserInvitationImportResponseTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/import-company-user-invitations',
            $companyUserInvitationImportRequestTransfer
        );

        return $companyUserInvitationImportResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
    ): CompanyUserInvitationCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer */
        $companyUserInvitationCollectionTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/get-company-user-invitation-collection',
            $companyUserInvitationGetCollectionRequestTransfer
        );

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
    ): CompanyUserInvitationSendResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer $companyUserInvitationSendResponseTransfer */
        $companyUserInvitationSendResponseTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/send-company-user-invitation',
            $companyUserInvitationSendRequestTransfer
        );

        return $companyUserInvitationSendResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer $companyUserInvitationSendBatchResponseTransfer */
        $companyUserInvitationSendBatchResponseTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/send-company-user-invitations',
            $companyUserTransfer
        );

        return $companyUserInvitationSendBatchResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer
     */
    public function updateCompanyUserInvitationStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResponseTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer $companyUserInvitationUpdateStatusResponseTransfer */
        $companyUserInvitationUpdateStatusResponseTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/update-company-user-invitation-status',
            $companyUserInvitationUpdateStatusRequestTransfer
        );

        return $companyUserInvitationUpdateStatusResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function getCompanyUserInvitationByHash(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer */
        $companyUserInvitationTransfer = $this->zedRequestClient->call(
            '/company-user-invitation/gateway/get-company-user-invitation-by-hash',
            $companyUserInvitationTransfer
        );

        return $companyUserInvitationTransfer;
    }
}
