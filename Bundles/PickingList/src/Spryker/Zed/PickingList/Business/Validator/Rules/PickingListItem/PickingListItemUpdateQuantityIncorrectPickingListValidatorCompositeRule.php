<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Shared\PickingList\PickingListConfig;
use Spryker\Zed\PickingList\Business\Validator\AbstractPickingListValidatorCompositeRule;

class PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule extends AbstractPickingListValidatorCompositeRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY = 'picking_list.validation.wrong_property_picking_list_item_quantity';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY = 'picking_list.validation.incorrect_quantity';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED = 'picking_list.validation.only_full_quantity_picking_allowed';

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

        foreach ($pickingListCollectionTransfer->getPickingLists() as $entityIdentifier => $pickingListTransfer) {
            $errorCollectionTransfer = $this->executePickingListValidation(
                $entityIdentifier,
                $errorCollectionTransfer,
                $pickingListTransfer,
                $existingPickingListItemTransferCollectionIndexedByUuid,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollectionIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePickingListValidation(
        int $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer,
        PickingListTransfer $pickingListTransfer,
        array $existingPickingListItemTransferCollectionIndexedByUuid
    ): ErrorCollectionTransfer {
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $existingPickingListItemTransfer = $this->getExistingPickingListItemTransfer(
                $pickingListItemTransfer,
                $existingPickingListItemTransferCollectionIndexedByUuid,
            );

            if ($existingPickingListItemTransfer->getUuid() === null) {
                continue;
            }

            $errorList = $this->executePickingListItemValidation(
                $pickingListTransfer,
                $pickingListItemTransfer,
                $existingPickingListItemTransfer,
            );

            $errorCollectionTransfer = $this->mapErrorListToErrorCollectionTransfer(
                $errorList,
                $entityIdentifier,
                $errorCollectionTransfer,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $existingPickingListItemTransfer
     *
     * @return list<string>
     */
    protected function executePickingListItemValidation(
        PickingListTransfer $pickingListTransfer,
        PickingListItemTransfer $pickingListItemTransfer,
        PickingListItemTransfer $existingPickingListItemTransfer
    ): array {
        $errorList = [];
        $quantity = $this->getQuantity($pickingListItemTransfer, $existingPickingListItemTransfer);
        $numberOfPicked = $pickingListItemTransfer->getNumberOfPickedOrFail();
        $numberOfNotPicked = $pickingListItemTransfer->getNumberOfNotPickedOrFail();

        if ($quantity === 0) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY;
        }

        $processedQuantity = $numberOfPicked + $numberOfNotPicked;
        if ($processedQuantity === 0 && $pickingListTransfer->getStatus() === PickingListConfig::STATUS_PICKING_FINISHED) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY;
        }

        if ($processedQuantity !== 0 && $quantity !== $processedQuantity) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED;
        }

        if ($numberOfPicked !== 0 && $numberOfNotPicked !== 0) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED;
        }

        return $errorList;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $existingPickingListItemTransfer
     *
     * @return int
     */
    protected function getQuantity(
        PickingListItemTransfer $pickingListItemTransfer,
        PickingListItemTransfer $existingPickingListItemTransfer
    ): int {
        $isQuantityModified = $pickingListItemTransfer->isPropertyModified(
            PickingListItemTransfer::QUANTITY,
        );

        if ($isQuantityModified) {
            return $pickingListItemTransfer->getQuantityOrFail();
        }

        return $existingPickingListItemTransfer->getQuantityOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollectionIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    protected function getExistingPickingListItemTransfer(
        PickingListItemTransfer $pickingListItemTransfer,
        array $existingPickingListItemTransferCollectionIndexedByUuid
    ): PickingListItemTransfer {
        $pickingListItemUuid = $pickingListItemTransfer->getUuid();
        if ($pickingListItemUuid === null) {
            return new PickingListItemTransfer();
        }

        if (!isset($existingPickingListItemTransferCollectionIndexedByUuid[$pickingListItemUuid])) {
            return new PickingListItemTransfer();
        }

        return $existingPickingListItemTransferCollectionIndexedByUuid[$pickingListItemUuid];
    }
}
