<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUserInvitationStatusEntityTransfer;

class CompanyUserInvitationMapper implements CompanyUserInvitationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer
     */
    public function mapCompanyUserInvitationTransferToEntityTransfer(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): SpyCompanyUserInvitationEntityTransfer {
        $companyUserInvitationEntityTransfer = new SpyCompanyUserInvitationEntityTransfer();
        $companyUserInvitationEntityTransfer->fromArray($companyUserInvitationTransfer->toArray(), true);

        return $companyUserInvitationEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer $companyUserInvitationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function mapEntityTransferToCompanyUserInvitationTransfer(
        SpyCompanyUserInvitationEntityTransfer $companyUserInvitationEntityTransfer
    ): CompanyUserInvitationTransfer {
        $companyUserInvitationEntityTransferData = $companyUserInvitationEntityTransfer->modifiedToArray(true, true);

        $companyUserInvitationTransfer = new CompanyUserInvitationTransfer();
        $companyUserInvitationTransfer->fromArray($companyUserInvitationEntityTransferData, true);

        if (isset($companyUserInvitationEntityTransferData[SpyCompanyUserInvitationEntityTransfer::SPY_COMPANY_BUSINESS_UNIT])) {
            $companyUserInvitationTransfer->setCompanyBusinessUnitName(
                $companyUserInvitationEntityTransferData[SpyCompanyUserInvitationEntityTransfer::SPY_COMPANY_BUSINESS_UNIT][SpyCompanyBusinessUnitEntityTransfer::NAME]
            );
            $companyUserInvitationTransfer->setCompanyId(
                $companyUserInvitationEntityTransferData[SpyCompanyUserInvitationEntityTransfer::SPY_COMPANY_BUSINESS_UNIT][SpyCompanyBusinessUnitEntityTransfer::FK_COMPANY]
            );
        }

        if (isset($companyUserInvitationEntityTransferData[SpyCompanyUserInvitationEntityTransfer::SPY_COMPANY_USER_INVITATION_STATUS])) {
            $companyUserInvitationTransfer->setCompanyUserInvitationStatusKey(
                $companyUserInvitationEntityTransferData[SpyCompanyUserInvitationEntityTransfer::SPY_COMPANY_USER_INVITATION_STATUS][SpyCompanyUserInvitationStatusEntityTransfer::STATUS_KEY]
            );
        }

        return $companyUserInvitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer[] $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapCompanyUserInvitationCollection(array $collection): CompanyUserInvitationCollectionTransfer
    {
        $companyUserInvitations = new ArrayObject();
        $companyUserInvitationCollectionTransfer = new CompanyUserInvitationCollectionTransfer();

        foreach ($collection as $spyCompanyUserInvitationEntityTransfer) {
            $companyUserInvitations->append(
                $this->mapEntityTransferToCompanyUserInvitationTransfer($spyCompanyUserInvitationEntityTransfer)
            );
        }
        $companyUserInvitationCollectionTransfer->setCompanyUserInvitations($companyUserInvitations);

        return $companyUserInvitationCollectionTransfer;
    }
}
