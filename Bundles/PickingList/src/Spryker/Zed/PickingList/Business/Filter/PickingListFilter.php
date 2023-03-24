<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Filter;

use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

class PickingListFilter implements PickingListFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getValidPickingLists(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionTransfer {
        $pickingListIdsWithErrors = $this->getPickingListIdsWithErrors($pickingListCollectionResponseTransfer);
        $pickingListCollectionTransfer = new PickingListCollectionTransfer();
        foreach ($pickingListCollectionResponseTransfer->getPickingLists() as $entityIdentifier => $pickingListTransfer) {
            if (!$this->isPickingListValid($entityIdentifier, $pickingListIdsWithErrors)) {
                continue;
            }

            $pickingListCollectionTransfer->addPickingList($pickingListTransfer);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getInvalidPickingLists(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionTransfer {
        $pickingListIdsWithErrors = $this->getPickingListIdsWithErrors($pickingListCollectionResponseTransfer);
        $pickingListCollectionTransfer = new PickingListCollectionTransfer();
        foreach ($pickingListCollectionResponseTransfer->getPickingLists() as $entityIdentifier => $pickingListTransfer) {
            if ($this->isPickingListValid($entityIdentifier, $pickingListIdsWithErrors)) {
                continue;
            }

            $pickingListCollectionTransfer->addPickingList($pickingListTransfer);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $validPickingListCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $invalidPickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function mergeValidAndInvalidPickingLists(
        PickingListCollectionTransfer $validPickingListCollectionTransfer,
        PickingListCollectionTransfer $invalidPickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($invalidPickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $validPickingListCollectionTransfer->addPickingList($pickingListTransfer);
        }

        return $validPickingListCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param list<int> $pickingListIdsWithErrors
     *
     * @return bool
     */
    protected function isPickingListValid(
        int $entityIdentifier,
        array $pickingListIdsWithErrors
    ): bool {
        return !in_array($entityIdentifier, $pickingListIdsWithErrors, true);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return list<int>
     */
    protected function getPickingListIdsWithErrors(PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer): array
    {
        $entityIdentifierCollection = [];
        foreach ($pickingListCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $entityIdentifier = (int)$errorTransfer->getEntityIdentifierOrFail();

            if (!in_array($entityIdentifier, $entityIdentifierCollection)) {
                $entityIdentifierCollection[] = $entityIdentifier;
            }
        }

        return $entityIdentifierCollection;
    }
}
