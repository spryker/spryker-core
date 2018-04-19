<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer;

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
        $companyUserInvitationEntityTransferData = $companyUserInvitationEntityTransfer->modifiedToArray();

        $companyUserInvitationTransfer = new CompanyUserInvitationTransfer();
        $companyUserInvitationTransfer->fromArray($companyUserInvitationEntityTransferData, true);

        if (isset($companyUserInvitationEntityTransferData['spy_company_business_unit'])) {
            $companyUserInvitationTransfer->setCompanyBusinessUnitName(
                $companyUserInvitationEntityTransferData['spy_company_business_unit']['name']
            );
            $companyUserInvitationTransfer->setCompanyId(
                $companyUserInvitationEntityTransferData['spy_company_business_unit']['fk_company']
            );
        }

        if (isset($companyUserInvitationEntityTransferData['spy_company_user_invitation_status'])) {
            $companyUserInvitationTransfer->setCompanyUserInvitationStatusKey(
                $companyUserInvitationEntityTransferData['spy_company_user_invitation_status']['status_key']
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
