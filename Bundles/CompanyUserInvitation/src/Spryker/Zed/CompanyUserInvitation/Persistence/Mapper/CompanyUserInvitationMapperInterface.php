<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer;

interface CompanyUserInvitationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer
     */
    public function mapCompanyUserInvitationTransferToEntityTransfer(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): SpyCompanyUserInvitationEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer $companyUserInvitationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function mapEntityTransferToCompanyUserInvitationTransfer(
        SpyCompanyUserInvitationEntityTransfer $companyUserInvitationEntityTransfer
    ): CompanyUserInvitationTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserInvitationEntityTransfer[] $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapCompanyUserInvitationCollection(array $collection): CompanyUserInvitationCollectionTransfer;
}
