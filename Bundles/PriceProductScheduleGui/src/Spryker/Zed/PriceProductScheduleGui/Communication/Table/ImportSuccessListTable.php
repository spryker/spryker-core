<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig;

class ImportSuccessListTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @var \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected $priceProductScheduleListTransfer;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig
     */
    protected $priceProductScheduleGuiConfig;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
     */
    public function __construct(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
    ) {
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
        $this->priceProductScheduleListTransfer = $priceProductScheduleListTransfer;
        $this->priceProductScheduleGuiConfig = $priceProductScheduleGuiConfig;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $fields = $this->priceProductScheduleGuiConfig->getFieldsList();

        $config->setHeader(array_combine($fields, $fields));

        $config->setSortable($fields);

        $config->setDefaultSortField($this->priceProductScheduleGuiConfig->getDefaultSortFieldForSuccessTable());

        $config->setUrl(sprintf(
            'table?%s=%d',
            PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST,
            $this->priceProductScheduleListTransfer->getIdPriceProductScheduleList()
        ));

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductTableMap::COL_SKU,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[] $priceProductScheduleCollection */
        $priceProductScheduleCollection = $this->runQuery($query, $config, true);

        return $this->mapPriceProductScheduleCollection($priceProductScheduleCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $priceProductScheduleCollection
     *
     * @return array
     */
    protected function mapPriceProductScheduleCollection(ObjectCollection $priceProductScheduleCollection): array
    {
        $priceProductScheduleList = [];

        foreach ($priceProductScheduleCollection as $priceProductScheduleEntity) {
            $priceProductScheduleList[] = $priceProductScheduleEntity->toArray();
        }

        return $priceProductScheduleList;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function prepareQuery(): ModelCriteria
    {
        return $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->joinWithStore()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByFkPriceProductScheduleList($this->priceProductScheduleListTransfer->getIdPriceProductScheduleList())
            ->withColumn(SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE, $this->priceProductScheduleGuiConfig->getIdPriceProductScheduleKey())
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, $this->priceProductScheduleGuiConfig->getAbstractSkuKey())
            ->withColumn(SpyProductTableMap::COL_SKU, $this->priceProductScheduleGuiConfig->getConcreteSkuKey())
            ->withColumn(SpyCurrencyTableMap::COL_CODE, $this->priceProductScheduleGuiConfig->getCurrencyKey())
            ->withColumn(SpyStoreTableMap::COL_NAME, $this->priceProductScheduleGuiConfig->getStoreKey())
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, $this->priceProductScheduleGuiConfig->getPriceTypeKey())
            ->withColumn(SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM, $this->priceProductScheduleGuiConfig->getFromIncludedKey())
            ->withColumn(SpyPriceProductScheduleTableMap::COL_ACTIVE_TO, $this->priceProductScheduleGuiConfig->getToIncludedKey())
            ->withColumn(SpyPriceProductScheduleTableMap::COL_NET_PRICE, $this->priceProductScheduleGuiConfig->getValueNetKey())
            ->withColumn(SpyPriceProductScheduleTableMap::COL_GROSS_PRICE, $this->priceProductScheduleGuiConfig->getValueGrossKey());
    }
}
