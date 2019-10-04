<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStoragePersistenceFactory getFactory()
 */
class MerchantProfileStorageRepository extends AbstractRepository implements MerchantProfileStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function getFilteredMerchantProfileStorageTransfers(FilterTransfer $filterTransfer, array $merchantIds = []): array
    {
        $merchantStorageQuery = $this->getFactory()
            ->createMerchantProfileStorageQuery();
        if ($merchantIds) {
            $merchantStorageQuery->filterByFkMerchant_In($merchantIds);
        }
        $merchantStorageQuery
            ->setOffset($filterTransfer->getOffset())
            ->setLimit($filterTransfer->getLimit());
        $merchantStorageEntities = $merchantStorageQuery->find();

        return $this->mapMerchantProfileStorageEntitiesToMerchantProfileStorageTransfers($merchantStorageEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage[] $merchantProfileStorageEntities
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    protected function mapMerchantProfileStorageEntitiesToMerchantProfileStorageTransfers(ObjectCollection $merchantProfileStorageEntities): array
    {
        $merchantProfileStorageTransfers = [];
        $merchantProfileStorageMapper = $this->getFactory()->createMerchantProfileStorageMapper();
        foreach ($merchantProfileStorageEntities as $merchantProfileStorageEntity) {
            $merchantProfileStorageTransfers[] = $merchantProfileStorageMapper
                ->mapMerchantProfileStorageEntityToMerchantProfileStorageTransfer($merchantProfileStorageEntity, new MerchantProfileStorageTransfer());
        }

        return $merchantProfileStorageTransfers;
    }
}
