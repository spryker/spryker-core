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
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\Map\PriceProductScheduleImportMapInterface;
use Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface;

class ImportSuccessListTable extends AbstractTable
{
    public const KEY_ABSTRACT_SKU = PriceProductScheduleImportMapInterface::KEY_ABSTRACT_SKU;
    public const KEY_CONCRETE_SKU = PriceProductScheduleImportMapInterface::KEY_CONCRETE_SKU;
    public const KEY_STORE = PriceProductScheduleImportMapInterface::KEY_STORE;
    public const KEY_CURRENCY = PriceProductScheduleImportMapInterface::KEY_CURRENCY;
    public const KEY_PRICE_TYPE = PriceProductScheduleImportMapInterface::KEY_PRICE_TYPE;
    public const KEY_PRICE_NET = PriceProductScheduleImportMapInterface::KEY_PRICE_NET;
    public const KEY_PRICE_GROSS = PriceProductScheduleImportMapInterface::KEY_PRICE_GROSS;
    public const KEY_INCLUDED_FROM = PriceProductScheduleImportMapInterface::KEY_INCLUDED_FROM;
    public const KEY_INCLUDED_TO = PriceProductScheduleImportMapInterface::KEY_INCLUDED_TO;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface
     */
    protected $priceProductScheduleGuiRepository;

    /**
     * @var \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected $priceProductScheduleListTransfer;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface $priceProductScheduleGuiRepository
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     */
    public function __construct(
        PriceProductScheduleGuiRepositoryInterface $priceProductScheduleGuiRepository,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ) {
        $this->priceProductScheduleGuiRepository = $priceProductScheduleGuiRepository;
        $this->priceProductScheduleListTransfer = $priceProductScheduleListTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::KEY_ABSTRACT_SKU => static::KEY_ABSTRACT_SKU,
            static::KEY_CONCRETE_SKU => static::KEY_CONCRETE_SKU,
            static::KEY_STORE => static::KEY_STORE,
            static::KEY_CURRENCY => static::KEY_CURRENCY,
            static::KEY_PRICE_TYPE => static::KEY_PRICE_TYPE,
            static::KEY_PRICE_NET => static::KEY_PRICE_NET,
            static::KEY_PRICE_GROSS => static::KEY_PRICE_GROSS,
            static::KEY_INCLUDED_FROM => static::KEY_INCLUDED_FROM,
            static::KEY_INCLUDED_TO => static::KEY_INCLUDED_TO,
        ]);

        $config->setSortable([
            static::KEY_ABSTRACT_SKU,
            static::KEY_CONCRETE_SKU,
            static::KEY_STORE,
            static::KEY_CURRENCY,
            static::KEY_PRICE_TYPE,
            static::KEY_PRICE_NET,
            static::KEY_PRICE_GROSS,
            static::KEY_INCLUDED_FROM,
            static::KEY_INCLUDED_TO,
        ]);

        $config->setUrl(sprintf(
            'table?%s=%d',
            PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST,
            $this->priceProductScheduleListTransfer->getIdPriceProductScheduleList()
        ));

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductTableMap::COL_SKU,
            SpyCurrencyTableMap::COL_CODE,
            SpyStoreTableMap::COL_NAME,
            SpyPriceTypeTableMap::COL_NAME,
            SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM,
            SpyPriceProductScheduleTableMap::COL_ACTIVE_TO,
            SpyPriceProductScheduleTableMap::COL_NET_PRICE,
            SpyPriceProductScheduleTableMap::COL_GROSS_PRICE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
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
    protected function mapPriceProductScheduleCollection(ObjectCollection $priceProductScheduleCollection)
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
        return $this->priceProductScheduleGuiRepository
            ->getPriceProductScheduleQuery()
            ->filterByFkPriceProductScheduleList($this->priceProductScheduleListTransfer->getIdPriceProductScheduleList())
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::KEY_ABSTRACT_SKU)
            ->withColumn(SpyProductTableMap::COL_SKU, static::KEY_CONCRETE_SKU)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, static::KEY_CURRENCY)
            ->withColumn(SpyStoreTableMap::COL_NAME, static::KEY_STORE)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, static::KEY_PRICE_TYPE)
            ->withColumn(SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM, static::KEY_INCLUDED_FROM)
            ->withColumn(SpyPriceProductScheduleTableMap::COL_ACTIVE_TO, static::KEY_INCLUDED_TO)
            ->withColumn(SpyPriceProductScheduleTableMap::COL_NET_PRICE, static::KEY_PRICE_NET)
            ->withColumn(SpyPriceProductScheduleTableMap::COL_GROSS_PRICE, static::KEY_PRICE_GROSS);
    }
}
