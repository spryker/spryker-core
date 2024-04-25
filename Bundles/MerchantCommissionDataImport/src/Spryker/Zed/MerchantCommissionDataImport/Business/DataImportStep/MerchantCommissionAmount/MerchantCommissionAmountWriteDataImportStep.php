<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionAmount;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionAmountDataSetInterface;

class MerchantCommissionAmountWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantCommissionAmountEntity = $this->getMerchantCommissionAmountQuery()
            ->filterByFkMerchantCommission($dataSet[MerchantCommissionAmountDataSetInterface::ID_MERCHANT_COMMISSION])
            ->filterByFkCurrency($dataSet[MerchantCommissionAmountDataSetInterface::ID_CURRENCY])
            ->findOneOrCreate();

        $merchantCommissionAmountEntity
            ->setNetAmount($dataSet[MerchantCommissionAmountDataSetInterface::COLUMN_VALUE_NET])
            ->setGrossAmount($dataSet[MerchantCommissionAmountDataSetInterface::COLUMN_VALUE_GROSS])
            ->save();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery
     */
    protected function getMerchantCommissionAmountQuery(): SpyMerchantCommissionAmountQuery
    {
        return SpyMerchantCommissionAmountQuery::create();
    }
}
