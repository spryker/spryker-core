<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;
use Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface;

class WarehouseUserAssignmentUpdater implements WarehouseUserAssignmentUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface
     */
    protected WarehouseUserEntityManagerInterface $warehouseUserEntityManager;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface
     */
    protected WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentStatusUpdaterInterface
     */
    protected WarehouseUserAssignmentStatusUpdaterInterface $warehouseUserAssignmentStatusUpdater;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    protected WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder;

    /**
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface $warehouseUserEntityManager
     * @param \Spryker\Zed\WarehouseUser\Business\Validator\WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator
     * @param \Spryker\Zed\WarehouseUser\Business\Updater\WarehouseUserAssignmentStatusUpdaterInterface $warehouseUserAssignmentStatusUpdater
     * @param \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
     */
    public function __construct(
        WarehouseUserEntityManagerInterface $warehouseUserEntityManager,
        WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator,
        WarehouseUserAssignmentStatusUpdaterInterface $warehouseUserAssignmentStatusUpdater,
        WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
    ) {
        $this->warehouseUserEntityManager = $warehouseUserEntityManager;
        $this->warehouseUserAssignmentValidator = $warehouseUserAssignmentValidator;
        $this->warehouseUserAssignmentStatusUpdater = $warehouseUserAssignmentStatusUpdater;
        $this->warehouseUserAssignmentIdentifierBuilder = $warehouseUserAssignmentIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function updateWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        /** @var \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers */
        $warehouseUserAssignmentTransfers = $warehouseUserAssignmentCollectionRequestTransfer->getWarehouseUserAssignments();
        $warehouseUserAssignmentCollectionResponseTransfer = $this->warehouseUserAssignmentValidator
            ->validateCollection($warehouseUserAssignmentTransfers);

        /** @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransferCollection */
        $errorTransferCollection = $warehouseUserAssignmentCollectionResponseTransfer->getErrors();

        if ($warehouseUserAssignmentCollectionRequestTransfer->getIsTransactional() && $errorTransferCollection->count()) {
            return $warehouseUserAssignmentCollectionResponseTransfer;
        }

        [$validWarehouseUserAssignmentTransfers, $invalidWarehouseUserAssignmentTransfers] = $this->splitWarehouseUserAssignmentTransfersByValidity(
            $warehouseUserAssignmentCollectionResponseTransfer,
        );
        if ($validWarehouseUserAssignmentTransfers === []) {
            return $warehouseUserAssignmentCollectionResponseTransfer;
        }

        $persistedWarehouseUserAssignmentTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validWarehouseUserAssignmentTransfers) {
            return $this->executeUpdateWarehouseUserAssignmentCollectionTransaction($validWarehouseUserAssignmentTransfers);
        });

        return $warehouseUserAssignmentCollectionResponseTransfer->setWarehouseUserAssignments(new ArrayObject(
            array_merge($persistedWarehouseUserAssignmentTransfers, $invalidWarehouseUserAssignmentTransfers),
        ));
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    protected function executeUpdateWarehouseUserAssignmentCollectionTransaction(array $warehouseUserAssignmentTransfers): array
    {
        $this->warehouseUserAssignmentStatusUpdater->deactivatePreviouslyActivatedWarehouseUserAssignments(
            $warehouseUserAssignmentTransfers,
        );

        $persistedWarehouseUserAssignmentTransfers = [];
        foreach ($warehouseUserAssignmentTransfers as $key => $warehouseUserAssignmentTransfer) {
            $persistedWarehouseUserAssignmentTransfers[$key] = $this->warehouseUserEntityManager->updateWarehouseUserAssignment($warehouseUserAssignmentTransfer);
        }

        return $persistedWarehouseUserAssignmentTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return list<array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>
     */
    protected function splitWarehouseUserAssignmentTransfersByValidity(
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransferCollection */
        $errorTransferCollection = $warehouseUserAssignmentCollectionResponseTransfer->getErrors();

        $warehouseUserAssignmentIdsWithErrors = $this->getErroredWarehouseUserAssignmentsIdentifiers($errorTransferCollection);

        $validWarehouseUserAssignmentTransfers = [];
        $invalidWarehouseUserAssignmentTransfers = [];
        foreach ($warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments() as $key => $warehouseUserAssignmentTransfer) {
            if ($this->isErroredWarehouseUserAssignment($warehouseUserAssignmentTransfer, $warehouseUserAssignmentIdsWithErrors)) {
                $invalidWarehouseUserAssignmentTransfers[$key] = $warehouseUserAssignmentTransfer;

                continue;
            }

            $validWarehouseUserAssignmentTransfers[$key] = $warehouseUserAssignmentTransfer;
        }

        return [
            $validWarehouseUserAssignmentTransfers,
            $invalidWarehouseUserAssignmentTransfers,
        ];
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return list<string>
     */
    protected function getErroredWarehouseUserAssignmentsIdentifiers(ArrayObject $errorTransfers): array
    {
        $warehouseUserAssignmentIdentifiers = [];
        foreach ($errorTransfers as $errorTransfer) {
            $entityIdentifier = $errorTransfer->getEntityIdentifierOrFail();
            $warehouseUserAssignmentIdentifiers[$entityIdentifier] = $entityIdentifier;
        }

        return array_values($warehouseUserAssignmentIdentifiers);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param list<string> $warehouseUserAssignmentIdsWithErrors
     *
     * @return bool
     */
    protected function isErroredWarehouseUserAssignment(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        array $warehouseUserAssignmentIdsWithErrors
    ): bool {
        return in_array(
            $this->warehouseUserAssignmentIdentifierBuilder->buildIdentifier($warehouseUserAssignmentTransfer),
            $warehouseUserAssignmentIdsWithErrors,
            true,
        );
    }
}
