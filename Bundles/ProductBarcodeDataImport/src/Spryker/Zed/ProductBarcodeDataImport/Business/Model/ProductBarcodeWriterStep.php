<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductBarcodeDataImport\Business\Model\DataSet\ProductBarcodeDataSetInterface;

class ProductBarcodeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productBarcodeEntity = SpyProductQuery::create()
            ->findOneByIdProduct($dataSet[ProductBarcodeDataSetInterface::ID_PRODUCT_KEY])
            ->setEan($dataSet[ProductBarcodeDataSetInterface::PRODUCT_EAN_KEY]);

        $productBarcodeEntity->save();
    }
}
