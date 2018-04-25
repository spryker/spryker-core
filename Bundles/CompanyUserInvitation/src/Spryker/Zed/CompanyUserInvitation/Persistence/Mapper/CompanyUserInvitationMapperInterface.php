<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitation;
use Propel\Runtime\Collection\Collection;

interface CompanyUserInvitationMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $companyUserInvitationCollection
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapCompanyUserInvitationCollection(
        Collection $companyUserInvitationCollection
    ): CompanyUserInvitationCollectionTransfer;

    /**
     * @param \Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitation $spyCompanyUserInvitation
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer(
        SpyCompanyUserInvitation $spyCompanyUserInvitation
    ): CompanyUserInvitationTransfer;
}
