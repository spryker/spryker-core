<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface;
use Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface;

class InvitationHydrator implements InvitationHydratorInterface
{
    /**
     * @var array
     */
    protected $businessUnitCache = [];

    /**
     * @var \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer
     */
    protected $companyUserInvitationStatusTransfer;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface $utilTextService
     */
    public function __construct(
        CompanyUserInvitationRepositoryInterface $repository,
        CompanyUserInvitationToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserInvitationToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyUserInvitationToUtilTextInterface $utilTextService
    ) {
        $this->repository = $repository;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->utilTextService = $utilTextService;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function hydrate(CompanyUserInvitationTransfer $companyUserInvitationTransfer): CompanyUserInvitationTransfer
    {
        $companyUserInvitationTransfer->setHash($this->generateHash($companyUserInvitationTransfer));
        $companyUserInvitationTransfer->setFkCompanyBusinessUnit($this->getIdCompanyBusinessUnit($companyUserInvitationTransfer));
        $companyUserInvitationTransfer->setFkCompanyUserInvitationStatus($this->getIdCompanyUserInvitationStatus());

        return $companyUserInvitationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return string
     */
    protected function generateHash(CompanyUserInvitationTransfer $invitationTransfer): string
    {
        return $this->utilTextService->hashValue(
            sprintf('%s.%s', $invitationTransfer->getEmail(), microtime(true)),
            Hash::SHA256
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return int
     */
    protected function getIdCompanyBusinessUnit(CompanyUserInvitationTransfer $invitationTransfer): int
    {
        if (!$this->businessUnitCache) {
            $this->populateBusinessUnitCache($invitationTransfer);
        }

        return $this->businessUnitCache[$invitationTransfer->getCompanyBusinessUnitName()];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return void
     */
    protected function populateBusinessUnitCache(CompanyUserInvitationTransfer $invitationTransfer)
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($invitationTransfer->getFkCompanyUser());

        $companyBusinessUnitCriteriaFilter = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($companyUserTransfer->getFkCompany());

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            $companyBusinessUnitCriteriaFilter
        );

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $this->businessUnitCache[$companyBusinessUnitTransfer->getName()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }
    }

    /**
     * @return int
     */
    protected function getIdCompanyUserInvitationStatus(): int
    {
        if (!$this->companyUserInvitationStatusTransfer) {
            $this->companyUserInvitationStatusTransfer = $this->repository->findCompanyUserInvitationStatusByStatusKey(
                CompanyUserInvitationConfig::INVITATION_STATUS_NEW
            );
        }

        return $this->companyUserInvitationStatusTransfer->getIdCompanyUserInvitationStatus();
    }
}
