<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business\Reader;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;

class CompanyRoleReader implements CompanyRoleReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $companyRoleRepository;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $companyRoleRepository
     */
    public function __construct(CompanyRoleRepositoryInterface $companyRoleRepository)
    {
        $this->companyRoleRepository = $companyRoleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function findCompanyRoleByUuid(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $companyRoleTransfer->requireUuid();

        $companyRoleTransfer = $this->companyRoleRepository->findCompanyRoleByUuid(
            $companyRoleTransfer->getUuid()
        );

        $companyRoleResponseTransfer = new CompanyRoleResponseTransfer();
        if (!$companyRoleTransfer) {
            return $companyRoleResponseTransfer->setIsSuccessful(false);
        }

        return $companyRoleResponseTransfer
            ->setIsSuccessful(true)
            ->setCompanyRoleTransfer($companyRoleTransfer);
    }
}
