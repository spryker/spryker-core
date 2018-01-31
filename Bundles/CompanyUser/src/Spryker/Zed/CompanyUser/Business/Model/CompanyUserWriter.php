<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface;

class CompanyUserWriter implements CompanyUserWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface
     */
    protected $companyUserWriterRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    protected $companyUserPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface $companyUserWriterRepository
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface $companyUserPluginExecutor
     */
    public function __construct(
        CompanyUserWriterRepositoryInterface $companyUserWriterRepository,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor
    ) {
        $this->companyUserWriterRepository = $companyUserWriterRepository;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->save($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $this->companyUserPluginExecutor->executeCompanyUserSavePlugins($companyUserTransfer);

        $companyUserTransfer = $this->companyUserWriterRepository->save($companyUserTransfer);
        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

        return $companyUserResponseTransfer;
    }
}
