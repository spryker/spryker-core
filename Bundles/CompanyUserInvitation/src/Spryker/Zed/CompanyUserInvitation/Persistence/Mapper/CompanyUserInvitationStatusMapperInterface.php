<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\SpyCompanyUserInvitationStatusEntityTransfer;

interface CompanyUserInvitationStatusMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserInvitationStatusEntityTransfer
     */
    public function mapCompanyUserInvitationStatusTransferToEntityTransfer(
        CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
    ): SpyCompanyUserInvitationStatusEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationStatusEntityTransfer $companyUserInvitationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer
     */
    public function mapEntityTransferToCompanyUserInvitationStatusTransfer(
        SpyCompanyUserInvitationStatusEntityTransfer $companyUserInvitationEntityTransfer
    ): CompanyUserInvitationStatusTransfer;
}
