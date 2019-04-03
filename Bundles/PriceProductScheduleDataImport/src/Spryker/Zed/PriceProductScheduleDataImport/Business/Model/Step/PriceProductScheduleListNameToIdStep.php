<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig;

class PriceProductScheduleListNameToIdStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig
     */
    protected $config;

    /**
     * @var string[]
     */
    protected $idPriceProductScheduleListCache = [];

    /**
     * @param \Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig $config
     */
    public function __construct(PriceProductScheduleDataImportConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $listName = $this->config->getPriceProductScheduleListDefaultName();
        if (!isset($this->idPriceProductScheduleListCache[$listName])) {
            $priceProductScheduleListEntity = $this->createPriceProductScheduleListQuery()
                ->filterByName($listName)
                ->findOneOrCreate();

            $priceProductScheduleListEntity->setIsActive(true)->save();

            $this->idPriceProductScheduleListCache[$listName] = $priceProductScheduleListEntity->getIdPriceProductScheduleList();
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_PRICE_PRODUCT_SCHEDULE_LIST] = $this->idPriceProductScheduleListCache[$listName];
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    protected function createPriceProductScheduleListQuery(): SpyPriceProductScheduleListQuery
    {
        return SpyPriceProductScheduleListQuery::create();
    }
}
