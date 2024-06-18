<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextDataImport\Business\DataImportStep;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StoreContextDataImport\Business\DataSet\StoreContextDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE_STORE_NOT_FOUND = 'Store not found: %s';

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery<\Orm\Zed\Store\Persistence\SpyStore>
     */
    protected SpyStoreQuery $storeQuery;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery<\Orm\Zed\Store\Persistence\SpyStore> $storeQuery
     */
    public function __construct(SpyStoreQuery $storeQuery)
    {
        $this->storeQuery = $storeQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[StoreContextDataSetInterface::COLUMN_STORE_NAME];

        $storeEntity = $this->storeQuery
            ->filterByName($storeName)
            ->findOne();

        if ($storeEntity === null) {
            throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE_STORE_NOT_FOUND, $storeName));
        }

        $dataSet[StoreContextDataSetInterface::FK_STORE] = $storeEntity->getIdStore();
    }
}
