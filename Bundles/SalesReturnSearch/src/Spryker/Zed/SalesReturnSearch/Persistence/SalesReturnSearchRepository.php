<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchPersistenceFactory getFactory()
 */
class SalesReturnSearchRepository extends AbstractRepository implements SalesReturnSearchRepositoryInterface
{
    /**
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\ReturnReasonSearchTransfer[]
     */
    public function getReturnReasonSearchTransfersByReturnReasonIds(array $returnReasonIds): array
    {
        if (!$returnReasonIds) {
            return [];
        }

        $returnReasonSearchEntityCollection = $this->getReturnReasonSearchEntityCollection(
            new FilterTransfer(),
            $returnReasonIds
        );

        if (!$returnReasonSearchEntityCollection->count()) {
            return [];
        }

        $returnReasonSearchTransfers = [];

        foreach ($returnReasonSearchEntityCollection as $returnReasonSearchEntity) {
            $returnReasonSearchTransfers[] = (new ReturnReasonSearchTransfer())
                ->fromArray($returnReasonSearchEntity->toArray(), true)
                ->setIdSalesReturnReason($returnReasonSearchEntity->getFkSalesReturnReason());
        }

        return $returnReasonSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getReturnReasonSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $returnReasonIds = []): array
    {
        $synchronizationDataTransfers = [];

        $returnReasonSearchEntityCollection = $this->getReturnReasonSearchEntityCollection(
            $filterTransfer,
            $returnReasonIds
        );

        foreach ($returnReasonSearchEntityCollection as $returnReasonSearchEntity) {
            /** @var string $data */
            $data = $returnReasonSearchEntity->getData();

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($returnReasonSearchEntity->getKey())
                ->setLocale($returnReasonSearchEntity->getLocale());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $returnReasonIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturnSearch\Persistence\SpySalesReturnReasonSearch[]
     */
    protected function getReturnReasonSearchEntityCollection(
        FilterTransfer $filterTransfer,
        array $returnReasonIds = []
    ): ObjectCollection {
        $salesReturnReasonSearchQuery = $this->getFactory()->getSalesReturnReasonSearchPropelQuery();

        if ($returnReasonIds) {
            $salesReturnReasonSearchQuery->filterByFkSalesReturnReason_In($returnReasonIds);
        }

        return $this->buildQueryFromCriteria($salesReturnReasonSearchQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();
    }
}
