<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business\Model;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepositoryInterface;

class CompanyRoleWriter implements CompanyRoleWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepositoryInterface
     */
    protected $companyRoleWriterRepository;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleWriterRepositoryInterface $companyRoleWriterRepository
     */
    public function __construct(CompanyRoleWriterRepositoryInterface $companyRoleWriterRepository)
    {
        $this->companyRoleWriterRepository = $companyRoleWriterRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->save($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function save(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $companyRoleTransfer = $this->companyRoleWriterRepository->save($companyRoleTransfer);
        $companyRoleResponseTransfer = new CompanyRoleResponseTransfer();
        $companyRoleResponseTransfer->setCompanyRoleTransfer($companyRoleTransfer);

        return $companyRoleResponseTransfer;
    }
}
