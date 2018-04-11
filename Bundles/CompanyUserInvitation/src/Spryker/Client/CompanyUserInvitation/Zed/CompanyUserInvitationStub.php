<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Zed;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer;
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
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer
     */
    public function importInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportReportTransfer {
        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/import-invitations',
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
}
