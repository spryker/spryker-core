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
        $data = $companyUserInvitationTransfer->toArray();
        unset($data['company_user'], $data['company_business_unit'], $data['company_user_invitation_status']);
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
        $companyUserTransfer = new CompanyUserInvitationTransfer();
        $companyUserTransfer->fromArray($companyUserInvitationEntityTransfer->toArray(), true);

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer[] $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapCompanyUserInvitationCollection($collection): CompanyUserInvitationCollectionTransfer
    {
        $companyUserInvitations = new ArrayObject();
        $companyUserInvitationCollectionTransfer = new CompanyUserInvitationCollectionTransfer();

        foreach ($collection as $spyCompanyUserInvitationEntityTransfer) {
            $companyUserInvitations->append(
                $this->mapEntityTransferToCompanyUserInvitationTransfer($spyCompanyUserInvitationEntityTransfer)
            );
        }

        $companyUserInvitationCollectionTransfer->setInvitations($companyUserInvitations);

        return $companyUserInvitationCollectionTransfer;
    }
}
