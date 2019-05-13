<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class PriceProductScheduleAbstractTable extends AbstractTable
{
    protected const COL_CURRENCY = 'fk_currency';
    protected const COL_STORE = 'fk_store';
    protected const COL_NET_PRICE = 'net_price';
    protected const COL_GROSS_PRICE = 'gross_price';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var int
     */
    protected $fkProductAbstract;

    /**
     * @var int
     */
    protected $fkPriceType;

    /**
     * @param int $fkProductAbstract
     * @param int $fkPriceType
     */
    public function __construct(int $fkProductAbstract, int $fkPriceType)
    {
        $this->fkProductAbstract = $fkProductAbstract;
        $this->fkPriceType = $fkPriceType;
        $this->baseUrl = '/';
        $this->defaultUrl = Url::generate('price-product-schedule-gui/index/table', [
            'id-product-abstract' => $fkProductAbstract,
            'id-price-type' => $fkPriceType,
        ])->build();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_CURRENCY => 'Currency',
            static::COL_STORE => 'Store',
            static::COL_NET_PRICE => 'Net price',
            static::COL_GROSS_PRICE => 'Gross price',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
        ]);

        $config->setSortable([
            static::COL_CURRENCY,
            static::COL_STORE,
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $this->setTableIdentifier($this->fkProductAbstract . ' ' . $this->fkPriceType);

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
        $queryResults = $this->runQuery($query, $config, true);

        $priceProductScheduleCollection = [];

        foreach ($queryResults as $priceProductScheduleEntity) {
            $priceProductScheduleCollection[] = $this->generateItem($priceProductScheduleEntity);
        }

        return $priceProductScheduleCollection;
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function prepareQuery(): SpyPriceProductScheduleQuery
    {
        return (new SpyPriceProductScheduleQuery())
            ->leftJoinWithCurrency()
            ->leftJoinWithStore()
            ->filterByFkProductAbstract($this->fkProductAbstract)
            ->filterByFkPriceType($this->fkPriceType);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return array
     */
    protected function generateItem(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        return [
            static::COL_NET_PRICE => $priceProductScheduleEntity->getNetPrice(),
            static::COL_GROSS_PRICE => $priceProductScheduleEntity->getGrossPrice(),
            static::COL_STORE => $priceProductScheduleEntity->getStore()->getName(),
            static::COL_CURRENCY => $priceProductScheduleEntity->getCurrency()->getCode(),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($priceProductScheduleEntity)),
        ];
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string[]
     */
    protected function createActionColumn(SpyPriceProductSchedule $item): array
    {
        return [
            $this->generateEditPriceProductScheduleButton($item),
            $this->generatePriceProductScheduleRemoveButton($item),
        ];
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string
     */
    protected function generateEditPriceProductScheduleButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateEditButton(
            Url::generate('/price-product-schedule-gui/edit', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Edit'
        );
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return string
     */
    protected function generatePriceProductScheduleRemoveButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateRemoveButton(
            Url::generate('/price-product-schedule-gui/delete', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Delete'
        );
    }
}
