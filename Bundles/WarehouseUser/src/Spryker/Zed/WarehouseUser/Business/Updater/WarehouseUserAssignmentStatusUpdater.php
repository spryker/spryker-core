<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Updater;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface;

class WarehouseUserAssignmentStatusUpdater implements WarehouseUserAssignmentStatusUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface
     */
    protected WarehouseUserRepositoryInterface $warehouseUserRepository;

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface
     */
    protected WarehouseUserEntityManagerInterface $warehouseUserEntityManager;

    /**
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface $warehouseUserRepository
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface $warehouseUserEntityManager
     */
    public function __construct(
        WarehouseUserRepositoryInterface $warehouseUserRepository,
        WarehouseUserEntityManagerInterface $warehouseUserEntityManager
    ) {
        $this->warehouseUserRepository = $warehouseUserRepository;
        $this->warehouseUserEntityManager = $warehouseUserEntityManager;
    }

    /**
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return void
     */
    public function deactivatePreviouslyActivatedWarehouseUserAssignments(array $warehouseUserAssignmentTransfers): void
    {
        $userUuids = $this->extractUserUuidsFromActiveWarehouseUserAssignments($warehouseUserAssignmentTransfers);
        if ($userUuids === []) {
            return;
        }

        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer($userUuids);
        $persistedWarehouseUserAssignmentCollection = $this->warehouseUserRepository->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);
        $filteredPersistedWarehouseUserAssignmentTransfers = $this->filterWarehouseUserAssignmentsToDeactivate(
            $persistedWarehouseUserAssignmentCollection,
            $warehouseUserAssignmentTransfers,
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($filteredPersistedWarehouseUserAssignmentTransfers): void {
            $this->executeDeactivateWarehouseUserAssignmentsTransaction($filteredPersistedWarehouseUserAssignmentTransfers);
        });
    }

    /**
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return void
     */
    protected function executeDeactivateWarehouseUserAssignmentsTransaction(array $warehouseUserAssignmentTransfers): void
    {
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            $warehouseUserAssignmentTransfer->setIsActive(false);
            $this->warehouseUserEntityManager->updateWarehouseUserAssignment($warehouseUserAssignmentTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return list<string>
     */
    protected function extractUserUuidsFromActiveWarehouseUserAssignments(array $warehouseUserAssignmentTransfers): array
    {
        $userUuids = [];
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getIsActiveOrFail()) {
                $userUuids[] = $warehouseUserAssignmentTransfer->getUserUuidOrFail();
            }
        }

        return $userUuids;
    }

    /**
     * @param list<string> $userUuids
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCriteriaTransfer(array $userUuids): WarehouseUserAssignmentCriteriaTransfer
    {
        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->setUserUuids($userUuids)
            ->setIsActive(true);

        return (new WarehouseUserAssignmentCriteriaTransfer())->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $persistedWarehouseUserAssignmentCollectionTransfer
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $requestedWarehouseUserAllocationTransfers
     *
     * @return list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    protected function filterWarehouseUserAssignmentsToDeactivate(
        WarehouseUserAssignmentCollectionTransfer $persistedWarehouseUserAssignmentCollectionTransfer,
        array $requestedWarehouseUserAllocationTransfers
    ): array {
        $filteredWarehouseUserAssignmentTransfers = [];
        foreach ($persistedWarehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments() as $persistedWarehouseUserAssignmentTransfer) {
            if (
                $this->isPersistedWarehouseUserAssignmentsShouldBeDeactivated(
                    $persistedWarehouseUserAssignmentTransfer,
                    $requestedWarehouseUserAllocationTransfers,
                )
            ) {
                $filteredWarehouseUserAssignmentTransfers[] = $persistedWarehouseUserAssignmentTransfer;
            }
        }

        return $filteredWarehouseUserAssignmentTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $persistedWarehouseUserAssignmentTransfer
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $requestedWarehouseUserAssignmentTransfers
     *
     * @return bool
     */
    protected function isPersistedWarehouseUserAssignmentsShouldBeDeactivated(
        WarehouseUserAssignmentTransfer $persistedWarehouseUserAssignmentTransfer,
        array $requestedWarehouseUserAssignmentTransfers
    ): bool {
        foreach ($requestedWarehouseUserAssignmentTransfers as $requestedWarehouseUserAssignmentTransfer) {
            if (
                $this->isNewWarehouseUserAssignment($requestedWarehouseUserAssignmentTransfer)
                || !$this->isSameWarehouseUserAssignment($persistedWarehouseUserAssignmentTransfer, $requestedWarehouseUserAssignmentTransfer)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return bool
     */
    protected function isNewWarehouseUserAssignment(WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer): bool
    {
        return $warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment() === null && $warehouseUserAssignmentTransfer->getUuid() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransferA
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransferB
     *
     * @return bool
     */
    protected function isSameWarehouseUserAssignment(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransferA,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransferB
    ): bool {
        return $warehouseUserAssignmentTransferB->getUuidOrFail() === $warehouseUserAssignmentTransferA->getUuid()
            || $warehouseUserAssignmentTransferB->getIdWarehouseUserAssignmentOrFail() === $warehouseUserAssignmentTransferA->getIdWarehouseUserAssignment();
    }
}
