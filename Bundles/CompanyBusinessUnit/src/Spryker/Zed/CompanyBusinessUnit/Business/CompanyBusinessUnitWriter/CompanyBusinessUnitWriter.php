<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitWriterPluginExecutorInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyBusinessUnitWriter implements CompanyBusinessUnitWriterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_HAS_RELATED_USERS = 'company.company_business_unit.delete.error.has_users';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitWriterPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @var int[]
     */
    protected static $companyBUIdStack = [];

    /**
     * @var int
     */
    protected $entryBusinessUnitId;

    /**
     * @var int
     */
    protected $entryParentBusinessUnitId;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface $companyBusinessUnitEntityManager
     * @param \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitWriterPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository,
        CompanyBusinessUnitEntityManagerInterface $companyBusinessUnitEntityManager,
        CompanyBusinessUnitWriterPluginExecutorInterface $pluginExecutor
    ) {
        $this->repository = $companyBusinessUnitRepository;
        $this->entityManager = $companyBusinessUnitEntityManager;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function update(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitResponseTransfer = (new CompanyBusinessUnitResponseTransfer())
            ->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitResponseTransfer) {
            return $this->executeUpdateTransaction($companyBusinessUnitResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function delete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = $this->repository
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $companyBusinessUnitResponseTransfer = (new CompanyBusinessUnitResponseTransfer())
            ->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitResponseTransfer) {
            return $this->executeDeleteTransaction($companyBusinessUnitResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function executeDeleteTransaction(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitResponseTransfer
            ->getCompanyBusinessUnitTransfer()
            ->requireIdCompanyBusinessUnit();

        $companyBusinessUnitResponseTransfer = $this->checkOnRelatedUsers($companyBusinessUnitResponseTransfer);

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            return $companyBusinessUnitResponseTransfer;
        }

        $idCompanyBusinessUnit = $companyBusinessUnitResponseTransfer
            ->getCompanyBusinessUnitTransfer()
            ->getIdCompanyBusinessUnit();

        $this->entityManager->clearParentBusinessUnit($idCompanyBusinessUnit);
        $this->entityManager->deleteCompanyBusinessUnitById($idCompanyBusinessUnit);

        return $companyBusinessUnitResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function checkOnRelatedUsers(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): CompanyBusinessUnitResponseTransfer
    {
        $hasUsers = $this->repository->hasUsers(
            $companyBusinessUnitResponseTransfer
                ->getCompanyBusinessUnitTransfer()
                ->getIdCompanyBusinessUnit()
        );

        if ($hasUsers) {
            $companyBusinessUnitResponseTransfer
                ->addMessage(
                    (new ResponseMessageTransfer())
                        ->setText(static::ERROR_MESSAGE_HAS_RELATED_USERS)
                )
                ->setIsSuccessful(false);

            return $companyBusinessUnitResponseTransfer;
        }

        return $companyBusinessUnitResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function executeUpdateTransaction(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer();

        if ($this->companyBusinessUnitCycleDependencyExists($companyBusinessUnitTransfer)) {
            $companyBusinessUnitResponseTransfer->setIsSuccessful(false);

            return $companyBusinessUnitResponseTransfer;
        }

        $companyBusinessUnitTransfer = $this->entityManager->saveCompanyBusinessUnit($companyBusinessUnitTransfer);
        $companyBusinessUnitTransfer = $this->pluginExecutor->executePostSavePlugins($companyBusinessUnitTransfer);
        $companyBusinessUnitResponseTransfer->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);

        return $companyBusinessUnitResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function companyBusinessUnitCycleDependencyExists(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        $businessUnitId = (int)$companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        $parentBusinessUnitId = $companyBusinessUnitTransfer->getFkParentCompanyBusinessUnit();

        $this->entryBusinessUnitId = $businessUnitId;
        $this->entryParentBusinessUnitId = $parentBusinessUnitId;

        return $this->existCycleDependency($businessUnitId, $parentBusinessUnitId);
    }

    /**
     * @param int $businessUnitId
     * @param int $parentBusinessUnitId
     *
     * @return bool
     */
    protected function existCycleDependency($businessUnitId, $parentBusinessUnitId): bool
    {
        $companyBusinessUnitCriteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $companyBusinessUnitsCollection = $this->repository->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        $companyBusinessUnits = (array)$companyBusinessUnitsCollection->getCompanyBusinessUnits();

        static::$companyBUIdStack[] = $businessUnitId;

        // deep cycle dependency like if A is the parent of B and B is the parent of C and C is the parent of D, then D cannot be the parent of B or A
        if ($businessUnitId == $this->entryParentBusinessUnitId && in_array($parentBusinessUnitId, static::$companyBUIdStack)) {
            return true;
        }

        // no cycle dependency found
        if (!$businessUnitId) {
            return false;
        }

        $companyBusinessUnit = array_filter($companyBusinessUnits, function ($companyBusinessUnit) use ($businessUnitId) {
            return $companyBusinessUnit->getFkParentCompanyBusinessUnit() == $businessUnitId;
        });

        if (!empty($companyBusinessUnit)) {
            $businessUnitId = array_values($companyBusinessUnit)[0]->getIdCompanyBusinessUnit();
            $parentBusinessUnitId = array_values($companyBusinessUnit)[0]->getFkParentCompanyBusinessUnit();
        } else {
            $businessUnitId = $parentBusinessUnitId = null;
        }

        // simple cycle dependency like if A is the parent of B then B cannot be the parent of A
        if ($this->entryBusinessUnitId == $parentBusinessUnitId && $this->entryParentBusinessUnitId == $businessUnitId) {
            return true;
        }

        return $this->existCycleDependency($businessUnitId, $parentBusinessUnitId);
    }
}
