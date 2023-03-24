<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator\Rules;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface;

class WarehouseUserAssignmentExistsValidatorRule implements WarehouseUserAssignmentValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_user_assignment_not_found';

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface
     */
    protected WarehouseUserRepositoryInterface $warehouseUserRepository;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    protected WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder;

    /**
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface $warehouseUserRepository
     * @param \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
     */
    public function __construct(
        WarehouseUserRepositoryInterface $warehouseUserRepository,
        WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
    ) {
        $this->warehouseUserRepository = $warehouseUserRepository;
        $this->warehouseUserAssignmentIdentifierBuilder = $warehouseUserAssignmentIdentifierBuilder;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $warehouseUserCriteriaTransfer = $this->createWarehouseUserCriteriaTransfer(
            $warehouseUserAssignmentTransfers,
        );
        $warehouseUsersAssignmentCollectionTransfer = $this->warehouseUserRepository->getWarehouseUserAssignmentCollection($warehouseUserCriteriaTransfer);

        return $this->validateWarehouseUserAssignmentsExist(
            $warehouseUserAssignmentTransfers,
            $warehouseUsersAssignmentCollectionTransfer,
            $warehouseUserAssignmentCollectionResponseTransfer,
        );
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserCriteriaTransfer(ArrayObject $warehouseUserAssignmentTransfers): WarehouseUserAssignmentCriteriaTransfer
    {
        $warehouseUserAssignmentConditionsTransfer = new WarehouseUserAssignmentConditionsTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment()) {
                $warehouseUserAssignmentConditionsTransfer->addIdWarehouseUserAssignment(
                    $warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail(),
                );
            }

            if ($warehouseUserAssignmentTransfer->getUuid()) {
                $warehouseUserAssignmentConditionsTransfer->addUuid($warehouseUserAssignmentTransfer->getUuidOrFail());
            }
        }

        return (new WarehouseUserAssignmentCriteriaTransfer())->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function validateWarehouseUserAssignmentsExist(
        ArrayObject $warehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $indexedWarehouseUserAssignmentTransfers = $this->getWarehouseUserAssignmentTransfersIndexedByUuid($warehouseUserAssignmentCollectionTransfer);
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if (!isset($indexedWarehouseUserAssignmentTransfers[$warehouseUserAssignmentTransfer->getUuidOrFail()])) {
                $warehouseUserAssignmentCollectionResponseTransfer->addError(
                    $this->createErrorTransfer(
                        static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND,
                        $this->warehouseUserAssignmentIdentifierBuilder->buildIdentifier($warehouseUserAssignmentTransfer),
                    ),
                );
            }
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    protected function getWarehouseUserAssignmentTransfersIndexedByUuid(
        WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
    ): array {
        $indexedWarehouseUserAssignmentTransfers = [];
        foreach ($warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments() as $warehouseUserAssignmentTransfer) {
            $indexedWarehouseUserAssignmentTransfers[$warehouseUserAssignmentTransfer->getUuidOrFail()] = $warehouseUserAssignmentTransfer;
        }

        return $indexedWarehouseUserAssignmentTransfers;
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
