<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Reader;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationReader implements InvitationReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    private $repository;

    /**
     * InvitationReader constructor.
     *
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationCollectionTransfer {
        return $this->repository->getCompanyUserInvitationCollection($criteriaFilterTransfer);
    }
}
