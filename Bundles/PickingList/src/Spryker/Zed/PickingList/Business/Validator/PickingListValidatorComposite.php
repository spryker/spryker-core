<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;

class PickingListValidatorComposite implements PickingListValidatorCompositeInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface
     */
    protected PickingListGrouperInterface $pickingListGrouper;

    /**
     * @var list<\Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface>
     */
    protected array $validatorRules = [];

    /**
     * @param \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface $pickingListGrouper
     * @param list<\Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface> $validatorRules
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListGrouperInterface $pickingListGrouper,
        array $validatorRules
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListGrouper = $pickingListGrouper;
        $this->validatorRules = $validatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $existingPickingListCollectionTransfer = $this->getExistingPickingListCollectionTransfer(
            $pickingListCollectionTransfer,
        );

        $pickingListTransferCollectionIndexedByUuid = $this->pickingListGrouper
            ->getPickingListTransferCollectionIndexedByUuid($existingPickingListCollectionTransfer);
        $pickingListItemTransferCollectionIndexedByUuid = $this->pickingListGrouper
            ->getPickingListItemTransferCollectionIndexedByUuid($existingPickingListCollectionTransfer);

        foreach ($this->validatorRules as $validatorRule) {
            $errors = $validatorRule->validate(
                $pickingListCollectionTransfer,
                $pickingListTransferCollectionIndexedByUuid,
                $pickingListItemTransferCollectionIndexedByUuid,
            );

            $errorCollectionTransfer = $this->mergeErrorCollectionTransfers(
                $errors,
                $errorCollectionTransfer,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function getExistingPickingListCollectionTransfer(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        if ($this->isNewPickingListCollection($pickingListCollectionTransfer) === true) {
            return new PickingListCollectionTransfer();
        }

        $pickingListUuids = $this->getPickingListUuids($pickingListCollectionTransfer);

        return $this->pickingListReader->getPickingListCollection(
            $this->createPickingListCriteriaTransfer($pickingListUuids),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return bool
     */
    protected function isNewPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): bool {
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            if ($pickingListTransfer->getIdPickingList()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $sourceErrorCollectionTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $targetErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function mergeErrorCollectionTransfers(
        ErrorCollectionTransfer $sourceErrorCollectionTransfer,
        ErrorCollectionTransfer $targetErrorCollectionTransfer
    ): ErrorCollectionTransfer {
        foreach ($sourceErrorCollectionTransfer->getErrors() as $errorTransfer) {
            if ($this->hasError($targetErrorCollectionTransfer, $errorTransfer)) {
                continue;
            }
            $targetErrorCollectionTransfer->addError($errorTransfer);
        }

        return $targetErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\ErrorTransfer $targetErrorTransfer
     *
     * @return bool
     */
    protected function hasError(ErrorCollectionTransfer $errorCollectionTransfer, ErrorTransfer $targetErrorTransfer): bool
    {
        foreach ($errorCollectionTransfer->getErrors() as $errorTransfer) {
            if (
                $errorTransfer->getEntityIdentifierOrFail() === $targetErrorTransfer->getEntityIdentifierOrFail()
                && $errorTransfer->getMessageOrFail() === $targetErrorTransfer->getMessageOrFail()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    protected function getPickingListUuids(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array {
        $pickingListUuids = [];

        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $uuidPickingList = $pickingListTransfer->getUuid();

            if ($uuidPickingList !== null) {
                $pickingListUuids[] = $uuidPickingList;
            }
        }

        return $pickingListUuids;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    protected function createPickingListCriteriaTransfer(array $pickingListUuids): PickingListCriteriaTransfer
    {
        return (new PickingListCriteriaTransfer())->setPickingListConditions(
            (new PickingListConditionsTransfer())->setUuids($pickingListUuids),
        );
    }
}
