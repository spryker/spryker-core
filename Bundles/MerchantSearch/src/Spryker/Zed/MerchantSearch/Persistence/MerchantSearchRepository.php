<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchPersistenceFactory getFactory()
 */
class MerchantSearchRepository extends AbstractRepository implements MerchantSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByMerchantIds(FilterTransfer $filterTransfer, array $merchantIds = []): array
    {
        $synchronizationDataTransfers = [];

        $merchantSearchEntityCollection = $this->getMerchantSearchEntityCollection(
            $filterTransfer,
            $merchantIds
        );

        foreach ($merchantSearchEntityCollection as $merchantSearchEntity) {
            /** @var string $data */
            $data = $merchantSearchEntity->getData();

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($merchantSearchEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch[]
     */
    protected function getMerchantSearchEntityCollection(
        FilterTransfer $filterTransfer,
        array $merchantIds
    ): ObjectCollection {
        $merchantSearchQuery = $this->getFactory()->getMerchantSearchPropelQuery();

        if ($merchantIds) {
            $merchantSearchQuery->filterByFkMerchant_In($merchantIds);
        }

        return $this->buildQueryFromCriteria($merchantSearchQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();
    }
}
