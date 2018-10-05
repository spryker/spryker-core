<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CompanyUserStatusHandler implements CompanyUserStatusHandlerInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface
     */
    protected $companyUserEntityManager;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface $companyUserEntityManager
     */
    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserEntityManagerInterface $companyUserEntityManager
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserEntityManager = $companyUserEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function enableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();

        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);

        $existingCompanyUser = $this->companyUserRepository->findCompanyUserByIdCompanyUser($companyUserTransfer);

        if (!$existingCompanyUser || $existingCompanyUser->getIsActive()) {
            return $companyUserResponseTransfer;
        }

        $existingCompanyUser->setIsActive(true);
        $this->companyUserEntityManager->updateCompanyUserStatus($existingCompanyUser);

        return $companyUserResponseTransfer
            ->setCompanyUser($existingCompanyUser)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function disableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();

        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(false);

        $existingCompanyUser = $this->companyUserRepository->findCompanyUserByIdCompanyUser($companyUserTransfer);

        if (!$existingCompanyUser || !$existingCompanyUser->getIsActive()) {
            return $companyUserResponseTransfer;
        }

        $existingCompanyUser->setIsActive(false);
        $this->companyUserEntityManager->updateCompanyUserStatus($existingCompanyUser);

        return $companyUserResponseTransfer
            ->setCompanyUser($existingCompanyUser)
            ->setIsSuccessful(true);
    }
}
