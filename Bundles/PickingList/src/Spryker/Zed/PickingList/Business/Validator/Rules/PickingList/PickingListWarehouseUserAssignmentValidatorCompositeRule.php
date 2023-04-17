<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator\Rules\PickingList;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface;
use Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouperInterface;
use Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Zed\PickingList\Business\Validator\AbstractPickingListValidatorCompositeRule;

class PickingListWarehouseUserAssignmentValidatorCompositeRule extends AbstractPickingListValidatorCompositeRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND = 'picking_list.validation.picking_list_entity_not_found';

    /**
     * @var \Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface
     */
    protected PickingListExtractorInterface $pickingListExtractor;

    /**
     * @var \Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReaderInterface
     */
    protected WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader;

    /**
     * @var \Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouperInterface
     */
    protected WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper;

    /**
     * @param \Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface $pickingListExtractor
     * @param \Spryker\Zed\PickingList\Business\Reader\WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader
     * @param \Spryker\Zed\PickingList\Business\Grouper\WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper
     */
    public function __construct(
        PickingListExtractorInterface $pickingListExtractor,
        WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader,
        WarehouseUserAssignmentGrouperInterface $warehouseUserAssignmentGrouper
    ) {
        $this->pickingListExtractor = $pickingListExtractor;
        $this->warehouseUserAssignmentReader = $warehouseUserAssignmentReader;
        $this->warehouseUserAssignmentGrouper = $warehouseUserAssignmentGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $existingPickingListTransferCollectionIndexedByUuid
     * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollectionIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(
        PickingListCollectionTransfer $pickingListCollectionTransfer,
        array $existingPickingListTransferCollectionIndexedByUuid,
        array $existingPickingListItemTransferCollectionIndexedByUuid
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $warehouseUuids = $this->pickingListExtractor->extraWarehouseUuidsFromPickingListCollection($pickingListCollectionTransfer);
        $userUuids = $this->pickingListExtractor->extraUserUuidsFromPickingListCollection($pickingListCollectionTransfer);
        if ($userUuids === []) {
            return $errorCollectionTransfer;
        }

        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer(
            $warehouseUuids,
            $userUuids,
        );
        $warehouseUserAssignmentCollectionTransfer = $this
            ->warehouseUserAssignmentReader
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);
        $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid = $this
            ->warehouseUserAssignmentGrouper
            ->groupWarehouseUserAssignmentCollectionByUserUuidAndWarehouseUuid($warehouseUserAssignmentCollectionTransfer);

        foreach ($pickingListCollectionTransfer->getPickingLists() as $i => $pickingListTransfer) {
            $errorCollectionTransfer = $this->executePickingListValidation(
                $pickingListTransfer,
                $errorCollectionTransfer,
                $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid,
                $i,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param list<string> $warehouseUuids
     * @param list<string> $userUuids
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCriteriaTransfer(
        array $warehouseUuids,
        array $userUuids
    ): WarehouseUserAssignmentCriteriaTransfer {
        $warehouseUserAssignmentConditions = (new WarehouseUserAssignmentConditionsTransfer())
            ->setIsActive(true)
            ->setWarehouseUuids($warehouseUuids)
            ->setUserUuids($userUuids);

        return (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditions);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param array<string, array<string, array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>> $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid
     * @param int $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePickingListValidation(
        PickingListTransfer $pickingListTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid,
        int $entityIdentifier
    ): ErrorCollectionTransfer {
        $userUuid = null;
        $warehouseUuid = null;

        if ($pickingListTransfer->getUser()) {
            $userUuid = $pickingListTransfer->getUserOrFail()->getUuidOrFail();
        }

        if ($pickingListTransfer->getWarehouse()) {
            $warehouseUuid = $pickingListTransfer->getWarehouseOrFail()->getUuidOrFail();
        }

        if (
            isset($warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid[$userUuid])
            && isset($warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid[$userUuid][$warehouseUuid])
        ) {
            return $errorCollectionTransfer;
        }

        $errorTransfer = $this->createErrorTransfer(
            $entityIdentifier,
            static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
        );

        return $errorCollectionTransfer->addError($errorTransfer);
    }
}
