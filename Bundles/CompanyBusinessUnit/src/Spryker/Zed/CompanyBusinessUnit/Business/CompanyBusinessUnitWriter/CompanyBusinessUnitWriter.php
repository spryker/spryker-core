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

    protected const ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE = 'message.business_unit.update.cycle_dependency_error';

    protected const MESSAGE_BUSINESS_UNIT_UPDATE_SUCCESS = 'message.business_unit.update';
    protected const MESSAGE_BUSINESS_UNIT_CREATE_SUCCESS = 'message.business_unit.create';
    protected const MESSAGE_BUSINESS_UNIT_DELETE_SUCCESS = 'message.business_unit.delete';

    protected const HIERARCHY_CYCLE_CHECK_DEPTH = 1000;

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
            ->addMessage((new ResponseMessageTransfer())->setText(static::MESSAGE_BUSINESS_UNIT_UPDATE_SUCCESS));

        if (!$companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getIdCompanyBusinessUnit()) {
            $companyBusinessUnitResponseTransfer
                ->setMessages(new ArrayObject())
                ->addMessage((new ResponseMessageTransfer())->setText(static::MESSAGE_BUSINESS_UNIT_CREATE_SUCCESS));
        }

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
            ->setIsSuccessful(true)
            ->addMessage((new ResponseMessageTransfer())->setText(static::MESSAGE_BUSINESS_UNIT_DELETE_SUCCESS));

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
            $message = (new ResponseMessageTransfer())->setText(static::ERROR_MESSAGE_HAS_RELATED_USERS);
            $companyBusinessUnitResponseTransfer
                ->setMessages(new ArrayObject([$message]))
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

        if ($this->isCompanyBusinessUnitHierarchyCycleExists($companyBusinessUnitTransfer)) {
            $companyBusinessUnitResponseTransfer
                ->setIsSuccessful(false)
                ->setMessages(new ArrayObject())
                ->addMessage(
                    (new ResponseMessageTransfer())->setText(static::ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE)
                );

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
    protected function isCompanyBusinessUnitHierarchyCycleExists(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        $businessUnitId = (int)$companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        $parentBusinessUnitId = (int)$companyBusinessUnitTransfer->getFkParentCompanyBusinessUnit();

        $companyBusinessUnitMap = $this->getCompanyBusinessUnits();

        // A new element in the tree can not cause cycle, since it has no descendants
        if (!$businessUnitId) {
            return false;
        }

        $companyBusinessUnitMap[$businessUnitId]->setFkParentCompanyBusinessUnit($parentBusinessUnitId);

        return $this->isHierarchyCycleExists($companyBusinessUnitMap, $businessUnitId);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]
     */
    protected function getCompanyBusinessUnits(): array
    {
        $companyBusinessUnitsCollection = $this->repository->getCompanyBusinessUnitCollection(new CompanyBusinessUnitCriteriaFilterTransfer());

        $companyBusinessUnits = [];
        foreach ($companyBusinessUnitsCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            $companyBusinessUnits[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit;
        }

        return $companyBusinessUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[] $companyBusinessUnitMap
     * @param int $entryCompanyBusinessUnitId
     *
     * @return bool
     */
    public function isHierarchyCycleExists(array $companyBusinessUnitMap, int $entryCompanyBusinessUnitId): bool
    {
        $allNodes = $companyBusinessUnitMap;
        $attemptCount = 0;

        $visitedNodes = [$entryCompanyBusinessUnitId];
        $nodeToCheck = $entryCompanyBusinessUnitId;

        do {
            if (in_array($allNodes[$nodeToCheck]->getFkParentCompanyBusinessUnit(), $visitedNodes)) {
                return true;
            }

            $visitedNodes[] = $allNodes[$nodeToCheck]->getFkParentCompanyBusinessUnit();
            $nodeToCheck = $allNodes[$nodeToCheck]->getFkParentCompanyBusinessUnit();
        } while ($nodeToCheck && $attemptCount++ < static::HIERARCHY_CYCLE_CHECK_DEPTH);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ResponseMessageTransfer
     */
    protected function getHierarchyCycleErrorMessageResponseTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): ResponseMessageTransfer
    {
        $responseMessage = new ResponseMessageTransfer();
        $responseMessage->setText(
            sprintf(
                static::ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE,
                $companyBusinessUnitTransfer->getName()
            )
        );

        return $responseMessage;
    }
}
