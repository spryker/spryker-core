<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationHydrator implements InvitationHydratorInterface
{
    const STATUS_DEFAULT = 1;

    /**
     * @var array
     */
    private $businessUnitCache;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    private $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface
     */
    private $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface
     */
    private $utilTextService;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface $utilTextService
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyUserInvitationToUtilTextInterface $utilTextService
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->repository = $repository;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function hydrate(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer
    {
        $invitationTransfer = $this->setHash($invitationTransfer);
        $invitationTransfer = $this->setFkCompanyBusinessUnit($invitationTransfer);
        $invitationTransfer = $this->setFkCompanyUserInvitationStatus($invitationTransfer);
        $invitationTransfer = $this->setFkCompanyUser($invitationTransfer);

        return $invitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function setFkCompanyBusinessUnit(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer
    {
        if (!$this->businessUnitCache) {
            $this->populateBusinessUnitCache($invitationTransfer);
        }

        $invitationTransfer->setFkCompanyBusinessUnit(
            $this->businessUnitCache[$invitationTransfer->getCompanyBusinessUnit()->getName()]
        );

        return $invitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function setFkCompanyUserInvitationStatus(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer
    {
        $invitationTransfer->setFkCompanyUserInvitationStatus(static::STATUS_DEFAULT);

        return $invitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function setHash(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer
    {
        $salt = sprintf('%s.%s', $invitationTransfer->getEmail(), microtime(true));
        $hash = $this->utilTextService->hashValue($salt, Hash::SHA256);
        $invitationTransfer->setHash($hash);

        return $invitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function setFkCompanyUser(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer
    {
        $invitationTransfer->setFkCompanyUser(
            $invitationTransfer->getCompanyUser()->getIdCompanyUser()
        );

        return $invitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return void
     */
    protected function populateBusinessUnitCache(CompanyUserInvitationTransfer $invitationTransfer)
    {
        $companyBusinessUnitCriteriaFilter = new CompanyBusinessUnitCriteriaFilterTransfer();
        $companyBusinessUnitCriteriaFilter->setIdCompany($invitationTransfer->getCompanyUser()->getFkCompany());
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            $companyBusinessUnitCriteriaFilter
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $this->businessUnitCache[$companyBusinessUnitTransfer->getName()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }
    }
}
