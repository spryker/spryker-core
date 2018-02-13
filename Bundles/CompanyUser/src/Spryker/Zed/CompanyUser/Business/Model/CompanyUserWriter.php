<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business\Model;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface;

class CompanyUserWriter implements CompanyUserWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface
     */
    protected $companyUserWriterRepository;

    /**
     * @var \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    protected $companyUserPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface $companyUserWriterRepository
     * @param \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface $companyUserPluginExecutor
     */
    public function __construct(
        CompanyUserWriterRepositoryInterface $companyUserWriterRepository,
        CompanyUserToCustomerFacadeInterface $customerFacade,
        CompanyUserPluginExecutorInterface $companyUserPluginExecutor
    ) {
        $this->companyUserWriterRepository = $companyUserWriterRepository;
        $this->companyUserPluginExecutor = $companyUserPluginExecutor;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer->requireCustomerTransfer();
        $customerResponseTransfer = $this->registerCustomer($companyUserTransfer->getCustomerTransfer());

        if (!$customerResponseTransfer->getIsSuccess()) {
            $companyUserTransfer->setCustomerTransfer($customerResponseTransfer->getCustomerTransfer());
            $companyUserResponseTransfer = new CompanyUserResponseTransfer();
            $companyUserResponseTransfer->setCompanyUserTransfer($companyUserTransfer);

            return $companyUserResponseTransfer;
        }

        $companyUserTransfer->setFkCustomer($customerResponseTransfer->getCustomerTransfer()->getIdCustomer());

        return $this->save($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function save(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer = $this->companyUserWriterRepository->save($companyUserTransfer);
        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        $companyUserResponseTransfer->setIsSuccessful(true);
        $companyUserResponseTransfer->setCompanyUserTransfer($companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function registerCustomer(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->customerFacade->registerCustomer($customerTransfer);
    }
}
