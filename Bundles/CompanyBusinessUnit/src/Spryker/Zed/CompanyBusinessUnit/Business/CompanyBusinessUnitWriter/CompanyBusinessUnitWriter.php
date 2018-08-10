<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter;

use ArrayObject;
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

    protected const MESSAGE_CYCLE_DEPENDENCY_ERROR_COMPANY_BUSINESS_UNIT_UPDATE = 'Company Business Unit "%s" has not been updated. A Business Unit cannot be set as a child to an own child Business Unit, please check the Business Unit hierarchy.';

    protected const MESSAGE_SUCCESS_COMPANY_BUSINESS_UNIT_UPDATE = 'Company Business Unit "%s" has been updated.';

    protected const CYCLE_CHECK_DEPTH = 1000;

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
            ->setIsSuccessful(true)
            ->addMessage($this->getSuccessMessageResponseTransfer($companyBusinessUnitTransfer));

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

        if ($this->isCompanyBusinessUnitCycleDependencyExists($companyBusinessUnitTransfer)) {
            $companyBusinessUnitResponseTransfer->setIsSuccessful(false);
            $companyBusinessUnitResponseTransfer->setMessages(new ArrayObject());
            $companyBusinessUnitResponseTransfer->addMessage($this->getCycleDependencyErrorMessageResponseTransfer($companyBusinessUnitTransfer));

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
    protected function isCompanyBusinessUnitCycleDependencyExists(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        $businessUnitId = (int)$companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        $parentBusinessUnitId = (int)$companyBusinessUnitTransfer->getFkParentCompanyBusinessUnit();

        return $this->isCycleDependencyExists($businessUnitId, $parentBusinessUnitId);
    }

    /**
     * @param int $businessUnitId
     * @param int $parentBusinessUnitId
     *
     * @return bool
     */
    protected function isCycleDependencyExists($businessUnitId, $parentBusinessUnitId): bool
    {
        $companyBusinessUnitCriteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $companyBusinessUnitsCollection = $this->repository->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        $allNodesFiltered = [];
        $attemptCount = 0;

        foreach ($companyBusinessUnitsCollection->getCompanyBusinessUnits() as $node) {
            $allNodesFiltered[$node->getIdCompanyBusinessUnit()] = $node;
        }

        $allNodesFiltered[$businessUnitId]->setFkParentCompanyBusinessUnit($parentBusinessUnitId);
        $visitedNodes = [$businessUnitId];
        $nodesToCheck = [$businessUnitId];

        do {
            $nodesToCheckInNextRound = [];
            foreach ($allNodesFiltered as $node) {
                foreach ($nodesToCheck as $nodeToCheck) {
                    if (!$nodeToCheck) {
                        return false;
                    }

                    if ($node->getIdCompanyBusinessUnit() !== $allNodesFiltered[$nodeToCheck]->getIdCompanyBusinessUnit()) {
                        continue;
                    }

                    if (in_array($allNodesFiltered[$nodeToCheck]->getFkParentCompanyBusinessUnit(), $visitedNodes)) {
                        return true;
                    }

                    $nodesToCheckInNextRound[] = $allNodesFiltered[$nodeToCheck]->getFkParentCompanyBusinessUnit();
                    $visitedNodes[] = $allNodesFiltered[$nodeToCheck]->getFkParentCompanyBusinessUnit();
                }
            }

            $nodesToCheck = $nodesToCheckInNextRound;
        } while ($nodesToCheck && $attemptCount++ < static::CYCLE_CHECK_DEPTH);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ResponseMessageTransfer
     */
    protected function getSuccessMessageResponseTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): ResponseMessageTransfer
    {
        $responseMessage = new ResponseMessageTransfer();
        $responseMessage->setText(
            sprintf(
                static::MESSAGE_SUCCESS_COMPANY_BUSINESS_UNIT_UPDATE,
                $companyBusinessUnitTransfer->getName()
            )
        );

        return $responseMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ResponseMessageTransfer
     */
    protected function getCycleDependencyErrorMessageResponseTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): ResponseMessageTransfer
    {
        $responseMessage = new ResponseMessageTransfer();
        $responseMessage->setText(
            sprintf(
                static::MESSAGE_CYCLE_DEPENDENCY_ERROR_COMPANY_BUSINESS_UNIT_UPDATE,
                $companyBusinessUnitTransfer->getName()
            )
        );

        return $responseMessage;
    }
}
