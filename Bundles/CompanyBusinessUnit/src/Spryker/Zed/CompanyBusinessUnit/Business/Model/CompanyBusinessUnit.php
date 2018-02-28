<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyBusinessUnit implements CompanyBusinessUnitInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface $companyBusinessUnitEntityManager
     */
    public function __construct(
        CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository,
        CompanyBusinessUnitEntityManagerInterface $companyBusinessUnitEntityManager
    ) {
        $this->repository = $companyBusinessUnitRepository;
        $this->entityManager = $companyBusinessUnitEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function create(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitTransfer) {
            return $this->executeBusinessUnitSaveTransaction($companyBusinessUnitTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function update(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitTransfer) {
            return $this->executeBusinessUnitSaveTransaction($companyBusinessUnitTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function delete(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitTransfer) {
            $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();
            $hasUsers = $this->repository->hasUsers($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
            if ($hasUsers) {
                return (new CompanyBusinessUnitResponseTransfer())->setIsSuccessful(false);
            }

            $this->entityManager->deleteCompanyBusinessUnitById(
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            );

            return (new CompanyBusinessUnitResponseTransfer())->setIsSuccessful(true);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function executeBusinessUnitSaveTransaction(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {

        $companyBusinessUnitTransfer = $this->entityManager->saveCompanyBusinessUnit($companyBusinessUnitTransfer);

        return (new CompanyBusinessUnitResponseTransfer())->setIsSuccessful(true)
            ->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);
    }
}
