<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator\Rules;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;

class SingleActiveWarehouseUserAssignmentValidatorRule implements WarehouseUserAssignmentValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS = 'warehouse_user_assignment.validation.too_many_active_warehouse_assignments';

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    protected WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder;

    /**
     * @param \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
     */
    public function __construct(
        WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
    ) {
        $this->warehouseUserAssignmentIdentifierBuilder = $warehouseUserAssignmentIdentifierBuilder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        if (!$this->isAnyOfWarehouseUserAssignmentCollectionActive($warehouseUserAssignmentTransfers)) {
            return $warehouseUserAssignmentCollectionResponseTransfer;
        }

        return $this->validateWarehouseUserAssignmentsIsActiveStatus(
            $warehouseUserAssignmentTransfers,
            $warehouseUserAssignmentCollectionResponseTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return bool
     */
    protected function isAnyOfWarehouseUserAssignmentCollectionActive(ArrayObject $warehouseUserAssignmentTransfers): bool
    {
        /** @var \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer */
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $requestedWarehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function validateWarehouseUserAssignmentsIsActiveStatus(
        ArrayObject $requestedWarehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $groupedRequestedWarehouseUserAssignmentTransfers = $this->getWarehouseUserAssignmentTransfersGroupedByUserUuid($requestedWarehouseUserAssignmentTransfers);

        foreach ($groupedRequestedWarehouseUserAssignmentTransfers as $requestedUserWarehouseUserAssignmentTransfers) {
            if ($this->getNumberOfActiveWarehouseUserAssignments($requestedUserWarehouseUserAssignmentTransfers) > 1) {
                $warehouseUserAssignmentCollectionResponseTransfer = $this->addErrorsForActiveWarehouseUserAssignments(
                    $requestedUserWarehouseUserAssignmentTransfers,
                    $warehouseUserAssignmentCollectionResponseTransfer,
                );
            }
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return int
     */
    protected function getNumberOfActiveWarehouseUserAssignments(array $warehouseUserAssignmentTransfers): int
    {
        $count = 0;
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getIsActive()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>
     */
    protected function getWarehouseUserAssignmentTransfersGroupedByUserUuid(ArrayObject $warehouseUserAssignmentTransfers): array
    {
        $groupedWarehouseUserAssignmentTransfers = [];
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            $groupedWarehouseUserAssignmentTransfers[$warehouseUserAssignmentTransfer->getUserUuidOrFail()][] = $warehouseUserAssignmentTransfer;
        }

        return $groupedWarehouseUserAssignmentTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $userWarehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function addErrorsForActiveWarehouseUserAssignments(
        array $userWarehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        foreach ($userWarehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getIsActive() === true) {
                $warehouseUserAssignmentCollectionResponseTransfer->addError(
                    $this->createErrorTransfer(
                        static::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS,
                        $this->warehouseUserAssignmentIdentifierBuilder->buildIdentifier($warehouseUserAssignmentTransfer),
                    ),
                );
            }
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }

    /**
     * @param string $message
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $message, string $identifier): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage($message)
            ->setEntityIdentifier($identifier);
    }
}
