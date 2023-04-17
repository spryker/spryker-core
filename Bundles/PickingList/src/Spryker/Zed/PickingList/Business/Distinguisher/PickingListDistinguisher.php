<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Distinguisher;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface;
use Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface;
use Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface;

class PickingListDistinguisher implements PickingListDistinguisherInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface
     */
    protected PickingListExtractorInterface $pickingListExtractor;

    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface
     */
    protected PickingListRepositoryInterface $pickingListRepository;

    /**
     * @var \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface
     */
    protected PickingListGrouperInterface $pickingListGrouper;

    /**
     * @param \Spryker\Zed\PickingList\Business\Extractor\PickingListExtractorInterface $pickingListExtractor
     * @param \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface $pickingListRepository
     * @param \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface $pickingListGrouper
     */
    public function __construct(
        PickingListExtractorInterface $pickingListExtractor,
        PickingListRepositoryInterface $pickingListRepository,
        PickingListGrouperInterface $pickingListGrouper
    ) {
        $this->pickingListExtractor = $pickingListExtractor;
        $this->pickingListRepository = $pickingListRepository;
        $this->pickingListGrouper = $pickingListGrouper;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function setModifiedAttributes(ArrayObject $pickingListTransfers): ArrayObject
    {
        $pickingListUuids = $this->pickingListExtractor->extractPickingListUuids($pickingListTransfers);
        $persistedPickingListCollectionTransfer = $this->getPersistedPickingListTransfers($pickingListUuids);

        $persistedPickingListTransfersIndexedByUuids = $this->pickingListGrouper->getPickingListTransferCollectionIndexedByUuid(
            $persistedPickingListCollectionTransfer,
        );
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        foreach ($pickingListTransfers as $pickingListTransfer) {
            if (!$this->isPersisted($pickingListTransfer, $persistedPickingListTransfersIndexedByUuids)) {
                $pickingListTransfer->setModifiedAttributes(array_keys($pickingListTransfer->toArray(false, true)));

                continue;
            }

            $this->setPickingListModifiedAttributes($pickingListTransfer, $persistedPickingListTransfersIndexedByUuids);
        }

        return $pickingListTransfers;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function getPersistedPickingListTransfers(array $pickingListUuids): PickingListCollectionTransfer
    {
        if (!$pickingListUuids) {
            return new PickingListCollectionTransfer();
        }
        $pickingListCriteriaTransfer = $this->createPickingListCriteriaTransfer($pickingListUuids);

        return $this->pickingListRepository->getPickingListCollection($pickingListCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $persistedPickingListTransfersIndexedByUuids
     *
     * @return bool
     */
    protected function isPersisted(
        PickingListTransfer $pickingListTransfer,
        array $persistedPickingListTransfersIndexedByUuids
    ): bool {
        $pickingListUuid = $pickingListTransfer->getUuid();

        return $pickingListUuid && array_key_exists($pickingListUuid, $persistedPickingListTransfersIndexedByUuids);
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    protected function createPickingListCriteriaTransfer(array $pickingListUuids): PickingListCriteriaTransfer
    {
        $pickingListConditionsTransfer = (new PickingListConditionsTransfer())
            ->setUuids($pickingListUuids);

        return (new PickingListCriteriaTransfer())
            ->setPickingListConditions($pickingListConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $persistedPickingListTransfersIndexedByUuids
     *
     * @return void
     */
    protected function setPickingListModifiedAttributes(
        PickingListTransfer $pickingListTransfer,
        array $persistedPickingListTransfersIndexedByUuids
    ): void {
        $persistedPickingListTransfer = $persistedPickingListTransfersIndexedByUuids[$pickingListTransfer->getUuidOrFail()];

        $persistedPickingListData = $persistedPickingListTransfer->toArray(true, true);
        foreach ($pickingListTransfer->toArray(true, true) as $attribute => $value) {
            if ($persistedPickingListData[$attribute] !== $value) {
                $pickingListTransfer->addModifiedAttribute($attribute);
            }
        }
    }
}
