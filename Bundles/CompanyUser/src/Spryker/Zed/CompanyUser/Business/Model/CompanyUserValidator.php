<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CompanyUserValidator implements CompanyUserValidatorInterface
{
    protected const MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED = 'Customer already attached to this business unit.';

    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     */
    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository
    ) {
        $this->companyUserRepository = $companyUserRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function checkIfCompanyUserUnique(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setIsSuccessful(true);

        if (!$this->companyUserRepository->isCompanyUserExists($companyUserTransfer)) {
            return $companyUserResponseTransfer;
        }

        $message = (new ResponseMessageTransfer())
            ->setText(static::MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED);

        return $companyUserResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($message);
    }
}
