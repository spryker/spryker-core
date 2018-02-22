<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    protected $companyUserRepository;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface $companyUserRepository
     */
    public function __construct(CompanyUserRepositoryInterface $companyUserRepository)
    {
        $this->companyUserRepository = $companyUserRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        return $this->companyUserRepository->findCompanyUserByCustomerId($customerTransfer);
    }
}
