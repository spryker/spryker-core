<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceProductStoreWriterStep implements DataImportStepInterface
{
    /**
     * @var string[]
     */
    protected $idPriceProductStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT_STORE] = $this->getIdPriceProductStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getIdPriceProductStore(DataSetInterface $dataSet): string
    {
        $cacheIndex = $this->buildCacheIndex($dataSet);
        if (isset($this->idPriceProductStoreCache[$cacheIndex])) {
            return $this->idPriceProductStoreCache[$cacheIndex];
        }

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductOfferDataSetInterface::FK_STORE])
            ->filterByFkCurrency($dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT])
            ->filterByNetPrice((int)$dataSet[PriceProductOfferDataSetInterface::VALUE_NET])
            ->filterByGrossPrice((int)$dataSet[PriceProductOfferDataSetInterface::VALUE_GROSS])
            ->findOneOrCreate();

        $priceProductStoreEntity->save();

        $this->idPriceProductStoreCache[$cacheIndex] = $priceProductStoreEntity->getIdPriceProductStore();

        return $this->idPriceProductStoreCache[$cacheIndex];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function buildCacheIndex(DataSetInterface $dataSet): string
    {
        return implode('-', [
            $dataSet[PriceProductOfferDataSetInterface::FK_STORE],
            $dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY],
            $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT],
            $dataSet[PriceProductOfferDataSetInterface::VALUE_NET],
            $dataSet[PriceProductOfferDataSetInterface::VALUE_GROSS],
        ]);
    }
}
