<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use DateTime;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

abstract class AbstractPriceProductScheduleTable extends AbstractTable
{
    protected const COL_SKU_PRODUCT_ABSTRACT = 'spy_product_abstract.sku';
    protected const COL_SKU_PRODUCT = 'spy_product.sku';
    protected const COL_PRICE_TYPE = 'spy_price_type.name';
    protected const COL_CURRENCY = 'fk_currency';
    protected const COL_STORE = 'fk_store';
    protected const COL_NET_PRICE = 'net_price';
    protected const COL_GROSS_PRICE = 'gross_price';
    protected const COL_ACTIVE_FROM = 'active_from';
    protected const COL_ACTIVE_TO = 'active_to';

    protected const PRICE_NUMERIC_PATTERN = '/[^0-9]+/';

    /**
     * @var int
     */
    protected $idPriceProductScheduleList;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    protected $rowFormatter;

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface $rowFormatter
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param int $idPriceProductScheduleList
     */
    public function __construct(
        RowFormatterInterface $rowFormatter,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        int $idPriceProductScheduleList
    ) {
        $this->idPriceProductScheduleList = $idPriceProductScheduleList;
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
        $this->rowFormatter = $rowFormatter;
        $this->baseUrl = '/';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeaders($config);

        $config = $this->setRawColumns($config);

        $config->setSearchable([
            static::COL_SKU_PRODUCT_ABSTRACT,
            static::COL_SKU_PRODUCT,
        ]);

        $config->setSortable([
            static::COL_SKU_PRODUCT_ABSTRACT,
            static::COL_SKU_PRODUCT,
            static::COL_PRICE_TYPE,
            static::COL_CURRENCY,
            static::COL_STORE,
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
            static::COL_ACTIVE_FROM,
            static::COL_ACTIVE_TO,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawColumns(TableConfiguration $config): TableConfiguration
    {
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
        $queryResults = $this->runQuery($query, $config, true);

        $priceProductScheduleCollection = [];

        foreach ($queryResults as $priceProductScheduleEntity) {
            $priceProductScheduleCollection[] = $this->generateItem($priceProductScheduleEntity);
        }

        return $priceProductScheduleCollection;
    }

    /**
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        return [
            static::COL_SKU_PRODUCT_ABSTRACT => 'Abstract SKU',
            static::COL_SKU_PRODUCT => 'Concrete SKU',
            static::COL_PRICE_TYPE => 'Price Type',
            static::COL_CURRENCY => 'Currency',
            static::COL_STORE => 'Store',
            static::COL_NET_PRICE => 'Net price',
            static::COL_GROSS_PRICE => 'Gross price',
            static::COL_ACTIVE_FROM => 'Start from (included)',
            static::COL_ACTIVE_TO => 'Finish at (included)',
        ];
    }

    /**
     * @return array
     */
    protected function getCustomHeaders(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeaders(TableConfiguration $config): TableConfiguration
    {
        $defaultHeaders = $this->getDefaultHeaders();
        $customHeaders = $this->getCustomHeaders();

        $config->setHeader(array_merge($defaultHeaders, $customHeaders));

        return $config;
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function prepareQuery(): SpyPriceProductScheduleQuery
    {
        return $this->priceProductScheduleQuery
            ->leftJoinWithCurrency()
            ->leftJoinWithStore()
            ->leftJoinWithProductAbstract()
            ->leftJoinWithProduct()
            ->leftJoinWithPriceType()
            ->filterByFkPriceProductScheduleList($this->idPriceProductScheduleList);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateDefaultItemFields(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        return [
            static::COL_SKU_PRODUCT_ABSTRACT => $this->getAbstractSkuFromPriceProductScheduleEntity($priceProductScheduleEntity),
            static::COL_SKU_PRODUCT => $this->getConcreteSkuFromPriceProductScheduleEntity($priceProductScheduleEntity),
            static::COL_PRICE_TYPE => $priceProductScheduleEntity->getPriceType()->getName(),
            static::COL_NET_PRICE => $this->formatMoney($priceProductScheduleEntity->getNetPrice(), $priceProductScheduleEntity),
            static::COL_GROSS_PRICE => $this->formatMoney($priceProductScheduleEntity->getGrossPrice(), $priceProductScheduleEntity),
            static::COL_STORE => $priceProductScheduleEntity->getStore()->getName(),
            static::COL_CURRENCY => $priceProductScheduleEntity->getCurrency()->getCode(),
            static::COL_ACTIVE_FROM => $this->formatDateTime($priceProductScheduleEntity->getActiveFrom(), $priceProductScheduleEntity->getFkStore()),
            static::COL_ACTIVE_TO => $this->formatDateTime($priceProductScheduleEntity->getActiveTo(), $priceProductScheduleEntity->getFkStore()),
        ];
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateCustomItemFields(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        return [];
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateItem(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        $defaultItemFields = $this->generateDefaultItemFields($priceProductScheduleEntity);
        $customItemFileds = $this->generateCustomItemFields($priceProductScheduleEntity);

        return array_merge($defaultItemFields, $customItemFileds);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string|null
     */
    protected function getAbstractSkuFromPriceProductScheduleEntity(SpyPriceProductSchedule $priceProductScheduleEntity): ?string
    {
        $productAbstractEntity = $priceProductScheduleEntity->getProductAbstract();

        if ($productAbstractEntity === null) {
            return null;
        }

        return $productAbstractEntity->getSku();
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string|null
     */
    protected function getConcreteSkuFromPriceProductScheduleEntity(SpyPriceProductSchedule $priceProductScheduleEntity): ?string
    {
        $productConcreteEntity = $priceProductScheduleEntity->getProduct();

        if ($productConcreteEntity === null) {
            return null;
        }

        return $productConcreteEntity->getSku();
    }

    /**
     * @param \DateTime $dateTime
     * @param int $fkStore
     *
     * @return string
     */
    protected function formatDateTime(DateTime $dateTime, int $fkStore): string
    {
        return $this->rowFormatter->formatDateTime($dateTime, $fkStore);
    }

    /**
     * @param int|null $amount
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string|null
     */
    protected function formatMoney(?int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): ?string
    {
        if ($amount === null) {
            return null;
        }

        return $this->rowFormatter->formatMoney($amount, $priceProductScheduleEntity);
    }
}
