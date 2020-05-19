<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchPersistenceFactory getFactory()
 */
class SalesReturnPageSearchRepository extends AbstractRepository implements SalesReturnPageSearchRepositoryInterface
{
    /**
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[]
     */
    public function getReturnReasonPageSearchTransfersByReturnReasonIds(array $returnReasonIds): array
    {
        if (!$returnReasonIds) {
            return [];
        }

        $returnReasonPageSearchEntityCollection = $this->getReturnReasonPageSearchEntityCollection(
            new FilterTransfer(),
            $returnReasonIds
        );

        if (!$returnReasonPageSearchEntityCollection->count()) {
            return [];
        }

        $returnReasonPageSearchTransfers = [];

        foreach ($returnReasonPageSearchEntityCollection as $returnReasonPageSearchEntity) {
            $returnReasonPageSearchTransfers[] = (new ReturnReasonPageSearchTransfer())
                ->fromArray($returnReasonPageSearchEntity->toArray(), true)
                ->setIdSalesReturnReason($returnReasonPageSearchEntity->getFkSalesReturnReason());
        }

        return $returnReasonPageSearchTransfers;
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

        $returnReasonPageSearchEntityCollection = $this->getReturnReasonPageSearchEntityCollection(
            $filterTransfer,
            $returnReasonIds
        );

        foreach ($returnReasonPageSearchEntityCollection as $returnReasonPageSearchEntity) {
            /** @var string $data */
            $data = $returnReasonPageSearchEntity->getData();

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($returnReasonPageSearchEntity->getKey())
                ->setLocale($returnReasonPageSearchEntity->getLocale());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $returnReasonIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SalesReturnPageSearch\Persistence\SpySalesReturnReasonPageSearch[]
     */
    protected function getReturnReasonPageSearchEntityCollection(
        FilterTransfer $filterTransfer,
        array $returnReasonIds = []
    ): ObjectCollection {
        $salesReturnReasonPageSearchQuery = $this->getFactory()->getSalesReturnReasonPageSearchPropelQuery();

        if ($returnReasonIds) {
            $salesReturnReasonPageSearchQuery->filterByFkSalesReturnReason_In($returnReasonIds);
        }

        return $this->buildQueryFromCriteria($salesReturnReasonPageSearchQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();
    }
}
