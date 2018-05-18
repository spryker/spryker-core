<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Model;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSet;

class MerchantWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companySupplierToProductEntity = SpyMerchantQuery::create()
            ->filterByMerchantKey($dataSet[MerchantDataSet::MERCHANT_KEY])
            ->findOneOrCreate();

        $companySupplierToProductEntity
            ->setName($dataSet[MerchantDataSet::NAME])
            ->save();
    }
}
