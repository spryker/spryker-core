<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyUserValidator;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyUserValidator implements CompanyUserValidatorInterface
{
    protected const MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED = 'Customer already attached to this business unit.';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $companyBusinessUnitRepository;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     */
    public function __construct(
        CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
    ) {
        $this->companyBusinessUnitRepository = $companyBusinessUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function isUniqueCompanyUserByCustomer(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())->setIsSuccessful(true);

        if (!$companyUserTransfer->getFkCompanyBusinessUnit() || !$companyUserTransfer->getFkCustomer()) {
            return $companyUserResponseTransfer;
        }

        $existsCompanyUser = $this->companyBusinessUnitRepository
            ->hasCompanyUserByCustomer($companyUserTransfer);

        if (!$existsCompanyUser) {
            return $companyUserResponseTransfer;
        }

        $message = (new ResponseMessageTransfer())
            ->setText(static::MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED);

        return $companyUserResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($message);
    }
}
