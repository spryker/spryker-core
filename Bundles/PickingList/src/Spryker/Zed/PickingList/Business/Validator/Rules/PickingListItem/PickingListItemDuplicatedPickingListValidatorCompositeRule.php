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

class PickingListItemDuplicatedPickingListValidatorCompositeRule extends AbstractPickingListValidatorCompositeRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED = 'picking_list.validation.picking_list_item_duplicated';

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
        $pickingListItemUuids = [];
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $errorCollectionTransfer = $this->executePickingListItemValidation(
                $entityIdentifier,
                $errorCollectionTransfer,
                $pickingListItemTransfer,
                $pickingListItemUuids,
            );

            $pickingListItemUuids[] = $pickingListItemTransfer->getUuidOrFail();
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param list<string> $pickingListItemUuids
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePickingListItemValidation(
        int $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer,
        PickingListItemTransfer $pickingListItemTransfer,
        array $pickingListItemUuids
    ): ErrorCollectionTransfer {
        $pickingListItemUuid = $pickingListItemTransfer->getUuidOrFail();

        if (!in_array($pickingListItemUuid, $pickingListItemUuids)) {
            return $errorCollectionTransfer;
        }

        return $errorCollectionTransfer->addError(
            $this->createErrorTransfer($entityIdentifier, static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED),
        );
    }
}
