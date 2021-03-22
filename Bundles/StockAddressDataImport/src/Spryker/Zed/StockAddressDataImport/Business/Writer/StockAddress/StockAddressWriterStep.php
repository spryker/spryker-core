<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress;

use Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet\StockAddressDataSetInterface;

class StockAddressWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $stockAddressEntity = SpyStockAddressQuery::create()
            ->filterByFkStock($dataSet[StockAddressDataSetInterface::ID_STOCK])
            ->findOneOrCreate();

        $stockAddressEntity->fromArray($dataSet->getArrayCopy());
        $stockAddressEntity->setFkCountry($dataSet[StockAddressDataSetInterface::ID_COUNTRY])
            ->setFkRegion($dataSet[StockAddressDataSetInterface::ID_REGION]);

        $stockAddressEntity->save();
    }
}
