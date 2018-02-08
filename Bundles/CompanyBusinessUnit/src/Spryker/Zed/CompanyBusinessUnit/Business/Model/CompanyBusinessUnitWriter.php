<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface;

class CompanyBusinessUnitWriter implements CompanyBusinessUnitWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface
     */
    protected $companyBusinessUnitWriterRepository;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $companyBusinessUnitRepository;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface $companyBusinessUnitWriterRepository
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     */
    public function __construct(
        CompanyBusinessUnitWriterRepositoryInterface $companyBusinessUnitWriterRepository,
        CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
    ) {
        $this->companyBusinessUnitWriterRepository = $companyBusinessUnitWriterRepository;
        $this->companyBusinessUnitRepository = $companyBusinessUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function create(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        return $this->save($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function update(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        return $this->save($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    public function delete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        $isCompanyBusinessUnitHasUsers = $this->isCompanyBusinessUnitHasUsers($companyBusinessUnitTransfer);

        if ($isCompanyBusinessUnitHasUsers) {
            return false;
        }

        $this->companyBusinessUnitWriterRepository->delete($companyBusinessUnitTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function save(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = $this->companyBusinessUnitWriterRepository->save($companyBusinessUnitTransfer);
        $companyBusinessUnitResponseTransfer = new CompanyBusinessUnitResponseTransfer();
        $companyBusinessUnitResponseTransfer->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);
        $companyBusinessUnitResponseTransfer->setIsSuccessful(true);

        return $companyBusinessUnitResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function isCompanyBusinessUnitHasUsers(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        return $this->companyBusinessUnitRepository->isCompanyBusinessUnitHasUsers($companyBusinessUnitTransfer);
    }
}
