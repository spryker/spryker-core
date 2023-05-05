<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchPersistenceFactory getFactory()
 */
class ServicePointSearchRepository extends AbstractRepository implements ServicePointSearchRepositoryInterface
{
    /**
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\ServicePointSearchTransfer>
     */
    public function getServicePointSearchTransfersByServicePointIds(array $servicePointIds): array
    {
        if (!$servicePointIds) {
            return [];
        }

        $servicePointSearchEntityCollection = $this->getServicePointSearchEntityCollection(
            new FilterTransfer(),
            $servicePointIds,
        );

        if (!$servicePointSearchEntityCollection->count()) {
            return [];
        }

        $servicePointSearchTransfers = [];

        foreach ($servicePointSearchEntityCollection as $servicePointSearchEntity) {
            $servicePointSearchTransfers[] = (new ServicePointSearchTransfer())
                ->fromArray($servicePointSearchEntity->toArray(), true)
                ->setIdServicePoint($servicePointSearchEntity->getFkServicePoint());
        }

        return $servicePointSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $servicePointIds = []): array
    {
        $synchronizationDataTransfers = [];

        $servicePointSearchEntityCollection = $this->getServicePointSearchEntityCollection(
            $filterTransfer,
            $servicePointIds,
        );

        foreach ($servicePointSearchEntityCollection as $servicePointSearchEntity) {
            /** @var string $data */
            $data = $servicePointSearchEntity->getData();

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($servicePointSearchEntity->getKey())
                ->setStore($servicePointSearchEntity->getStore());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $servicePointIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearch>
     */
    protected function getServicePointSearchEntityCollection(
        FilterTransfer $filterTransfer,
        array $servicePointIds = []
    ): ObjectCollection {
        $servicePointSearchQuery = $this->getFactory()->getServicePointSearchPropelQuery();

        if ($servicePointIds) {
            $servicePointSearchQuery->filterByFkServicePoint_In($servicePointIds);
        }

        return $this->buildQueryFromCriteria($servicePointSearchQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();
    }
}
