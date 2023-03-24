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
use Spryker\Zed\PickingList\Business\Validator\AbstractPickingListValidatorCompositeRule;

class PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule extends AbstractPickingListValidatorCompositeRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_picked';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_not_picked';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY = 'picking_list.validation.wrong_property_picking_list_item_quantity';

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
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePickingListValidation(
        int $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer,
        PickingListTransfer $pickingListTransfer
    ): ErrorCollectionTransfer {
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $errorList = $this->executePickingListItemValidation(
                $pickingListItemTransfer,
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
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return list<string>
     */
    protected function executePickingListItemValidation(
        PickingListItemTransfer $pickingListItemTransfer
    ): array {
        $errorList = [];

        $quantity = $pickingListItemTransfer->getQuantityOrFail();
        $numberOfPicked = $pickingListItemTransfer->getNumberOfPickedOrFail();
        $numberOfNotPicked = $pickingListItemTransfer->getNumberOfNotPickedOrFail();

        if ($quantity === 0) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY;
        }

        if ($numberOfPicked !== 0) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED;
        }

        if ($numberOfNotPicked !== 0) {
            $errorList[] = static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED;
        }

        return $errorList;
    }
}
