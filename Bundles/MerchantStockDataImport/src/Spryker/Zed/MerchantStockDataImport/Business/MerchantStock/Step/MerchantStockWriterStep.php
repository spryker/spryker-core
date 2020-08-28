<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step;

use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\DataSet\MerchantStockDataSetInterface;

class MerchantStockWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idMerchant = $dataSet[MerchantStockDataSetInterface::MERCHANT_ID];
        $idStock = $dataSet[MerchantStockDataSetInterface::STOCK_ID];
        $merchantStockEntity = $this->createMerchantStockQuery()
            ->filterByFkStock($idStock)
            ->filterByFkMerchant($idMerchant)
            ->findOneOrCreate();

        $merchantStockEntity->setIsDefault(true);

        $merchantStockEntity->save();
    }

    /**
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery
     */
    protected function createMerchantStockQuery(): SpyMerchantStockQuery
    {
        return SpyMerchantStockQuery::create();
    }
}
