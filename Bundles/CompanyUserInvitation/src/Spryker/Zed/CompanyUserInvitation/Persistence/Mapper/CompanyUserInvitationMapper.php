<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitation;
use Propel\Runtime\Collection\Collection;

class CompanyUserInvitationMapper implements CompanyUserInvitationMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $companyUserInvitationCollection
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapCompanyUserInvitationCollection(
        Collection $companyUserInvitationCollection
    ): CompanyUserInvitationCollectionTransfer {
        $companyUserInvitations = new ArrayObject();
        $companyUserInvitationCollectionTransfer = new CompanyUserInvitationCollectionTransfer();
        foreach ($companyUserInvitationCollection as $spyCompanyUserInvitation) {
            $companyUserInvitationTransfer = $this->mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer($spyCompanyUserInvitation);
            $companyUserInvitations->append($companyUserInvitationTransfer);
        }
        $companyUserInvitationCollectionTransfer->setCompanyUserInvitations($companyUserInvitations);

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitation $spyCompanyUserInvitation
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer(
        SpyCompanyUserInvitation $spyCompanyUserInvitation
    ): CompanyUserInvitationTransfer {
        $companyUserInvitationTransfer = new CompanyUserInvitationTransfer();
        $companyUserInvitationTransfer->fromArray($spyCompanyUserInvitation->toArray(), true);

        $companyUserInvitationTransfer->setCompanyUserInvitationStatusKey(
            $spyCompanyUserInvitation->getSpyCompanyUserInvitationStatus()->getStatusKey()
        );

        $companyUserInvitationTransfer->setCompanyId(
            $spyCompanyUserInvitation->getSpyCompanyBusinessUnit()->getFkCompany()
        );

        $companyUserInvitationTransfer->setCompanyBusinessUnitName(
            $spyCompanyUserInvitation->getSpyCompanyBusinessUnit()->getName()
        );

        return $companyUserInvitationTransfer;
    }
}
