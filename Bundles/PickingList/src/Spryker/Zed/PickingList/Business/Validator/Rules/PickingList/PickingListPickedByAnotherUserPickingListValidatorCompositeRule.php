<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator\Rules\PickingList;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Shared\PickingList\PickingListConfig;
use Spryker\Zed\PickingList\Business\Validator\AbstractPickingListValidatorCompositeRule;

class PickingListPickedByAnotherUserPickingListValidatorCompositeRule extends AbstractPickingListValidatorCompositeRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER = 'picking_list.validation.picked_by_another_user';

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
            $existingPickingListTransfer = $this->getExistingPickingListTransfer(
                $pickingListTransfer,
                $existingPickingListTransferCollectionIndexedByUuid,
            );

            $errorCollectionTransfer = $this->executePickingListValidation(
                $entityIdentifier,
                $errorCollectionTransfer,
                $pickingListTransfer,
                $existingPickingListTransfer,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $existingPickingListTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePickingListValidation(
        int $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer,
        PickingListTransfer $pickingListTransfer,
        PickingListTransfer $existingPickingListTransfer
    ): ErrorCollectionTransfer {
        if ($pickingListTransfer->getStatus() === PickingListConfig::STATUS_READY_FOR_PICKING) {
            return $errorCollectionTransfer;
        }

        if ($existingPickingListTransfer->getUuid() === null) {
            return $errorCollectionTransfer;
        }

        $pickingListUserUuid = $this->getUserUuid($pickingListTransfer);
        $existingPickingListUserUuid = $this->getUserUuid($existingPickingListTransfer);
        if ($pickingListUserUuid === $existingPickingListUserUuid) {
            return $errorCollectionTransfer;
        }

        return $errorCollectionTransfer->addError(
            $this->createErrorTransfer($entityIdentifier, static::GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return string|null
     */
    protected function getUserUuid(PickingListTransfer $pickingListTransfer): ?string
    {
        $pickingListUser = $pickingListTransfer->getUser();
        if (!$pickingListUser) {
            return null;
        }

        return $pickingListUser->getUuid();
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $existingPickingListTransferCollectionIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function getExistingPickingListTransfer(
        PickingListTransfer $pickingListTransfer,
        array $existingPickingListTransferCollectionIndexedByUuid
    ): PickingListTransfer {
        $pickingListUuid = $pickingListTransfer->getUuid();
        if ($pickingListUuid === null) {
            return new PickingListTransfer();
        }

        if (!isset($existingPickingListTransferCollectionIndexedByUuid[$pickingListUuid])) {
            return new PickingListTransfer();
        }

        return $existingPickingListTransferCollectionIndexedByUuid[$pickingListUuid];
    }
}
