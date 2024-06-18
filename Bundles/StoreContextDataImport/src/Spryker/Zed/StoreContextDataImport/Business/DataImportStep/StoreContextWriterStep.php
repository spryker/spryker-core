<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextDataImport\Business\DataImportStep;

use Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StoreContextDataImport\Business\DataSet\StoreContextDataSetInterface;

class StoreContextWriterStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery<\Orm\Zed\StoreContext\Persistence\SpyStoreContext>
     */
    protected SpyStoreContextQuery $storeContextQuery;

    /**
     * @param \Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery<\Orm\Zed\StoreContext\Persistence\SpyStoreContext> $storeContextQuery
     */
    public function __construct(SpyStoreContextQuery $storeContextQuery)
    {
        $this->storeContextQuery = $storeContextQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\StoreContext\Persistence\SpyStoreContext> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeContextEntity = $this->storeContextQuery
            ->filterByFkStore($dataSet[StoreContextDataSetInterface::FK_STORE])
            ->findOneOrCreate();

        $storeContextEntity->setApplicationContextCollection($dataSet[StoreContextDataSetInterface::COLUMN_APPLICATION_CONTEXT_COLLECTION]);
        $storeContextEntity->save();
    }
}
