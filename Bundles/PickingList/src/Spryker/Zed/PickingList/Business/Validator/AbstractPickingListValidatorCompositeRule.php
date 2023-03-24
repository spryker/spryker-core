<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

abstract class AbstractPickingListValidatorCompositeRule implements PickingListValidatorCompositeRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $existingPickingListTransferCollectionIndexedByUuid
     * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollectionIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    abstract public function validate(
        PickingListCollectionTransfer $pickingListCollectionTransfer,
        array $existingPickingListTransferCollectionIndexedByUuid,
        array $existingPickingListItemTransferCollectionIndexedByUuid
    ): ErrorCollectionTransfer;

    /**
     * @param list<string> $errorList
     * @param int $entityIdentifier
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function mapErrorListToErrorCollectionTransfer(
        array $errorList,
        int $entityIdentifier,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        foreach ($errorList as $errorMessage) {
            $errorTransfer = $this->createErrorTransfer($entityIdentifier, $errorMessage);
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $entityIdentifier
     * @param string $error
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(
        int $entityIdentifier,
        string $error
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setMessage($error)
            ->setEntityIdentifier(
                (string)$entityIdentifier,
            );
    }
}
